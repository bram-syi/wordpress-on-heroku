<?php

require_once(__DIR__.'/api.php');
require_once(__DIR__.'/campaign.php');

class PartnerApi extends Api {

  // GET
  //   search: terms to search
  //   blog_id
  //   domain: domain of a partner
  //   name: name of a partner
  //   
  public static function get($req) {
    $record = req($req, array('search','id:blog_id','domain','#private','#live','tag:tags:region', 'view', 'order'));

    $fields = ($record->view == 'dropdown' ? "c.blog_id,c.domain,c.name" : "c.*, CONCAT('charity-images/charity-',c.domain,'.jpg') as image");
    $query = new ApiQuery($fields, "charity c");
      
    if (!empty($record->id))
      $query->where_expr("c.blog_id", $record->id);
    else
      $query->where("c.blog_id > 1");

    if (!empty($record->domain))
      $query->where("c.domain = %s", $record->domain);

    if (isset($record->private)) {
      $query->where("c.private = %d", $record->private);
    }

    if (isset($record->live))
      $query->where("c.live = %d", $record->live);

    if ($record->view == "detail")
      $query->field("1 as detail");

    if (!empty($record->tag)) {
      $query->table('left join gift g on g.blog_id=c.blog_id');
      $query->group("c.blog_id");
      $query->where(build_tag_query('g.tags', $record->tag));
    }

    if (!empty($record->search)) {
      global $wpdb;

      $score = $wpdb->prepare("MATCH(domain,name,terms,location,description) AGAINST(%s IN BOOLEAN MODE)", $record->search);

      $query->where($score);
      $query->field("$score AS score");
      $query->order("score DESC");
    }

    switch ($record->order) {
      case 'name':
      default:
        break;
    }
    $query->order("c.name ASC");

    $results = $query->map_results(array(__CLASS__, 'format_row'));
    if ($record->view == 'gallery')
      $results = $query->map_results(array(__CLASS__, 'add_gallery'), $results);

    return $results;
  }

  // Join partner fields, using $field as the join variable and $on as blog_id
  public static function join(ApiQuery &$query, $on, $field = "partner", $group = "partner") {
    $query->table("LEFT JOIN charity $field on $field.blog_id=$on");

    $query->fields("
      $field.blog_id as {$group}_id,
      $field.domain as {$group}_domain,
      $field.url as {$group}_url,
      $field.name as {$group}_name");

    return $field;
  }

  // CREATE/UPDATE
  public static function update($req) {
    // TODO: does not currently save customizations to partner_page

    $record = req($req, array('blog_id:id', 'domain', 'name', 'location', 'description', 'terms', '#private', '#live', 'gallery'));
    if (isset($record->private))
      $record->private = ($record->private == TRUE);
    return static::insert_or_update($record);
  }

  protected static function insert_or_update($record) {
    global $wpdb;

    static::validate($record);

    // Save any promos that were associated with this record
    static::save_gallery($record);
    
    $blog_id = $record->blog_id;
    if ($blog_id == NULL) {
      // Create a new site
      if (domain_exists($record->domain))
        throw new Exception("Sorry, that domain already exists");

      $record->private = TRUE;
      $domain = $wpdb->get_var("SELECT domain FROM wp_site");

      global $NEW_CHARITY_CREATION;
      $NEW_CHARITY_CREATION = TRUE;
      $blog_id = wpmu_create_blog("{$record->domain}.$domain", NULL, 
        $record->name, 1/*admin*/,
        array( 'public' => !empty($record->private) ));
      unset($NEW_CHARITY_CREATION);

      if (is_wp_error($blog_id))
        throw new Exception($blog_id->get_error_message());

      $record->live = TRUE;
    }

    // TODO: go-live
    // TODO: Handle the domain name changing?
    // TODO: Handle public/private transition

    $where = array( 'blog_id' => $blog_id );
    $wpdb->update('charity', (array)$record, $where);

    if (isset($record->description))
      update_blog_option($blog_id, 'blogdescription', $record->description);

    return static::getOne($blog_id);
  }

  public static function validate(&$data) {
    // Blog ID must be sent
    if ($data->blog_id && ($data->blog_id < 0))
      throw new Exception("Invalid ID");

    // Domain must be alphanumeric
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $data->domain))
      throw new Exception("Invalid domain");

    $data->private = !empty($data->private);
    $data->live = !empty($data->live);

