<?php

require_once(__DIR__ . '/api.php');
require_once(ABSPATH . '/wp-content/mu-plugins/images.php');

class DonorApi extends Api {

  // GET
  //    donor: ID of a specific donor
  //    search: terms to search
  public static function get($req) {
    $record = req($req, array('id:donor', 'search', 'email', '#user_id','#fr_id','#blog_id','story'));

    $query = new ApiQuery("donor.ID as id,
      donor.firstName as first, donor.lastName as last, donor.email as email,
      donor.address, donor.address2, donor.city, donor.state, donor.zip,
      donor.user_id, 
      u.user_login, u.display_name, um.meta_value as user_fb_id,
      donor.data, donor.fullcontact", 
      "donationGiver donor
      LEFT JOIN wp_users u on u.id = donor.user_id
      LEFT JOIN wp_usermeta um on um.user_id = u.id AND um.meta_key='fb_id'");

    if (isset($record->id)) {
      $query->where_expr("donor.ID", $record->id);
      $query->field("1 as user_image");
    }

    if (isset($record->story)) {
      $ids = explode('/', $record->story);
      if (count($ids) != 2)
        throw new Exception("invalid story ID");

      $query->table("left join a_transaction t on t.donor_id = donor.id");
      $query->table("left join a_gift g on g.trans_id = t.id");
      $query->fields("MIN(t.date) as date, SUM(t.amount) as amount, MAX(t.fr_id) as fr_id");
      $query->fields("g.blog_id, g.story");
      $query->order("t.date DESC");
      $query->group("donor.ID");
      $query->where("g.blog_id=%d AND g.story=%d", $ids[0], $ids[1]);
    } else if (isset($record->blog_id)) { // not compat with story
      $query->table("left join a_transaction t on t.donor_id = donor.id");
      $query->fields("t.date as date, t.amount as amount, t.fr_id as fr_id");
      $query->table("left join a_gift g on g.trans_id = t.id");
      $query->order("t.date DESC");
      $query->group("donor.ID");
      $query->where("g.blog_id = %d", $record->blog_id);
    } else if (isset($record->fr_id)) { // not compat with story
      $query->table("left join a_transaction t on t.donor_id = donor.id");
      $query->fields("t.date as date, t.amount as amount, t.fr_id as fr_id");
      $query->where("t.fr_id = %d", $record->fr_id);
      $query->order("t.date DESC");
    } else 
      $query->group("donor.ID"); 

    if (isset($record->email))
      $query->where_expr("donor.email", $record->email);
    if (isset($record->user_id))
      $query->where_expr("donor.user_id", $record->user_id);

    if (!empty($record->search)) {
      $like = "%$record->search%";

      // TODO: better search
      $query->where(
        "((donor.firstName like %s) or (donor.lastName like %s) or (donor.email like %s))",
        $like, $like, $like);
    }

    $query->require_wheres();
    $query->order("donor.ID ASC");
    return $query->map_results(array(__CLASS__, 'format_row'));
  }


  // Join donor fields, using $field as the join variable and $on as blog_id
  public static function join(ApiQuery &$query, $on, $field = "donor", $group = "donor") {
    $query->table("LEFT JOIN donationGiver $field on $field.id = $on");

    $query->fields("
      $field.id as {$group}_id,
      $field.firstName as {$group}_first,
      $field.lastName as {$group}_last,
      $field.email as {$group}_email,
      $field.user_id as {$group}_user,
      $field.address,
      $field.address2,
      $field.city,
      $field.state,
      $field.zip");

    return $field;
  }


  public static function update($req) {
    // Map the request to a mysql record
    $record = req($req, array('ID:donor_id', 'firstName:first','lastName:last','email','address','address2','city','state','zip','phone','user_id'));
    $record->data = req($req, array('user_image'));

    return static::insert_or_update($record);
  }

  protected static function insert_or_update(&$record) {
    global $wpdb;

    static::validate_record($record);

    // Perform the mysql update or insert
    if ($record->ID) {
      $donor_id = $record->ID;
      $wpdb->update('donationGiver', (array)$record, array( 'ID' => $donor_id ));
    } else {
      $record->main = TRUE;
      $wpdb->insert('donationGiver', (array)$record);
      $donor_id = $wpdb->insert_id;
    }

    // Return the updated/inserted record
    return static::getOne($donor_id);
  }

  public static function validate_record(&$record) {
    // Donor ID must be valid
    if ($record->ID && ($record->ID < 0))
      throw new Exception("Invalid ID");

    if (empty($record->email))
      throw new InvalidArgumentException("email");

    if (!$record->ID)
      $record->ID = get_donor_id($record->email);

    if ($record->data)
      $record->data = json_encode($record->data);

    // TODO: ensure that user_id is valid
    // TODO: check various data fields are valid
  }

  public static function getColumns() {
    return array(
      'group:donor' => array(
        'first' => 'title',
        'last' => TRUE,
        'email' => 'email',
        'id' => 'id'
      ),
      'group:user' => array(
        'user_login' => TRUE,
        'user_id' => 'id',
        'user_fb_id' => 'id'
      ),
      'group:address' => array(
        'address' => TRUE,
        'address2' => TRUE,
        'city' => TRUE,
        'state' => TRUE,
        'zip' => TRUE
      )
    );
  }

  public static function getActions($req) {
    return self::menu('Donor',
      self::action('Donations', "donations"),
      self::action('Activity', "activity")
    );
  }

  public static function format_row($row) {
    static::unpack_data($row, 'data');
    static::unpack_data($row, 'fullcontact');

    if (isset($row->fullcontact['organizations'])) {
      $orgs = $row->fullcontact['organizations'];
      if (count($orgs) > 0)
        $row->occupation = $orgs[0]['title'] . ' ' . $orgs[0]['name'];
    }

    // Protected addresses
    protect_email($row->email);

    // Handle image manipulation here?
    if ($row->user_image == TRUE) {
      $row->user_image = user_image_src($row->user_id, 200,200);
    } else if ($row->user_image) {
      $row->user_image = image_src($row->user_image, 200,200);
    }

    return $row;
  }
}

// Register this API
register_api(__FILE__, 'DonorApi');
