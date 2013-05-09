<?php

require_once(__DIR__. '/api.php');
require_once(ABSPATH . '/a/api/promo.php');
require_once(ABSPATH . '/a/api/partner.php');
require_once(ABSPATH . '/a/api/user.php');
require_once(ABSPATH . '/a/api/campaign.php');
require_once(ABSPATH . '/a/api/team.php');
require_once(ABSPATH . '/wp-content/mu-plugins/images.php');
require_once(ABSPATH . '/wp-content/mu-plugins/users.php');

class FundraiserApi extends Api {

  public static function get($req) {
    $record = req($req, array('post_id:id', 'campaign', 'partner', 'fundraiser', 'slug:name', 'search', '#archived', 'team', 'is_fund'));
    $params = req($req, array('view','order','limit:page_limit'));
 
    $query = new ApiQuery(
      "c.ID as id, c.post_title as title, c.post_content as body",
      "wp_1_posts c");

    $fr = static::join($query, "c.ID");
    $query->fields("
      {$fr}.donors_count as donors, {$fr}.gifts_count as gifts,
      {$fr}.goal, {$fr}.raised, ROUND({$fr}.raised/{$fr}.donors_count, 2) as per_donor,
      {$fr}.tip, ROUND({$fr}.tip / {$fr}.raised, 3) as tip_rate,
      {$fr}.offline, {$fr}.updates, {$fr}.public, {$fr}.archived,
      -- count(DISTINCT i.id) as invites,
      c.post_date as created, {$fr}.start_date as started, -- {$fr}.first_donated,
      {$fr}.last_donated, {$fr}.end_date as ended,
      {$fr}.guid as url, {$fr}.team,
      {$fr}.owner,
      {$fr}.theme, {$fr}.theme as campaign, '' as status, {$fr}.custom as custom");

    $query->table("LEFT JOIN wp_1_postmeta pm on pm.post_id = c.ID AND pm.meta_key='syi_tag'");
    $query->field("IF(IFNULL({$fr}.tags,'') != '', {$fr}.tags, pm.meta_value) as tags");

    $campaign = CampaignApi::join($query, "{$fr}.theme");
    $partner = PartnerApi::join($query, "{$campaign}.blog_id");
    $user = UserApi::join($query, "{$fr}.owner");

    $query->field("{$user}.display_name");

    $query->table("LEFT JOIN donationAcct da on (da.event_id = c.ID) AND (IFNULL(da.event_id,0) != 0)");
    $query->field("SUM(da.balance) AS unallocated");

    if (!empty($record->campaign)) {
      $query->where("fr.theme=%s", $record->campaign);
    }

    if (!empty($record->partner)) {
      $query->where("{$campaign}.blog_id = %d", get_site_id($record->partner));
    }

    if (!empty($record->post_id))
      $query->where_expr("{$fr}.post_id", $record->post_id);
    if (!empty($record->fundraiser))
      $query->where("{$fr}.post_id=%d", $record->fundraiser);

    $query->field("({$fr}.post_id = {$campaign}.fr_id) as is_fund");
    if (isset($record->is_fund))
      $query->where("({$fr}.post_id = {$campaign}.fr_id) = %d", $record->is_fund);

    if (!empty($record->slug))
      $query->where("c.post_name", $record->slug);

    if (isset($record->public))
      $query->where("{$fr}.public = %d", $record->public);

    if (isset($record->archived))
      $query->where("{$fr}.archived = %d", $record->archived);
    else
      $query->where("{$fr}.archived = 0");

    if (!empty($record->team)) {
      // Fetch this team first, it's way easier than trying to build it into the query.
      $t = TeamApi::getOne(array(
        'campaign' => $record->campaign,
        'team' => $record->team
      ));

      $team = TeamApi::join($query, array(
        $t->partner_id,
        $t->campaign_page_id,
        "{$fr}.team"
      ));

      if ($t->independent)
        $query->where("{$team}.id = %d OR {$team}.id IS NULL", $t->team_id);
      else
        $query->where("{$team}.id = %d", $t->team_id);
    }

    if (!empty($record->search)) {
      $s = "% $record->search%";
      $query->where("CONCAT(' ',fundraiser_name) LIKE %s", $s);
    }
 
    if (!empty($record->started)) 
      $query->where_expr("c.start_date", $record->started);
 
    $query->where("c.post_type = 'event'");
    $query->group("c.ID");

    switch ($params->view) {
      case 'stats':
        $query->fields("fr.gifts_count,fr.donors_count,fr.supporters_count,fr.post_name,fr.guid,fr.pledge_count");
        break;
    }

    switch ($params->order) {
      case 'donors':
        $query->order("IFNULL(donors,0) DESC");
        break;
      case 'raised':
        $query->order("IFNULL(raised,0) DESC");
        break;
      case 'random':
        $query->order("RAND()");
        break;
      case 'username':
      case 'name':
        $query->order("IFNULL(fundraiser_name,'') ASC");
        break;
      default:
      case 'display_name':
        break;
    }
    $query->order("display_name ASC");

    return static::expand($query, $params);
  }


  // Join fundraiser fields, using $field as the join variable and $on as blog_id
  public static function join(ApiQuery &$query, $on, $field = "fr", $group = "fundraiser") {
    $query->fields("
      CONCAT(fn_{$field}.meta_value,' ', ln_{$field}.meta_value) as {$group}_owner,
      {$field}.post_id as {$group}_id, 
      {$field}.owner as {$group}_owner_id,
      {$field}.guid as {$group}_url,
      {$field}.post_title as {$group}_name, 
      {$field}.team as {$group}_team");

    $query->table("LEFT JOIN campaigns {$field} ON {$field}.post_id = $on");
    $query->table("
      LEFT JOIN wp_usermeta fn_{$field} ON fn_{$field}.user_id={$field}.owner and fn_{$field}.meta_key = 'first_name'
      LEFT JOIN wp_usermeta ln_{$field} ON ln_{$field}.user_id={$field}.owner and ln_{$field}.meta_key = 'last_name'");

    return $field;
  }





  // Format a fundraiser result set - this can be called from external classes
  // that provide a query that returns (at least) a proper set of columns
  public static function expand($query, $params = NULL) {
    $results = $query->map_results(array(__CLASS__, 'format_row'));

    if ($params->view == 'search')
      $results = $query->map_results(array(__CLASS__, 'view_search'), $results);

    if ($params->view == 'gallery') 
      $results = $query->map_results(array(__CLASS__, 'view_gallery'), $results);

    return $results;
  }

  public function view_search($row) {
    $row->image = fundraiser_image_src($row->id, 50, 50);
    return $row;
  }

  public function view_gallery($row) {
    $row->image = fundraiser_image_src($row->id, 200,200);

    return $row;
  }

  public static function update($req) {
    $record = req($req, array('post_id:id','owner','theme:campaign','team','post_title:title','post_content:appeal','post_name:slug','post_author:user_id','goal','tags:tag','start_date:start','end_date:ended','#public','#archived','offline', 'custom'));

    // Create a new fundraiser user & set as owner (if supplied)
    $new_user = (object)req($req, 'new_user');
    if ($new_user != NULL && !empty($new_user->email)) {
      $user_id = email_exists($new_user->email);
      if (!$user_id) {
        list($username, $user_id) = createWpAccount($new_user->email, $new_user->firstName, $new_user->lastName);
        if (!$username)
          throw new Exception($error_wp_signin);
      }
      $record->owner = $user_id;
    }
    // TODO: set owner if user details are provided

    return static::insert_or_create($record);
  }

  public static function insert_or_create($record) {
    global $wpdb;

    static::validate($record);

    if ($record->post_id == NULL) {
      // Create a new user
      if (!isset($record->owner) || $record->owner <= 1)
        $record->owner = 1;
      if (!isset($record->public))
        $record->public = TRUE;

      $campaign = CampaignApi::getOne($record->theme);
      if (!$campaign)
        throw new Exception("unknown campaign");

      $p = req($record, array('post_title','post_content'));
      $p->post_title = eor($p->post_title, $campaign->post_title);
      $p->post_content = eor($p->post_content, $campaign->post_content);
      $p->post_status = 'publish';
      $p->post_type = 'event';
      $p->post_author = $record->owner;
      $p->comment_status = 'closed';

      $id = wp_insert_post((array)$p);
      if (is_wp_error($id))
        throw new Exception("error creating fundraiser");
      $record->post_id = $id;

      // A hook has already created the campaign when we inserted the post.
      // Fall through to update case to update our tables
    }

    $attrs = req($record, array('post_id', 'owner:user_id', 'start_date:started','end_date:ended','#public','theme:campaign','goal','team','tags', 'offline'));
    if (isset($record->custom))
      $attrs->custom = indent_json(json_encode($record->custom, JSON_NUMERIC_CHECK));

    $wpdb->update('campaigns', (array)$attrs, array('post_id'=>$attrs->post_id));
    update_campaign_stats($record->post_id);

    return static::getOne($record->post_id);
  }

  public static function validate(&$record) {
    if (!isset($record->owner)) {
      throw new Exception("please specify an owner");
    }

/*
    $body = $record->post_content;
    if (!$body || trim($body) == '')
      throw new Exception("Post body can't be blank");
*/
  }

  public static function format_row($row) {
    if (!static::hasPermission("/partner/$row->partner_domain"))
      return null;

    if ($row->email)
      protect_email($row->email);
    if ($row->user_email)
      protect_email($row->user_email);

    static::unpack_data($row, 'custom');

    if ($row->body)
      $row->body = preg_replace('#<br\s*?/?>#i', "\n", $row->body);

    return $row;
  }

  public static function getColumns() {
    $columns = array(
      'group:fundraiser' => array(
        'fundraiser_id' => 'id',
        'fundraiser_owner' => 'id',
        'fundraiser_owner_id' => 'id',
        'fundraiser_name' => TRUE,
        'fundraiser_url' => 'url',
        'fundraiser_team' => TRUE
      ),

      // 'created' => 'date',
      'donors' => 'int',
      'raised' => 'money',
      'offline' => 'money',
      'archived' => 'check'
    );

    if (Api::hasPermission('/tips'))
      $columns['tip'] = 'money';

    $columns['group:user'] = array(
      'user_name' => TRUE,
      'user_first' => TRUE,
      'user_last' => TRUE,
      'user_email' => TRUE,
      'user_id' => 'id'
    );

    $columns['group:partner'] = array(
      'partner_name' => TRUE,
      'partner_domain' => TRUE,
      'partner_id' => 'id'
    );

    $columns['group:campaign'] = array(
      'campaign_name' => 'id',
      'campaign_title' => 'text'
    );


    global $ADMIN_PARTNER; // PRATHAM custom
    if ($ADMIN_PARTNER == 'pratham') {
      $columns['custom.coordinator'] = "custom";
      $columns['custom.age'] = "custom";
      $columns['custom.grade'] = "custom";
      $columns['custom.gender'] = "custom";
      $columns['custom.school'] = "custom";
      $columns['custom.birthday'] = "custom";
      $columns['custom.parents'] = "custom";
      $columns['custom.parent_email'] = "custom";
      $columns['custom.parent_phone'] = "custom";
    }
   
    return $columns;
  }

  public static function getActions($req) {
    return self::menu('Fundraiser',
      self::action('Settings', 'settings'),
      self::action('Donations', 'donations')
      // activity
      // supporters
    );
  }

  public static function getForms($req) {
    // TODO: data-driven
    $settings = self::menu('Settings',
      self::field('Team', 'team', 'team', array(
        'placeholder' => 'choose a team...',
        'other' => 'other...'
      )),
      self::menu('Goal',
        self::field('Goal', 'goal', 'money', array(
          'placeholder' => '$ to raise'
        )),
        self::field('Raised offline', 'offline', 'money', array(
          'before' => 'raised offline: ',
          'placeholder' => '$ amount raised offline'
        ))
      ),
      self::permitted('advanced',
        self::field('Tags', 'tags', array(
          'required' => TRUE,
          'after' => ' (overrides campaign tags)'
        ))
      ),
      self::field('Title', 'title', 'title'),
      self::field('Body', 'body', 'text')
    );

    global $ADMIN_PARTNER; // PRATHAM custom
    if ($ADMIN_PARTNER == 'pratham') {
      // TODO: data-driven custom fields
      global $PRATHAM_FIELDS;
      foreach ($PRATHAM_FIELDS as $field=>$def) {
        $settings['items'][] = self::field($def['label'], $field, $def);
      }
    }

    return array(
      'settings' => $settings
    );
  }

  // ==================================================================================
  public static function getOwner($id) {
    global $wpdb;

    // Because some campaigns are displayed on blog > 1
    $owner = $wpdb->get_var($wpdb->prepare(
      "SELECT owner FROM campaigns WHERE post_id=%d",
      $id));

    return $owner;
  }
}

// TODO: Move into API

// Rewrite all campaign links to go to a /members/ page if appropriate
// This affects any link throughout the system

function get_campaign_owner($id) {
  return FundraiserApi::getOwner($id);
}

function set_campaign_owner($id, $uid) {
  // TODO
}

global $PRATHAM_FIELDS;
$PRATHAM_FIELDS = array(
  'custom.coordinator' => array(
    'label' => 'Readathon coordinator',
    'prompt' => 'Who is your Readathon coordinator?',
    'type' => 'string'
  )
);






















register_api(__FILE__, 'FundraiserApi');
