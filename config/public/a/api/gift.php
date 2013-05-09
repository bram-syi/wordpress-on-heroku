<?php

require_once(__DIR__ . '/api.php');
require_once(__DIR__.'/promo.php');
require_once(ABSPATH . '/wp-content/mu-plugins/images.php');

class GiftApi extends Api {
  public static $GIVEANY = 50;
  public static $TOWARD_PREFIX = 'a donation toward ';
  public static $DEFAULT_PRICES = "10,20,50,100,250,500";

  public static function get($req) {
    $record = req($req, array('id:gift:gift_id', 'site:partner:charity', 'blog_id:partner_id', '#variable', '#active','#available','#all', 'whole_id','whole_of','#whole', 'tags:tag'));
    $params = req($req, array('search','view','order'));

    $query = new ApiQuery(
      "g.id as gift_id, g.displayName as gift_name, g.title, g.image,
      IF(IFNULL(g.towards_gift_id,0)=0, g.id, g.towards_gift_id) as full_id,
      g.towards_gift_id as whole_id, tg.displayName as whole_name,
      g.excerpt, g.post_id,
      g.unitAmount as price, g.prices as prices, g.varAmount as variable, g.unitsWanted,
      g.active,
      g.tags",
      "gift g
      LEFT JOIN gift tg ON g.towards_gift_id = tg.id
    ");

    $partner = PartnerApi::join($query, "g.blog_id");

    if (!empty($record->id))
      $query->where('g.id = %d', $record->id);

    global $blog_id;
    if (!empty($record->site))
      $query->where("{$partner}.domain = %s", $record->site);
    else if (!empty($record->blog_id))
      $query->where('g.blog_id = %d', $record->blog_id);
    else if($blog_id > 1)
      $query->where('g.blog_id = %d', $blog_id);

    if (isset($record->variable))
      $query->where("g.varAmount = %d", $record->variable);

    if (isset($record->available))
      $query->where("g.unitsWanted > 0 AND g.active = 1", $record->variable);

    if (isset($record->whole))
      $query->where("g.towards_gift_id = 0");
    if (isset($record->whole_id))
      $query->where("g.id = %d OR g.towards_gift_id = %d", $record->whole_id, $record->whole_id);

    if (isset($record->whole_of)) {
      $query->table("LEFT JOIN gift g2 ON g2.towards_gift_id=g.id AND g2.id=%d", $record->whole_of);
      $query->where("g2.id IS NOT NULL OR (g.id=%d AND g.towards_gift_id=0)", $record->whole_of);
    }

    if (isset($record->tags)) {
      // Support #domain style tag to match the domain of owning partner
      if (substr($record->tags, 0, 1) == '#') {
        $query->where("{$partner}.domain = %s", substr($record->tags,1));
      } else {
        $query->where(build_tag_query('g.tags', $record->tags));
      }
    }

    if (isset($record->active)) {
      if ($record->active)
        $query->where("g.active = 1 AND g.towards_gift_id = 0");
      else
        $query->where("g.active = 0 OR g.towards_gift_id > 0");
    }

    if (!empty($params->search))
      $query->where("g.displayName like %s", "%{$params->search}%");

    switch ($params->order) {
      case 'name':
        $query->order("g.active DESC, g.displayName ASC, partner_domain, g.id");
        break;
      case 'price':
      case 'cost':
        $query->order("g.active DESC, g.unitAmount ASC, g.displayName ASC");
        break;
      default:
        $query->order("partner_domain, g.active DESC, g.id");
    }

    $results = $query->map_results(array(__CLASS__, 'format_row'));
    if ($params->view == 'gallery')
      $results = $query->map_results(array(__CLASS__, 'add_gallery'), $results);

    return $results;
  }

  public static function add_gallery($record) {
    $record->gallery = array(
      'gift_image' => PromoApi::load("gift-images/{$record->gift_id}"),
      'gift_description' => PromoApi::load("{$record->partner_id}/{$record->post_id}")
    );

    return $record;
  }

  public static function format_row($row) {
    if (!static::hasPermission("/partner/$row->partner_domain"))
      return null;

    $row->partner = $row->partner_domain;

    if (empty($row->image))
      $row->image = '/wp-content/gift-images/default.jpg';
    $row->image = image_src(versioned_url($row->image), image_geometry(320,240));
    $row->available = $row->active && ($row->unitsWanted > 0);

    // TODO: allow customization of prices
    if (empty($row->prices))
      $row->prices = static::$DEFAULT_PRICES;
    else if ($row->full_id == 958)
      $row->prices = "10,25,50,125,150,200";
    else if ($row->price == 250) 
      $row->prices = "10,20,50,100,200,250";
    $row->prices = as_ints($row->prices);

    return $row;
  }

  public static function getGiveAny($gift_id) {
    if ($gift_id == 0 || $gift_id == GiftApi::$GIVEANY)
      return NULL;

    // Find the whole gift that this gift contributes towards
    // (which could be this gift itself)
    $toward = static::getOne(array(
      'whole_of' => $gift_id
    ));
    if ($toward == NULL)
      throw new Exception('Internal error');

    if ($toward->variable)
      return $toward;

    // Is there a variable gift that contributes to the whole gift?
    $gift = static::getOne(array(
      'variable' => TRUE,
      'whole_id' => $toward->gift_id
    ));

    // if not, create one
    if ($gift == NULL) {
      $gift = static::create(array(
        'price' => 1,
        'variable' => TRUE,
        'whole_id' => $toward->gift_id,
        'blog_id' => $toward->blog_id,
        'displayName' => GiftApi::TOWARD_PREFIX . $toward->displayName
      ));
    }

    return $gift;
  }

  public static function update($req) {
    // Map the request to a mysql record
    $record = req($req, array('id:gift_id', 'blog_id:partner_id', 'partner:site:partner_domain', 'unitAmount:price', 'towards_gift_id:whole_id', '#active', '#varAmount:variable', 'unitsWanted', 'displayName:gift_name', 'title', 'excerpt', 'tags:tag', 'gallery', 'prices'));
    if (isset($record->partner)) {
      $record->blog_id = get_site_id($record->partner);
      unset($record->partner);
    }

    return static::insert_or_update($record);
  }

  protected function insert_or_update(&$record) {
    global $wpdb;

    static::validate_record($record);

    // Save any promos that were associated with this record
    static::save_gallery($record);

    if (empty($record->tags)) {
      $record->tags = $wpdb->get_var($wpdb->prepare(
        "SELECT domain FROM charity WHERE blog_id=%d", $record->blog_id));
    }

    if ($record->prices == static::$DEFAULT_PRICES)
      $record->prices = "";
    else if (is_array($record->prices))
      $record->prices = implode(",", $record->prices);

    // Perform the mysql update or insert
    if ($record->id) {
      $gift_id = $record->id;
      $wpdb->update('gift', (array)$record, array( 'id' => $gift_id ));
    } else {

      // Default title
      if (empty($record->title))
        $record->title = "Give {$record->displayName}";

      // Default to variable amount
      if (!isset($record->varAmount))
        $record->varAmount = TRUE;

      $slug = sanitize_title_with_dashes($record->displayName);
      $record->post_id = db_new_page($record->blog_id, 1/*admin*/, $record->title, "", $slug,0,0,'gift', $record->excerpt);
      $record->active = TRUE;
      $record->unitsWanted = 10;

      // Insert the new gift
      $wpdb->insert('gift', (array)$record);
      $gift_id = $wpdb->insert_id;
    }

    // Return the updated/inserted record
    return static::getOne($gift_id);
  }

  public static function validate_record(&$record) {
    // ID must be valid
    if ($record->id && ($record->id < 0))
      throw new Exception("Invalid ID");

    // TODO: MORE validation!
    if (empty($record->displayName))
      throw new InvalidArgumentException("name");

    if (empty($record->excerpt)) // ? Should this be required?
      $record->excerpt = "";

    if ($record->unitAmount <= 0)
      throw new InvalidArgumentException("price");

    if ($record->towards_gift_id < 0 || ($record->towards_gift_id > 0 && $record->towards_gift_id == $record->id))
      throw new InvalidArgumentException("towards_gift_id");
  }

  public static function getActions($req) {
    return self::menu('Gift',
      self::action('Settings', "settings"),
      self::action('Design', "design")
    );
  }

  public static function getForms($req) {
    $settings = self::menu('Settings',
      self::field('Gift name', 'gift_name', array(
        'placeholder' => 'ex. tuition for a student'
      )),
      self::menu('Full cost',
        self::field('Price', 'price', 'money', array(
          'placeholder' => 'Price of full gift'
        )),
        self::field('Can donate partial amounts', 'variable', 'check')
      ),
      self::permitted('advanced',
        self::field('Gift levels', 'prices', array(
          'placeholder' => 'default ' . static::$DEFAULT_PRICES
        ))
      ),
      self::menu('Units wanted',
        self::field('Units wanted', 'unitsWanted', 'number', array(
          'placeholder' => 'How many can be sold?'
        )),
        self::field('Accepting donations', 'active', 'check')
      ),
      self::field('Excerpt', 'excerpt', 'text', array(
        'placeholder' => '(short description of the gift)'
      )),
      self::field('Tags', 'tags', 'tags')
    );

    return array(
      'settings' => $settings
    );
  }

  public static function getColumns($req) {
    $params = req($req, array('#all'));

    $cols = array(
      'group:gift' => array(
        'gift_name' => 'title',
        'gift_id' => 'id'
      ),
      'title' => 'title',
      'price' => 'money',
      'variable' => 'bool',
      'unitsWanted' => 'int',
      'group:partner' => array(
        'partner_name' => TRUE,
        'partner_domain' => 'partner',
        'partner_id' => 'id'
      ),
      'tags' => 'tags',
      'active' => 'bool',
      'available' => 'bool'
    );

    if (isset($params->all)) {
      $cols['group:whole'] = array(
        'whole_name' => 'title',
        'whole_id' => 'id'
      );
    }

    return $cols;
  }
}

register_api(__FILE__, 'GiftApi');
