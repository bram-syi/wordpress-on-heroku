<?php

require_once(__DIR__ . '/api.php');
require_once(ABSPATH . '/database/db-functions.php');
require_once(ABSPATH . '/wp-content/mu-plugins/teams.php');

class TeamApi extends Api {

  // GET
  //    team: ID of a specific team
  //    search: terms to search
  public static function get($req) {
    global $wpdb;

    $record = req($req, array('id', 'team:slug:name:post_name', 'title:post_title:team_title', 'partner', 'campaign', '#independent'));
    $params = req($req, array('order'));

    $c = CampaignApi::getOne($record->campaign);
    if ($c == NULL)
      return null;

    $query = new ApiQuery("p.id as id, p.id as team_id, p.post_name as team, p.post_name as team_name, p.post_title as team_title, p.post_content as team_body",
      "wp_{$c->partner_id}_posts p");
    $query->where("p.post_type='page' AND p.post_status='publish' AND p.post_parent=%d", $c->page_id); // only team pages

    $query->table("LEFT JOIN wp_{$c->partner_id}_postmeta pm ON pm.post_id=p.id AND pm.meta_key='is_independent'");
    $query->field("pm.meta_value as independent");

    $query->table("LEFT JOIN wp_{$c->partner_id}_postmeta pm2 ON pm2.post_id=p.id AND pm2.meta_key='goal'");
    $query->field("pm2.meta_value as goal");

    $partner = PartnerApi::join($query, $c->partner_id);
    $campaign = CampaignApi::join($query, $wpdb->prepare("%s", $c->id));

    $query->field("CONCAT({$partner}.url, {$campaign}.name, '/', p.post_name) as team_url");

    // SELECT fundraiser totals

    $subquery = $wpdb->prepare("
      select p.id as team_id, f.* 
      from campaigns f
      LEFT JOIN wp_{$c->partner_id}_posts p ON p.post_title=f.team AND p.post_type='page' AND p.post_status='publish' AND p.post_parent=%d
      WHERE f.theme = %s",
      $c->page_id, $c->name);

    $query->table("LEFT JOIN ($subquery) fr on (fr.team_id = p.id) OR (fr.team_id IS NULL AND pm.meta_value = 1)");
    $query->fields("
      IFNULL(SUM(fr.raised),0) as raised,
      IFNULL(SUM(fr.offline),0) as offline,
      SUM(fr.tip) as tip,
      IFNULL(SUM(fr.donors_count),0) as donors,
      SUM(IF(fr.archived = 0, 1, 0)) as active,
      SUM(IF(fr.archived = 1, 1, 0)) as archived");
    $query->group("p.id");

    if ($record->id > 0) 
      $query->where_expr("p.id", $record->id);
    if (!empty($record->team))
      $query->where_expr("p.post_name", $record->team);
    if (!empty($record->title))
      $query->where_expr("p.post_title", $record->title);
    if (isset($record->independent))
      $query->where("pm.meta_value = %d", $record->independent);

    switch ($params->order) {
      case 'raised':
        $query->order("raised DESC");
        break;

      case 'donors':
        $query->order("donors DESC");
        break;

      case 'alphabetical':
      default:
        break;
    }
    $query->order("IFNULL(p.post_title,'') ASC");

    return $query->map_results(array(__CLASS__, 'format_row'));
  }


  // Join team fields, using $field as the join variable and $on as blog_id
  public static function join(ApiQuery &$query, $on, $field = "team", $group = "team") {
    list($partner_id, $page_id, $title) = $on;

    $query->table("LEFT JOIN wp_{$partner_id}_posts {$field} ON {$field}.post_title={$title} AND {$field}.post_type='page' AND {$field}.post_status='publish' AND {$field}.post_parent=%d", $page_id);
    $query->fields("{$field}.id as {$group}_id, {$field}.post_title as {$group}_title");

    return $field;
  }


  public static function update($req) {
    // Map the request to a mysql record
    $record = req($req, array('ID:team_id', 'campaign:campaign_name', 'post_name:name:team_name', 'post_title:title:team_title', 'post_content:body:team_body', 'goal', '#independent'));

    return static::insert_or_update($record);
  }

  protected static function insert_or_update(&$record) {
    global $wpdb;

    $record->campaign = CampaignApi::getOne($record->campaign);
    static::validate_record($record);

    $campaign = $record->campaign;
    unset($record->campaign);

    if (!$record->ID) {

      static::make_slug($record->post_name, $record->post_title);

      $record->ID = db_new_page(
        $campaign->partner_id,
        1,
        $record->post_title,
        '',
        $record->post_name,
        0,
        $campaign->page_id
      );

      // setup theme promos (sidebar, header, etc)
      $parent_promo = $wpdb->get_var($wpdb->prepare(
        "select id from wp_{$campaign->partner_id}_posts where post_name = %s", "sidebar-{$campaign->name}"
      ));
      db_new_page(
        $campaign->partner_id,
        1,
        $record->post_title,
        '<div><!-- empty sidebar --></div>',
        $record->post_name,
        0,
        $parent_promo,
        'promo'
      );
    } else {
      $old = static::getOne(array(
        'campaign' => $campaign->name,
        'id' => $record->ID
      ));

      if (!$old)
        throw new Exception("invalid id");

      if (isset($record->post_title) && $record->post_title != $old->post_title) {
        // Rename fundraisers on this team to the new team name
        $wpdb->update("campaigns", array(
          'team' => $record->post_title
        ), array(
          'theme' => $campaign->name,
          'team' => $old->team_title
        )); 

        // TODO: rename sidebar
      }
    }

    // add postmeta
    switch_to_blog($campaign->partner_id);

    update_post_meta($record->ID, '_wp_page_template', 'chapter-page.php');
    if (isset($record->goal))
      update_post_meta($record->ID, 'goal', $record->goal);
    unset($record->goal);
    if (isset($record->independent))
      update_post_meta($record->ID, 'is_independent', $record->independent);
    unset($record->independent);
    restore_current_blog();

    $wpdb->update("wp_{$campaign->partner_id}_posts", (array)$record, array('ID' => $record->ID));

    // Return the updated/inserted record
    return static::getOne(array(
      'campaign' => $campaign->name,
      'id' => $record->ID
    ));
  }

  public static function validate_record(&$record) {
    if (!isset($record->campaign))
      throw new Exception("please choose a campaign");

    if (empty($record->post_title))
      throw new Exception("please choose a title");
  }

  public static function getColumns() {
    $columns = array(
      'id' => 'id',

      'group:team' => array(
        'team_id' => 'id',
        'team_name' => TRUE,
        'team_title' => TRUE,
        'team_url' => 'url'
      ),

      'independent' => 'bool',

      'goal' => 'money',
      'donors' => 'int',
      'raised' => 'money',
      'offline' => 'money'
    );

    if (Api::hasPermission('/tips'))
      $columns['tip'] = 'money';

    $columns['group:partner'] = array(
      'partner_name' => TRUE,
      'partner_domain' => TRUE,
      'partner_id' => 'id'
    );

    $columns['group:campaign'] = array(
      'campaign_name' => 'id',
      'campaign_title' => 'text'
    );

    return $columns;
  }

  public static function getActions($req) {
    return self::menu('Team',
      self::action('Fundraisers', "fundraisers"),
      self::action('Donations', "donations"),
      self::action('Settings', "settings")
    );
  }

  public static function getForms($req) {
    $settings = self::menu('Settings',
      self::menu('Team',
        self::field('Team name', 'team_title'),
        self::field('Indepdendent', 'independent', 'check')
      ),
      self::field('Goal', 'goal', 'money'),
      self::field('Message', 'team_body', 'text')
    );

    return array(
      'settings' => $settings
    );
  }

  public static function format_row($row) {
    $row->team_body = trim($row->team_body);

    // TODO
    return $row;
  }
}

// Register this API
register_api(__FILE__, 'TeamApi');