    // TODO: check domain, location, description,terms are valid, non-HTML
  }

  public static function add_gallery($record) {
    $record->gallery = array(
      'header' => PromoApi::load("{$record->blog_id}/header"),
      'quickfacts' => PromoApi::load("{$record->blog_id}/cause"),
      'certifiedorg' => PromoApi::load("{$record->blog_id}/certified"),
      'about' => PromoApi::load("{$record->blog_id}/about")
    );

    return $record;
  }


  public static function format_row($row) {
    if (!static::hasPermission("/partner/$row->domain"))
      return null;

    if (!empty($row->image)) {
      $row->image = "fetch/w_200,h_200,c_fill,g_faces/http://" . $_SERVER["SERVER_NAME"] . "/wp-content/$row->image";
      $row->image = versioned_url($row->image);
      $row->image = "http://res.cloudinary.com/seeyourimpact/image/$row->image";
    }

    $row->tag = "#$row->domain"; // Automatic gift tag

    if (!isset($row->partner_page)) {
      $row->partner_page = new stdClass;
      $row->partner_page->show = (object)array(
        'header' => TRUE,
        'quickfacts' => TRUE,
        'gifts' => TRUE,
        'certifiedorg' => TRUE,
        'stories' => TRUE,
        'activity' => TRUE,
        'comments' => TRUE
      );
    }

    if ($row->detail) {
      $row->front_id = get_blog_option($row->blog_id, 'page_on_front');
      if ($row->front_id > 0)
        $row->campaign = CampaignApi::getOne(array(
          'blog_id' => $row->blog_id,
          'page_id' => $row->front_id
        ));
    }

    return $row;
  }

  public static function getActions($req) {
    return self::menu('Partner',
      //TODO: self::action("Dashboard", 'dashboard'),
      self::action("Campaigns", 'campaigns'),
      self::action("Fundraisers", 'fundraisers'),
      self::action("Donations", 'donations', 'advanced'), // Marked advanced for now
      self::action("Gifts", 'gifts'),
      //TODO: self::action("Stories", 'stories'),
      self::action("Settings", 'settings', '/partners'),
      self::action("Design", 'design', '/partners'),
      self::action("About", 'about', '/partners')
    );
  }

  public static function getColumns($req) {
    return array(
      'group:partner' => array(
        'name' => TRUE,
        'domain' => TRUE,
        /* 'url' => 'url',
        'thumb' => 'image', */
        'blog_id' => 'id'
      ),
      'description' => 'text',
      'location' => 'location',
      'terms' => TRUE,
      'live' => 'bool',
      'private' => 'bool'
    );
  }

  public static function getForms($req) {
    $settings = self::menu('Settings',
      self::field('Name', 'name', array(
        'width' => 500,
        'required' => TRUE,
        'placeholder' => 'name of the organization'
      )),
      self::field('Site name', 'domain', array(
        'after' => '.' . $_SERVER["HTTP_HOST"],
        'lowercase' => TRUE,
        'placeholder' => 'subdomain name'
      )),
      self::field('Location', 'location', array(
        'placeholder' => 'city/state/country'
      )),
      self::field('Description', 'description', 'text', array(
        'placeholder' => 'what does this organization do?'
      )),
      self::field('Search terms', 'terms', array(
        'placeholder' => 'extra terms to help searches'
      )),
      self::field('Private', 'private', 'bool')
    );

    return array(
      'settings' => $settings
    );
  }

  // Upgrade a partner's gifts:
  //  - mark all the whole gifts as variable
  //  - migrate all partial gifts to whole gifts
  //  - mark the partial gifts as inactive
  // NOT TO BE DONE LIGHTLY!  This will kick a site out of the standard gift browser.
  // I'm just recording the script here for now since we use it for testing
  public static function upgradeGifts($partner_id) {
    /* Migrate partials that are variable to their parent if also variable
        update 
          gift g1
          left join gift g2 on g1.towards_gift_id=g2.id
          left join donationGifts dg on dg.giftID=g1.id
        set
          dg.giftID = g2.id, dg.towards_gift_id = 0
        where g1.varAmount = 1 and g2.varAmount = 1 and g1.unitAmount = 1 and dg.towards_gift_id=g2.id and g.blog_id=%d
    */

    /* Migrate all partials (variable or not) to their parent if variable
      update 
        gift g
        left join donationGifts dg on dg.towards_gift_id=g.id
      set
        dg.giftID = g.id, dg.towards_gift_id = 0
      where g.varAmount = 1 and g.blog_id=%d
    */
  }

}

register_api(__FILE__, 'PartnerApi');
