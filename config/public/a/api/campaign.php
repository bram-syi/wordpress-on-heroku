<?php

require_once(__DIR__.'/api.php');
require_once(__DIR__.'/partner.php');
require_once(__DIR__.'/promo.php');
require_once(__DIR__.'/fundraiser.php');
require_once(ABSPATH.'wp-content/mu-plugins/teams.php');

class CampaignApi extends Api {

  public static function get($req) {
    $record = req($req, array('campaign','name:id', 'search', 'partner_id:blog_id', '#fr_id', '#for_fr', 'page_id', 'partner'));
    $params = req($req, array('view'));

    $query = new ApiQuery(
      "campaign.name as id, campaign.*",
      "theme_data campaign");
    $partner = PartnerApi::join($query, "campaign.blog_id");

    // SELECT fundraiser totals
    $query->table("LEFT JOIN campaigns c on c.theme=campaign.name");
    $query->fields("
      SUM(c.raised) as raised,
      SUM(c.tip) as tip,
      SUM(c.donors_count) as donors,
      SUM(IF(c.archived = 0, 1, 0)) as active,
      SUM(IF(c.archived = 1, 1, 0)) as archived");

    if (!empty($record->campaign))
      $query->where("campaign.name = %s", $record->campaign);
    if (!empty($record->name))
      $query->where("campaign.name = %s", $record->name);
    if (!empty($record->partner_id))
      $query->where_expr("campaign.blog_id", $record->partner_id);
    if ($record->page_id > 0)
      $query->where("campaign.page_id = %d", $record->page_id);
    if ($record->fr_id > 0)
      $query->where("campaign.fr_id = %d", $record->fr_id);
    if ($record->for_fr > 0) {
      $query->table("LEFT JOIN campaigns fr ON fr.theme = campaign.name");
      $query->where("fr.post_id = %d", $record->for_fr);
    }
    if (!empty($record->partner))
      $query->where_expr("{$partner}.domain", $record->partner);

    if (!empty($record->search)) {
      $like = "%$record->search%";

      // TODO: better search
      $query->where(
        "(campaign.name like %s) or (c.post_title like %s)",
        $like, $like);
    }

    $query->order("campaign.name ASC");
    $query->group("campaign.name");

    return static::expand($query, $params);
  }

  // Join user fields, using $field as the join variable and $on as blog_id
  public static function join(ApiQuery &$query, $on, $field="campaign", $group = 'campaign') {
    $query->table("LEFT JOIN theme_data {$field} on {$field}.name=$on");
    $query->fields("
      {$field}.name as {$group}_id,
      {$field}.name as {$group}_name,
      {$field}.page_id as {$group}_page_id,
      NULL as {$group}_title");
      // $field.title as {$group}_title"); <-- not currently unpacked from data 

    return $field;
  }

  // Format a campaign result set - this can be called from external classes
  // that provide a query that returns (at least) a proper set of columns
  public static function expand($query, $params = NULL) {
    $results = $query->map_results(array(__CLASS__, 'format_row'));

    if ($params->view == 'gallery')
      $results = $query->map_results(array(__CLASS__, 'add_gallery'), $results);
    else if ($params->view == 'migrate')
      $results = $query->map_results(array(__CLASS__, 'migrate'), $results);

    return $results;
  }

  public static function format_row($row) {
    if (!static::hasPermission("/partner/$row->partner_domain"))
      return null;

    $json = json_decode($row->contents);
    unset($row->contents);

    $row->image = "http://res.cloudinary.com/seeyourimpact/image/fetch/w_200,h_200,c_fill,g_faces/http://dev1.seeyourimpact.com/wp-content/images/no-photo.jpg";

    $row = (object)array_merge((array)$row, (array)$json);

    // Hide certain campaigns from admin console
    $opts = req($row, array('#hidden'));
    if (($opts->hidden == TRUE) && !static::hasPermission("advanced"))
      return null;

    // These are copies to support tools that need them to be named this way
    if (isset($row->partner_domain) && !isset($row->org))
      $row->org = $row->partner_domain;
    if (isset($row->partner_domain) && !isset($row->partner))
      $row->partner = $row->partner_domain;

    if (isset($row->url) && !isset($row->campaign_url))
      $row->campaign_url = $row->url;

    if (!isset($row->title) && isset($row->post_title))
      $row->title = $row->post_title;

    $row->can_join = as_bool($row->can_join, $row->theme != 'give-big');

    // Translate old checkbox values
    if (!isset($row->show_last_names) && isset($row->show_admin_last_names)) {
      $row->show_last_names = $row->show_admin_last_names;
      unset($row->show_admin_last_names);
    }

    

    return $row;
  }

  // CREATE/UPDATE
  public static function update($req) {
    $record = req($req, array('name:id:campaign', 'org:partner', 'blog_id:partner_id','page_id','fr_id', '#is_home_page', 'title','tag:tags', '#goal', '#donor_goal', '#show_last_names:show_admins_last_names', '#downplay_money', 'post_title', 'post_content', 'required_fields', 'fields', 'fundraisers','campaign_page', 'gallery', 'legacy','gifts','show_private','can_join', 'og_image','campaign_header', 'team_sort','goal_themes', 'start_date', 'h20', 'contact', 'archived', 'hidden' ));

    static::handle_upgrades($record);

    return static::insert_or_update($record);
  }

  protected static function insert_or_update($record) {
    global $wpdb;

    static::validate($record);

    // Save any promos that were associated with this record
    static::save_gallery($record);
    
    // Replacing a current campaign?
    $current = $wpdb->get_row($wpdb->prepare(
      "SELECT * FROM theme_data WHERE name=%s",
      $record->name));

    if ($current === NULL) {
      // New campaign - create it.
      static::create_campaign("{$record->org}/{$record->name}", $record);
      $data = static::getDefaultCampaignSettings();

      // Create the general holding fundraiser for thie campaign - this allows giving directly to the
      // campaign rather than a particular champion's fundraiser.
      $fr = FundraiserApi::create(array(
        'owner' => 0,
        'title' => 'Campaign donations', // TODO: should it have same title as campaign?
        'slug' => $record->name,
        'theme' => $record->name,
        'public' => FALSE,
        'team' => '',
        'tags' => ''
      ));
      $record->fr_id = $fr->id;

      // BUG: "appeal" property doesn't exist
      $data->campaign_page->appeal->title = $record->title;
    } else {
      if (empty($record->blog_id))
        $record->blog_id = $current->blog_id;
      if (empty($record->page_id))
        $record->page_id = $current->page_id;
      if (empty($record->fr_id))
        $record->fr_id = $current->fr_id;
      if ($record->blog_id == 0)
        $record->blog_id = get_site_id($record->org);
      $data = json_decode($current->contents);
    }

    // Create the extra data storage
    $data = (object)array_merge((array)$data, (array)$record);

    // Remove the real columns - don't write name, blog_id, etc. into the data
    unset($data->name);
    unset($data->blog_id);
    unset($data->org);
    unset($data->page_id);
    unset($data->fr_id);
    unset($data->legacy);

    // Handle setting this as the front page
    if (isset($data->is_home_page)) {

      $data->url = static::set_home_page($record->blog_id, $record->page_id, $data->is_home_page);
      if (!$data->is_home_page)
        unset($data->is_home_page); // Just clear it out rather than storing false
    }

    $wpdb->update('theme_data', array(
      'blog_id' => $record->blog_id,
      'page_id' => $record->page_id,
      'fr_id' => $record->fr_id,
      'contents' => indent_json(json_encode($data, JSON_NUMERIC_CHECK))
    ), array('name' => $record->name ));

    update_campaign_stats($record->fr_id);

    return static::getOne($record->name);
  }

  public static function validate(&$record) {
    if (empty($record->title))
      throw new Exception("title");

    if (empty($record->name))
      $record->name = sanitize_title_with_dashes($record->title);
    $record->name = strtolower($record->name);
    $record->org = strtolower($record->org);
    
    // Name must be alphanumeric
    if (!preg_match('/^[a-zA-Z0-9_\-]+$/', $record->name))
      throw new Exception("Invalid campaign ID");

    if (!empty($record->org)) {
      $partner = PartnerApi::getOne(array( 'domain' => $record->org ));
      if ($partner == NULL)
        throw new Exception("Unknown partner");

      $record->blog_id = $partner->blog_id;
    }
  }

  public static function create_campaign($campaign, &$record) {
    global $wpdb;

    Team::prep($campaign);

    if (is_null($campaign->blog_id)) {
      trace_up("invalid campaign: $campaign->org/$campaign->name");
      return 0;
    }

    Team::check_slug($campaign->name, $record->title);

    if (!isset($record->tag) && isset($record->org))
      $record->tag = $campaign->org;

    // Create a new record in the campaign table
    $wpdb->insert('theme_data', array(
      'name' => $campaign->name,
      'blog_id' => $campaign->blog_id,
      'contents' => indent_json(json_encode($record), JSON_NUMERIC_CHECK)
    ));

    $desc = "$campaign->org/$campaign->name";

    // create a WP Page for the campaign with an empty body
    $record->page_id = db_new_page(
      $campaign->blog_id,
      1,
      $record->name,
      '',
      $campaign->name
    );

    // set postmeta
    switch_to_blog($campaign->blog_id);

    update_post_meta($record->page_id, 'campaign', $desc);
    update_post_meta($record->page_id, '_wp_page_template', 'chapter-page.php');
    if (isset($record->goal))
      update_post_meta($record->page_id, 'goal', $record->goal);

    restore_current_blog();

    $record->url = static::set_home_page($campaign->blog_id, $record->page_id, $record->is_home_page);

    // TODO: remove legacy
    if ($record->legacy) {
      // setup theme promos (sidebar, header, etc)
      db_new_page(
        $campaign->blog_id,
        1,
        "{$record->name} sidebar",
        '<div>build your sidebar</div>',
        "sidebar-{$campaign->name}",
        0,
        0,
        'promo'
      );

      db_new_page(
        $campaign->blog_id,
        1,
        "{$record->name} header",
        '<div>build your header</div>',
        "header-{$campaign->name}",
        0,
        0,
        'promo'
      );
    }

    return array($record->page_id, $record->url);
  }

  public function getDefaultCampaignSettings() {
    // TODO: Move these into a DB, maybe there are site-specific or site-wide settings?
    return array(
      'can_join' => TRUE,  // Anyone can join this fundraiser
      'fundraisers' => array(
        'show' => array(
          'banner' => TRUE,
          'progress' => TRUE,
          'give' => TRUE,
          'thankyou' => TRUE,
          'stories' => TRUE,
          'about' => TRUE,
          'comments' => TRUE
        ),
        'edit' => array(
          'title' => FALSE,
          'appeal' => TRUE,
          'goal' => FALSE
        )
      ),
      'campaign_page' => array(
        'show' => array(
          'banner' => TRUE,
          'header' => FALSE,
          'progress' => TRUE,
          'give' => TRUE,
          'gifts' => FALSE,
          'champions' => TRUE,
          'thankyou' => TRUE,
          'stories' => TRUE,
          'about' => TRUE,
          'comments' => TRUE
        )
      ),
      'required_fields' => array(
        'post_title' => FALSE,
        'post_content' => TRUE,
        'goal' => FALSE,
        'team' => FALSE
      )
    );
  }

  public static function getColumns($req) {
    return array(
      'id' => 'id',
      'group:campaign' => array(
        'title' => 'text',
        'name' => 'id'
      ),
      'group:partner' => array(
        'partner_name' => TRUE,
        'partner_domain' => TRUE,
        'partner_id' => 'id'
      ),
      'active' => 'count',
      'archived' => 'count',
      'tags' => 'tags',
      'goal' => 'money',
      'raised' => 'money',
      'tip' => 'money',
      'donors' => 'count',
      'is_home_page' => 'bool'
    );
  }

  public static function getActions($req) {
    return self::menu('Campaign',
      // self::action('Dashboard', "dashboard"),
      // TODO: move to a chart on the dashboard
      // self::action("progress&from=2012-01-01", {title: 'Progress'}),
      self::action('Fundraisers', "fundraisers"),
      self::action('Teams', "teams"),
      self::action('Donations', "donations"),
      self::action('Settings', "settings"),
      self::action('Signup Page', "signup"),
      self::permitted('advanced',
        self::action("Design: Campaign", "design")
      ),
      self::permitted('advanced',
        self::action("Design: Fundraiser", "design/fundraiser")
      ),
      self::permitted('advanced',
        self::action('Messages', "messages")
      )
    );
  }

  public static function getForms($req) {
    $settings = self::menu('Settings',
      self::menu('Name',
        self::field('Campaign Title', 'title', 'title',  array(
          'width' => 500,
          'required' => TRUE
        )),
        self::permitted('advanced',
          self::field('Campaign ID', 'name', array(
            'before' => ' page name: ',
            'lowercase' => TRUE
          ))
        )
      ),
      self::permitted('/partners',
        self::menu('Partner',
          self::field('Partner', 'partner', 'partner', array(
            'readOnly' => TRUE
          )),
          self::field('Set as home page', 'is_home_page', 'check')
        )
      ),
      self::menu('Duration',
        self::field('Start date', 'start_date', 'date'),
        self::field('End date', 'end_date', 'date', array(
          'before' => ' to '
        ))
      ),
      self::menu('Goals',
        self::field('Goal', 'goal', 'money'),
        self::field('Donor goal', 'donor_goal', 'number', array(
          'before' => ' or ',
          'after' => ' donors'
        ))
      ),
      self::field('Gift tag', 'tag', 'tags'),
      self::menu('Contact',
        self::field('Name', 'contact.name', array(
          'placeholder' => 'Contact name'
        )),
        self::field('Name', 'contact.email', 'email', array(
          'placeholder' => 'Contact e-mail'
        ))
      ),
      self::menu('Campaign Options',
        self::field('Show donor last names to admins', 'show_last_names', 'check'),
        self::field('Hide $ amounts from visitors', 'downplay_money', 'check'),
        self::permitted('advanced',
          self::field('Campaign has ended', 'archived', 'check')
        ),
        self::permitted('advanced',
          self::field('Hide campaign from partners', 'hidden', 'check')
        )
      )
    );

    $signup = self::menu('Signup Page',
      self::menu('Signup',
        self::field('Type', 'fundraisers.type', array(
          before => 'Start or edit your ',
          placeholder => 'fundraiser'
        )),
        self::field('Campaign is accepting signups', 'can_join', 'check')
      ),
      self::menu('Title',
        self::field('Title', 'post_title', 'title'),
        self::field('Champ can set title', 'required_fields.post_title', 'check')
      ),
      self::menu('Message',
        self::field('Message', 'post_content', 'text'),
        self::field('Champ can change message', 'required_fields.post_content', 'check')
      ),
      self::menu('Goal',
        self::field('Default goal', 'fundraisers.goal', 'money'),
        self::field('Champ can choose own goal', 'required_fields.post_goal', 'check')
      ),
      self::menu('Teams',
        self::field('Allow teams', 'required_fields.post_team', 'check'),
        self::field('Allow write-in "other" teams', 'required_fields.allow_writein_teams', 'check')
      )
/*
        // TODO: server-side merge of customizations
        $record->name == 'readathon' ? self::field('Readathon coordinator', 'required_fields.coordinator', 'check') : NULL
*/
    );

    $messages = self::menu('Messages',
      self::field('Default invitation text', 'h20.default_invite_message', 'text'),
      self::field('Facebook donation message', 'facebook.default_donation_message', 'text'),
      self::field('OpenGraph thumbnail', 'og_image')
    );

    return array(
      'settings' => $settings,
      'signup' => $signup,
      'messages' => $messages
    );
  }

  // Used to update the front page of a particular site to a campaign page
  // (or remove one from the front page)
  protected static function set_home_page($blog_id, $page_id, $is_home_page = TRUE) {
    if ($blog_id == 1) {
      if ($page_id == 0)
        return NULL;
    } else {
      $old_front_id = get_blog_option($blog_id, 'page_on_front');

      if ($is_home_page) {
        // Replacing an old campaign?  Remove that campaign from the front page first
        if ($old_front_id > 0 && ($old_front_id != $page_id)) {
          switch_to_blog($blog_id);
          $cid = get_post_meta($old_front_id, 'campaign', TRUE);
          restore_current_blog();

          // TODO: error check? This could happen if previous front page was not a campaign
          if ($cid != NULL) {
            $cid = explode('/', $cid);
            CampaignApi::update(array(
              'name' => $cid[1],
              'is_home_page' => FALSE
            ));
          } 
        }

        update_blog_option($blog_id, 'page_on_front', $page_id);
      } else if ($old_front_id == $page_id) {
        update_blog_option($blog_id, 'page_on_front', 0);
      }
    }

    return get_blog_permalink($blog_id, $page_id);
  }

  public static function migrate($record) {
    global $wpdb;

    if (isset($record->organization) && empty($record->blog_id)) {
      $record->blog_id = get_site_id($record->organization);
    } 

    if ($record->blog_id <= 1) {
      // Infer from gift tags
      $bids = explode(',', $wpdb->get_var($wpdb->prepare(
        "SELECT GROUP_CONCAT(DISTINCT blog_id) FROM gift WHERE tags LIKE %s",
        "%{$record->tag}%")));
      if (count($bids) == 1)
        $record->blog_id = $bids[0];
    }

    if ($record->blog_id == 0) {
      $record->blog_id = 1;
      $record->partner_id = 1;
    }

    if ($record->blog_id > 1 && empty($record->partner_id)) {
      $partner = PartnerApi::getOne($record->blog_id);
      $record->partner_id = $partner->blog_id;
      $record->partner_domain = $partner->domain;
      $record->partner_name = $partner->name;
    }

    if (isset($record->blog_id) && empty($record->page_id)) {
      if ($record->blog_id == 1)
        $record->page_id = 0;
      else {
        $record->page_id = $wpdb->get_var($wpdb->prepare(
          "select p.id from wp_{$record->blog_id}_postmeta pm
          left join wp_{$record->blog_id}_posts p on p.id=pm.post_id
          where pm.meta_value=%s
          and p.post_status='publish'",
          "{$record->partner_domain}/{$record->name}"));

        if (empty($record->page_id))
          $record->page_id = $wpdb->get_var($wpdb->prepare(
            "SELECT ID FROM wp_{$record->blog_id}_posts
            WHERE post_name='%s'",
            $record->name));
      }
    }

    if (isset($record->blog_id) && $record->page_id > 0) {
      $front_id = get_blog_option($record->blog_id, 'page_on_front');
      $record->is_home_page = ($record->page_id === $front_id);
    } 

    if (!isset($record->signup) && isset($record->required_fields)) {
      $record->signup = (object)array('show' => new stdClass);
      $record->signup->show->title = $record->required_fields->post_title == 'true';
      $record->signup->show->appeal = $record->required_fields->post_content == 'true';
      $record->signup->show->goal = $record->required_fields->goal == 'true';
      $record->signup->show->team = $record->required_fields->team == 'true';
    }

    if (!isset($record->fundraisers))
      $record->fundraisers = new stdClass;

    if (!isset($record->fundraisers->edit)) {
      $record->fundraisers->edit = new stdClass;
      if (isset($record->fields)) {
        $record->fundraisers->edit->title = $record->fields->post_title == 'true';
        $record->fundraisers->edit->appeal = $record->fields->post_content == 'true';
        $record->fundraisers->edit->goal = $record->fields->goal == 'true';
        $record->fundraisers->edit->photo = $record->fields->photo == 'true';
        $record->fundraisers->edit->team = $record->fields->team == 'true';
      } else {
        $record->fundraisers->edit->title = true;
        $record->fundraisers->edit->appeal = true;
        $record->fundraisers->edit->goal = true;
        $record->fundraisers->edit->photo = true;
        $record->fundraisers->edit->team = true;
      }
    }

    if (!isset($record->fundraisers->show)) {
      $record->fundraisers->show = new stdClass;
      $record->fundraisers->show->progress = true;
      $record->fundraisers->show->gifts = true;
      $record->fundraisers->show->stories = true;
      $record->fundraisers->show->about = true;
      $record->fundraisers->show->comments = true;
      $record->fundraisers->show->note = true;
      $record->fundraisers->show->thankyou = true;
    }

    if (!isset($record->campaign_page))
      $record->campaign_page = new stdClass;

    if (!isset($record->campaign_page->show)) {
      $record->campaign_page->show = new stdClass;
      $record->campaign_page->show->banner = false;
      $record->campaign_page->show->header = true;
      $record->campaign_page->show->progress = true;
      $record->campaign_page->show->champions = true;
      $record->campaign_page->show->leaderboard = !$record->teams_only;
      $record->campaign_page->show->give = true;
      $record->campaign_page->show->progress = true;
      $record->campaign_page->show->gifts = ($record->name == 'givingtuesday' || $record->name='newleaders-staff' || $record->name == 'www' || $record->name == 'iraqi-children');
      $record->campaign_page->show->stories = true;
      $record->campaign_page->show->about = true;
      $record->campaign_page->show->comments = true;
      $record->campaign_page->show->note = true;
      $record->campaign_page->show->thankyou = true;
    }

    $record = static::add_gallery($record);
    foreach ($record->gallery as $k=>$v) {
      $e = explode('/', $v->ref);
      $e[0] = $record->blog_id;
      $record->gallery[$k]->ref = implode('/',$e);
    }
    
    if ($record->start_banner == '<div style="width:690px; height: 120px; background: url(http://dev1.seeyourimpact.com/themes/placeholder_690x120.png) no-repeat 0 0; margin: -15px -35px 20px;"></div>')
      $record->start_banner = NULL;
    if ($record->banner == '<div style="width:990px; height: 120px; background: url(http://dev1.seeyourimpact.com/themes/placeholder_990x120.png) no-repeat 0 0;"></div>')
      $record->banner = NULL;
    if ($record->about == '<div style="width:649px; height:250px; background: url(http://dev1.seeyourimpact.com/themes/placeholder_649x250.png) no-repeat 0 0"><p>(fill in general info about the campaign that will appear on all fundraiser pages)</p></div>')
     $record->about = NULL;

    return static::update((object)$record);
  }

  public static function add_gallery($record) {
    $record->gallery = array(
      'campaign_banner' => PromoApi::load("{$record->partner_id}/banner-{$record->name}", "1/{$record->name}-banner"),
      'campaign_header' => PromoApi::load("{$record->partner_id}/header-{$record->name}"),
      'campaign_appeal' => PromoApi::load("{$record->partner_id}/{$record->page_id}"),
      'campaign_note' => PromoApi::load("{$record->partner_id}/sidebar-{$record->name}"),
      'campaign_about' => PromoApi::load("{$record->partner_id}/about-{$record->name}", "1/{$record->name}-about")
    );

    // Migrate headers out of the text
    if (!isset($record->campaign_page->appeal)) {
      $record->campaign_page->appeal = (object)array(
        'title' => static::extract_title($record->gallery['campaign_appeal']->html)
      );
    }

    if (!isset($record->campaign_page->about)) {
      $record->campaign_page->about = (object)array(
        'title' => static::extract_title($record->gallery['campaign_about']->html)
      );
    }

    return $record;
  }

  public static function extract_title(&$html) {
    $matches = array();

    // Extract IMG tag inside headers
    if (preg_match('/<h\d[^<]*>([^<]*)(<img [^>]*>)([^<]*)<\/h\d>/m', $html, $matches)) {
      $html = str_replace($matches[0], "<h2>{$matches[1]} {$matches[3]}</h2>{$matches[2]}", $html);
    }

    // Pull text from first header
    if (preg_match('/^(.*?)\s*<h\d[^<]*>([^<]*)<\/h\d>\s*(.*)$/ms', $html, $matches)) {
      $html = $matches[1] . $matches[3];
      return html_entity_decode($matches[2], ENT_COMPAT, 'UTF-8');
    }

    return '';
  }

  public static function handle_upgrades(&$record) {
    if (empty($record->title))
      $record->title = $record->name;

    if ($record->campaign_header) {
      PromoApi::update($promo = array(
        'ref' => "{$record->blog_id}/banner-{$record->name}",
        'html' => "<img src=\"{$record->campaign_header}\" width=\"980\">"
      ));

      $record->campaign_page->show->banner = TRUE;
      $record->campaign_page->show->header = FALSE;
      $record->campaign_page->show->give = TRUE;
      $record->fundraisers->show->give = TRUE;

      if ($record->fr_id == 0) {
        // Create the general holding fundraiser for thie campaign - this allows giving directly to the
        // campaign rather than a particular champion's fundraiser.
        $fr = FundraiserApi::create(array(
          /* No author or owner */
          'title' => 'Campaign donations', // TODO: should it have same title as campaign?
          'slug' => $record->name,
          'theme' => $record->name,
          'public' => FALSE,
          'team' => '',
          'tags' => ''
        ));
        $record->fr_id = $fr->id;
      }

      unset($record->campaign_header);
    }
  }
}

// Direct request = run the API
register_api(__FILE__, 'CampaignApi');

