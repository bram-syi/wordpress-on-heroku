<?php
/* Just a test */

include_once(ABSPATH . 'a/api/api.php');
include_once(ABSPATH . 'a/api/campaign.php');

function repl($blog_id, $str) {
  $str = str_replace( "SITE_URL", clean_url(get_blog_option( $blog_id, "siteurl" )), $str );
  $str = str_replace( "SITE_NAME", get_blog_option( $blog_id, "blogname" ), $str );
  return stripslashes( $str );
}

function db_get_version()
{
  global $wpdb;
  return $wpdb->get_var("SELECT version FROM version");

}

function db_set_version($ver)
{
  global $wpdb;

  $now = $wpdb->get_var("SELECT now() FROM DUAL");
  $wpdb->query("UPDATE version SET version=$ver, datestamp='$now'");
  return $wpdb->query("INSERT INTO version_log (id, version, datestamp) VALUES (NULL, $ver, '$now')");
}

function db_exec($sql)
{
  global $wpdb;
  echo "> $sql\r\n";
  if (false == $wpdb->query($sql))
  {
    echo "$wpdb->last_error\r\n";
  }
}

function db_count($where)
{
  global $wpdb;
  return $wpdb->get_var("SELECT count(*) FROM $where;");
}

function db_add_table($name, $cols, $opts = '', $engine = 'InnoDB')
{
  $DB_NAME = DB_NAME;

  if (db_count("information_schema.TABLES WHERE TABLE_SCHEMA='$DB_NAME' AND TABLE_NAME='$name'") > 0)
    return false;

  echo "* Adding table $name\r\n";

  $pkeys = array();
  $keys = array();

  foreach ($cols as $col => $def)
  {
    $defs = $defs . "$sep`$col` $def";
    $sep = ", ";

    if (strstr($def, "/*PRIMARYKEY") != FALSE)
      $pkeys[] = $col;
    else if (strstr($def, "/*KEY") != FALSE)
      $keys[] = $col;
  }

  if (sizeof($pkeys) > 0)
  {
    $defs = $defs . "$sep PRIMARY KEY (";
    $sep = ", ";
    $sep2 = '';
    foreach ($pkeys as $key)
    {
      $defs = $defs . "$sep2`$key`";
      $sep2 = ', ';
    }
    $defs = $defs . ")";
  }

  foreach ($keys as $key)
  {
    $defs = $defs . "$sep KEY $key($key)";
    $sep = ", ";
  }

  db_exec("CREATE TABLE `$DB_NAME`.`$name` ($defs) ENGINE=MyISAM DEFAULT CHARSET=utf8 $opts;");
  return true;
}

function db_add_column($name, $def)
{
  $DB_NAME = DB_NAME;

  list($table, $column) = split('\.', $name);
  if (db_count("information_schema.COLUMNS WHERE TABLE_SCHEMA='$DB_NAME' AND TABLE_NAME='$table' AND COLUMN_NAME='$column'") > 0)
    return false;

  echo "* Adding column $name\r\n";

  db_exec("ALTER TABLE `$DB_NAME`.`$table` ADD COLUMN $column $def");
  return true;
}

function db_remove_column($name)
{
  $DB_NAME = DB_NAME;

  list($table, $column) = split('\.', $name);
  if (db_count("information_schema.COLUMNS WHERE TABLE_SCHEMA='$DB_NAME' AND TABLE_NAME='$table' AND COLUMN_NAME='$column'") == 0)
    return false;

  echo "* Removing column $name\r\n";

  db_exec("ALTER TABLE `$DB_NAME`.`$table` DROP `$column`");
  return true;
}


function db_remove_table($name)
{
  $DB_NAME = DB_NAME;

  if (db_count("information_schema.TABLES WHERE TABLE_SCHEMA='$DB_NAME' AND TABLE_NAME='$name'") == 0)
    return false;

  echo "* Removing table $name\r\n";

  db_exec("DROP TABLE IF EXISTS `$DB_NAME`.`$name`;");
  return true;
}

function db_insert_row($table, $row)
{
  $DB_NAME = DB_NAME;
  global $wpdb;

  $cols = "";
  $values = "";
  foreach ($row as $col => $val)
  {
    $cols = $cols . "$sep`$col`";
    $values = $values . $wpdb->prepare("$sep%s", $val);
    $sep = ", ";
  }

  db_exec("INSERT INTO `$DB_NAME`.`$table` ($cols) VALUES ($values)");
  return true;
}

function db_update_row($table, $row)
{
  $DB_NAME = DB_NAME;
  global $wpdb;

  $cols = "";
  foreach ($row as $col => $val)
  {
    $cols = $cols . "$sep`$col`=" . $wpdb->prepare("%s", $val);
    $sep = ", ";
  }

  foreach ($row as $col => $val)
  {
    db_exec("UPDATE `$DB_NAME`.`$table` SET $cols WHERE (`$col` = " . $wpdb->prepare("%s", $val) . ")");
    return true;
  }
  return true;
}

function db_delete_row($table, $row)
{
  $DB_NAME = DB_NAME;
  global $wpdb;

  $cols = "";
  $values = "";
  foreach ($row as $col => $val)
  {
    $cols = $cols . "$sep`$col`=" . $wpdb->prepare("%s", $val);
    $sep = " AND ";
  }

  db_exec("DELETE FROM `$DB_NAME`.`$table` WHERE $cols");
  return true;
}

function db_insert_rows($table, $rows)
{
  foreach ($rows as $row)
  {
    db_insert_row($table, $row);
  }
}

function db_update_rows($table, $rows)
{
  foreach ($rows as $row)
  {
    db_update_row($table, $row);
  }
}

function db_delete_rows($table, $rows)
{
  foreach ($rows as $row)
  {
    db_delete_row($table, $row);
  }
}

function disable_comments()
{
   global $wpdb;
   $wpdb->query("UPDATE $wpdb->sitemeta SET meta_value='none' WHERE meta_key = 'registration'");
}

function turn_off_post_comments() {
  global $wpdb;
  $wpdb->query("UPDATE $wpdb->posts SET comment_status='closed', ping_status='closed'");
}

function reset_tweet_options()
{
   $new_options['tt_alignment'] = 'right';
   $new_options['tt_footer'] = false;

   $new_options['tt_digg'] = false;
   $new_options['tt_digg_icon'] = 'tt-digg-big2.png';

   $new_options['tt_delicious'] = false;
   $new_options['tt_delicious_icon'] = 'tt-delicious-big2.png';

   $new_options['tt_ping'] = false;
   $new_options['tt_ping_icon'] = 'tt-ping-big2-png';

   $new_options['tt_myspace'] = false;
   $new_options['tt_myspace_icon'] = 'tt-myspace-big2.png';

   $new_options['tt_buzz'] = false;
   $new_options['tt_buzz_icon'] = 'tt-buzz-big2.png';

   $new_options['tt_plurk'] = false;
   $new_options['tt_plurk_icon'] = 'tt-plurk-big2.png';

   $new_options['tt_facebook'] = true;
   $new_options['tt_facebook_icon'] = 'tt-facebook-big2.png';

   $new_options['tt_reddit'] = true;
   $new_options['tt_reddit_icon'] = 'tt-reddit-big2.png';

   $new_options['tt_su'] = true;
   $new_options['tt_su_icon'] = 'tt-su-big2.png';

   update_option('tweet_this_settings', $new_options);
}

function reset_tweet_options2() // Updated option settings
{
   $options = get_option('tweet_this_settings');

   $options['tt_footer'] = 'false';
   $options['tt_link_text'] = "[BLANK]";
   $options['tt_auto_display'] = 'true';
   $options['tt_twitter_icon'] = 'tt-twitter-micro3.png';
   $options['tt_facebook'] = 'true';
   $options['tt_facebook_icon'] = 'tt-facebook-micro3.png';
   $options['tt_reddit'] = 'false';
   $options['tt_su'] = 'false';
   $options['tt_alignment'] = 'right';

   update_option('tweet_this_settings', $options);
}

function create_faq_page()
{
   global $wpdb;
   $blog_id = $wpdb->get_var("SELECT blog_id FROM wp_blogs where blog_id = site_id");
   $user_id = $wpdb->get_var("SELECT ID FROM wp_users where user_login = 'admin'");
   $now     = date('Y-m-d H:i:s');
   $now_gmt = gmdate('Y-m-d H:i:s');
   $body    = 'Frequently asked Questions (need content)';
   $title   = 'FAQ';
   $slug    = 'faq';

   $wpdb->insert( $wpdb->posts, array(
        'post_author' => $user_id,
        'post_date' => $now,
        'post_date_gmt' => $now_gmt,
        'post_content' => stripslashes( repl($blog_id, $body) ),
        'post_excerpt' => '',
        'post_title' => stripslashes( repl($blog_id, $title)),
        'post_category' => 0,
        'post_name' => $slug,
        'post_modified' => $now,
        'post_modified_gmt' => $now_gmt,
        'post_status' => 'publish',
        'post_type' => 'page',
        'to_ping' => '',
        'pinged' => '',
        'post_content_filtered' => ''
   ) );
}

function update_thankyou_mail()
{
  global $wpdb;
  $subject='Thank you from SeeYourImpact.org and $CHARITY_NAME';
  $faq_link = $wpdb->get_var("SELECT domain FROM wp_site");
  $faq_link = 'http://' . $faq_link . '/faq';
  $content = <<<EOM
<pre>
Dear \$DONOR_NAME,

On behalf of SeeYourImpact.org and \$CHARITY_NAME, we would like to thank you for your gift of \$DONATION_AMOUNT towards a \$GIFT_NAME.

We are in the process of selecting a recipient for your gift.  In a few weeks you will receive an impact story from us with a summary and photos that will help you visualize how your donation helped make a difference.

Thanks again for choosing SeeYourImpact.org and we hope you will consider us for future gifts.

Warm regards,
- the SeeYourImpact team
--------~--------~--------~--------~--------~--------~--------~--------~--------~--------~--------~
Give time. Share Insight. Make a Difference -- volunteernetwork.ning.com


Have any questions?  Please review our frequently asked questions at:
    $faq_link
or contact us at contact@seeyourimpact.org
--------~--------~--------~--------~--------~--------~--------~--------~--------~--------~--------~
</pre>
EOM;

// Update message for all existing charities.
  $wpdb->query("UPDATE EE_EMAIL_TEMPLATE SET MAIL_SUBJECT='$subject', MAIL_CONTENT='$content' WHERE MAIL_TYPE_ID = 1");

}

function add_gift_description_links()
{
  db_add_column('gift.link_text', "varchar(255) DEFAULT NULL");
  db_add_column('gift.link_href', "varchar(255) DEFAULT NULL");
}

function add_towards_gift_id()
{
  db_add_column('gift.towards_gift_id', "int(10) DEFAULT '0'");
}

function add_current_amount()
{
  db_add_column('gift.current_amount', "int(10) DEFAULT '0'");
}

function create_homepage_category()
{
    wp_insert_category(array("cat_name"=>"Homepage", "category_description"=>"Posts that display on the home page."));
}

function add_donation_tip()
{
  db_add_column('donation.tip', "decimal(5,2) DEFAULT '0.00'");
}

function add_donation_anon()
{
  db_add_column('donation.anonymous', "TINYINT(1) DEFAULT 0");
}

function db_new_page($blog_id, $user_id, $title, $body, $slug,
$menu_order = 0, $parent = 0, $post_type = 'page', $excerpt = '',
$update = false){
    global $wpdb;

    switch_to_blog($blog_id);
    $now = date('Y-m-d H:i:s');
    $now_gmt = gmdate('Y-m-d H:i:s');

    $existing = null;

    if($update){

      $existing = get_posts('numberposts=1&post_type='.$post_type.
      '&name='.$slug);

      if(is_array($existing) && count($existing)==1)
        echo '<br/>post with "'.$slug.'" name exists as post#'.$existing[0]->ID.' on blog #'.$blog_id.', updating';
    }


    $ret_id = wp_insert_post(array(
                     'ID' =>
                       (is_array($existing) && count($existing)==1 ?
                        $existing[0]->ID:''),
                     'post_date'         => $now,
                     'post_date_gmt'     => $now_gmt,
                     'post_author'       => $user_id,
                     'post_modified'     => $now,
                     'post_modified_gmt' => $now_gmt,
		                 'post_parent'       => $parent,
                     'post_title'        => stripslashes( repl($blog_id, $title) ),
                     'post_content'      => stripslashes( repl($blog_id, $body) ),
                     'post_excerpt'      => $excerpt,
                     'post_status'       => 'publish',
                     'post_name'         => $slug,
                     'post_type'         => $post_type,
                     'menu_order'        => $menu_order,
                     'comment_count'     => 1)
                   ) or die("Unable to insert into " . $wpdb->posts . " database table. Error code: $ret_id");


//    $wpdb->query("INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) VALUES ($wpdb->insert_id, '_wp_page_template','CharityMain.php')");
    restore_current_blog();



    return $ret_id;
}

function find_page_id($page_slug)
{
   $query = new WP_Query("pagename=$page_slug");
   return $query->get_queried_object_id();
}

function create_thank_you_page()
{
    global $wpdb, $user_ID, $blog_id;

    if (find_page_id('gifts/thank-you') > 0)
      return;

    $slug = "thank-you";
    $title = 'Thank You!';
    $body = 'Thank you for your donation to SITE_NAME.';

    $parent = find_page_id('projects');
    if ($parent == 0)
      $parent = find_page_id('gifts');
    if ($parent == 0)
      return;

    $successPage = db_new_page($blog_id, $user_ID, $title, $body, $slug, 0, $parent);
}

function rename_gifts_to_projects()
{
    $id = find_page_id('gifts');
    if ($id == 0)
      return;

    wp_update_post( array(
      'ID' => $id,
      'post_name' => 'projects',
      'post_title' => 'Projects'
    ));
}

function add_donation_notifications()
{
  db_add_column('donation.notifications', "tinyint(2) DEFAULT '0'");
}

function rename_projects_to_gifts()
{
    $id = find_page_id('projects');
    if ($id == 0)
      return;

    wp_update_post( array(
      'ID' => $id,
      'post_name' => 'gifts',
      'post_title' => 'Gifts'
    ));
}

function create_gift_campaigns()
{
  db_add_table("gift_campaigns", array(
    'id' => "int(10) unsigned NOT NULL auto_increment /*PRIMARYKEY*/",
    'campaignID' => "varchar(255) default NULL /*KEY*/",
    'startDate' => "datetime NOT NULL default '0000-00-00 00:00:00'",
    'status' => "int(10) unsigned NOT NULL default '0'",
    'description' => "varchar(512) default NULL"
     ));
  db_add_column('donation.campaign', "varchar(255) default NULL");
  db_add_column('gift.campaign', "varchar(255) default NULL");
}

function denormalize_donations_table()
{
  global $wpdb;

  $wpdb->query("ALTER TABLE donationGifts MODIFY amount double NOT NULL default '0'");
  db_add_column('donationGifts.towards_gift_id', "int(10) NOT NULL default '0'");
  db_add_column('donationGifts.blog_id', "int(10) NOT NULL default '0'");
  db_add_column('donationGifts.campaign', "varchar(255)");
  db_add_column('donation.step', "varchar(255) NOT NULL default 'invalid?'");

  $wpdb->query("update donation set step='completed'");
  $wpdb->query('update donationGifts dg join gift g on dg.giftID = g.ID set dg.towards_gift_id = g.towards_gift_id, dg.campaign = g.campaign, dg.blog_id = g.blog_id');
}

// Delete inactive gifts by modifying old pointers to point at the NEW version of the same gift.
// Only deletes inactive gifts that have a newer version
function delete_inactive_gifts()
{
  global $wpdb;

  $wpdb->query("update donationGifts dg join gift g1, gift g2 set dg.giftID=g2.id where dg.giftID=g1.id and g1.active=0 and g2.tags=g1.tags and g2.active=1");
  $wpdb->query("update gift g join donationGifts dg set g.active=2 where g.id=dg.giftID and g.active=0");
  $wpdb->query("delete from gift where active=0");
  $wpdb->query("update gift set active=0 where active=2");
}

function add_donation_vars()
{
  db_add_column('donationGiver.referrer', 'varchar(255)');
  db_add_column('donation.raw', 'text');
}

function update_donationGifts_table()
{
  global $wpdb;

  $wpdb->query("ALTER TABLE `donationGifts` MODIFY COLUMN `donationID` INT(10) UNSIGNED NOT NULL, ADD COLUMN `ID` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT AFTER `campaign`, DROP PRIMARY KEY, ADD PRIMARY KEY USING BTREE(`ID`);");
}
function update_donationGifts_table2()
{
  global $wpdb;

  $wpdb->query("update donationGifts set ID=donationID;");
  db_add_column('donationGifts.story', 'int(10) UNSIGNED');
  $wpdb->query("update donationGifts set story=null");
  $wpdb->query("update donationGifts dg join donation d on dg.donationId = d.donationId set dg.story=0 where d.impactStatus=4");
}
function rename_impact_status()
{
  global $wpdb;

  $wpdb->query("update impact_feedback_status set feedback_status='pending' where feedback_status='recorded'");
}
function import_impacts()
{
  global $wpdb;
  global $blog_id;

  $wpdb->query("update wp_" . $blog_id . "_posts p join wp_" . $blog_id . "_postmeta meta on meta.post_id = p.id and meta.meta_key = 'donationIds' join donation d on d.donationId = convert(meta.meta_value, unsigned) join donationGifts dg on dg.donationId = d.donationId set dg.story=meta.post_id");
}
function convert_donation_meta()
{
  global $wpdb;
  global $blog_id;

  $wpdb->query("update wp_" . $blog_id . "_postmeta set meta_key='donation_items' where meta_key='donationIds'");
}

function move_donation_status_columns()
{
  global $wpdb;

  db_add_column('donationGifts.distributionStatus', 'int(10) UNSIGNED');
  db_add_column('donationGifts.fundTransferStatus', 'int(10) UNSIGNED');

  $wpdb->query("update donationGifts dg join donation d on d.donationId=dg.donationId set dg.distributionStatus = d.distributionStatus, dg.fundTransferStatus = d.fundTransferStatus");
}

function rename_bpa()
{
  global $wpdb;

  $site = get_current_site();
  $wpdb->query("update wp_blogs set domain='bpa.$site->domain' where blog_id=3");
  $wpdb->query("update wp_3_options set option_value='http://bpa.$site->domain' where option_id in (1,40)");
  $wpdb->query("update wp_3_options set option_value='http://bpa.$site->domain' where option_name='fileupload_url'");
}

function kill_sharethis_mail()
{
 update_option('st_sent','true');
 update_option('st_add_to_content', 'no');
 update_option('st_add_to_page', 'no');
 update_option('st_widget', '<script charset="utf-8" type="text/javascript" src="http://w.sharethis.com/button/sharethis.js#publisher=c5150261-8312-4174-9461-4848b1c702bf&type=website&buttonText=Share%20this&post_services=facebook%2Ctwitter%2Cemail%2Cmyspace%2Cdigg%2Csms%2Cwindows_live%2Cdelicious%2Cstumbleupon%2Creddit%2Cgoogle_bmarks%2Clinkedin%2Cbebo%2Cybuzz%2Cblogger%2Cyahoo_bmarks%2Cmixx%2Ctechnorati%2Cfriendfeed%2Cpropeller%2Cwordpress%2Cnewsvine%2Cxanga"></script>');
 update_option('st_pubid', 'c5150261-8312-4174-9461-4848b1c702bf');
}

function fix_user_notifications_preference()
{
   global $wpdb;

   $wpdb->query("update donation set notifications=1 where step = 'completed'");
   $wpdb->query("update donation d join donationDonor dd on dd.donationID = d.donationID join donationGiver dg on dg.ID = dd.donationGiverID set d.notifications=0, dg.sendUpdates=0 where raw like '%||0||%'");
}

function update_donation_table()
{
   global $wpdb;

   db_add_column('donation.donorID', "int (10) UNSIGNED AFTER donationAmount_Total");
   $wpdb->query("alter table donation modify column tip decimal(5,2) DEFAULT '0.00' AFTER donationAmount_Total");
   $wpdb->query("update donation set step='processing complete' where step='completed'");
   $wpdb->query("update donation d join donationDonor dd on dd.donationId=d.donationId set d.donorID = dd.donationGiverId");
}

function db_update_page($post_id, $blog_id, $user_id, $title, $body, $slug, $menu_order = 0, $parent = 0)
{
    global $wpdb;

    $now = date('Y-m-d H:i:s');
    $now_gmt = gmdate('Y-m-d H:i:s');

    $ret_id = wp_update_post(array(
                     'ID'                => $post_id,
					 'post_date'         => $now,
                     'post_date_gmt'     => $now_gmt,
                     'post_author'       => $user_id,
                     'post_modified'     => $now,
                     'post_modified_gmt' => $now_gmt,
		             'post_parent'       => $parent,
                     'post_title'        => stripslashes( repl($blog_id, $title) ),
                     'post_content'      => stripslashes( repl($blog_id, $body) ),
                     'post_excerpt'      => '',
                     'post_status'       => 'publish',
                     'post_name'         => $slug,
                     'post_type'         => 'page',
                     'post_type'         => 'page',
                     'menu_order'        => $menu_order,
                     'comment_count'     => 1)
                   ) or die("Unable to update into " . $wpdb->posts . " database table. Error code: $ret_id");

    return $ret_id;
}

function install_donation_account() {
  global $wpdb;
  $wpdb->query("CREATE TABLE IF NOT EXISTS
  donationAcct
  (id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  donorId INT(10) UNSIGNED NOT NULL,
  balance DOUBLE NOT NULL,
  dateUpdated DATETIME NOT NULL,
  `testData` TINYINT(1) UNSIGNED DEFAULT 0,
  PRIMARY KEY(`id`))");

  $wpdb->query("CREATE TABLE IF NOT EXISTS
  donationAcctTrans
  (id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  donationAcctId INT(10) UNSIGNED NOT NULL,
  amount DOUBLE NOT NULL,
  donationId INT(10) UNSIGNED NOT NULL,
  note VARCHAR(255),
  dateInserted DATETIME NOT NULL,
  PRIMARY KEY(`id`))");

  $wpdb->query("CREATE TABLE IF NOT EXISTS
  donationPromo
  (id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  donationAcctId INT(10) UNSIGNED NOT NULL,
  code VARCHAR(10) NOT NULL,
  amountTotal DOUBLE NOT NULL,
  amountUsed DOUBLE NOT NULL,
  amountEach DOUBLE NOT NULL,
  qtyTotal SMALLINT(5) UNSIGNED NOT NULL,
  qtyUsed SMALLINT(5) UNSIGNED NOT NULL,
  PRIMARY KEY(`id`))");

  $wpdb->query("ALTER TABLE `donation`
  ADD COLUMN `testData` TINYINT(1) UNSIGNED DEFAULT 0,
  ADD COLUMN `donationPromoCode` VARCHAR(10) NOT NULL;");
}

function install_donation_account2() {
  global $wpdb;
  $wpdb->query("ALTER TABLE `donationAcct`
  ADD COLUMN `code` VARCHAR(10) NOT NULL;");
}

function install_donation_account3() {
  global $wpdb;

  $wpdb->query("CREATE TABLE IF NOT EXISTS
  donationAcctType
  (id TINYINT(1) UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(20) NOT NULL,
  PRIMARY KEY(`id`))");

  $wpdb->query("INSERT INTO donationAcctType (id,name) VALUES (1,'internal')");
  $wpdb->query("INSERT INTO donationAcctType (id,name) VALUES (2,'general')");
  $wpdb->query("INSERT INTO donationAcctType (id,name) VALUES (3,'gift')");

  $wpdb->query("ALTER TABLE `donationAcct`
  ADD COLUMN `owner` INT(10) UNSIGNED NOT NULL,
  ADD COLUMN `creator` INT(10) UNSIGNED NOT NULL,
  ADD COLUMN `donationAcctTypeId` TINYINT(1) UNSIGNED NOT NULL,
  ADD COLUMN `note` VARCHAR(255) NOT NULL
  ;");

}

function upgrade_sitewide(){
  global $wpdb;

  $wpdb->query("ALTER TABLE `paypal_settings`
  ADD COLUMN provider VARCHAR(50) NOT NULL,
  ADD COLUMN api_key INT(50) UNSIGNED NOT NULL,
  ADD COLUMN api_url INT(50) UNSIGNED NOT NULL
  ;");

}

function upgrade_sitewide2(){
  global $wpdb;

  $wpdb->query("ALTER TABLE `paypal_settings`
  ADD COLUMN api_user VARCHAR(255) NOT NULL,
  MODIFY COLUMN api_key VARCHAR(255) NOT NULL,
  MODIFY COLUMN api_url VARCHAR(255) NOT NULL
  ;");

}

function upgrade_sitewide3(){
  global $wpdb;

  $wpdb->query("ALTER TABLE `paypal_settings`
  ADD COLUMN api_signature VARCHAR(255) NOT NULL;");

  $wpdb->query("INSERT INTO `paypal_settings` (`id`, `current_mode`, `type`, `business_id`, `form_action`, `return_url`, `cancel_return_url`, `notify_url`, `btn_image`, `pixel_image`, `verify_url`, `provider`, `api_key`, `api_url`, `api_user`, `api_signature`) VALUES
(3, 'DOWN', 'TEST', 'Partne_1221573342_biz@staging.seeyourimpact.com', 'https://www.sandbox.paypal.com/cgi-bin/webscr', 'testpay/returnurl.php', '', 'payments/logTransaction_secure.php', '', '', 'ssl://www.sandbox.paypal.com', 'creditcard', '1221573355', 'https://api-3t.sandbox.paypal.com/nvp', 'Partne_1221573342_biz_api1.seeyourimpact.org', 'AzTNHTF80BOT91wIPPKskBFxrT6VAFH-rbYMfBmKT5IR6iLF4VyGOZrV'),
(4, 'DOWN', 'LIVE', 'digvijay@seeyourimpact.org', 'https://www.paypal.com/cgi-bin/webscr', 'testpay/returnurl.php', '', 'payments/logTransaction.php', '', '', 'ssl://www.paypal.com', 'creditcard', '64KH8A4D3KQ5LL3W', 'https://api-3t.paypal.com/nvp', 'digvijay_api1.seeyourimpact.org', 'ABrdSGJjfqxnufxwTSUNI9Ar4i5JAokTliDcCHshM1iNnbG8i7nbjVqm');");

}

function upgrade_sitewide4(){
  global $wpdb;

  $wpdb->query("INSERT INTO `paypal_settings` (`id`, `current_mode`, `type`, `business_id`, `form_action`, `return_url`, `cancel_return_url`, `notify_url`, `btn_image`, `pixel_image`, `verify_url`, `provider`, `api_key`, `api_url`, `api_user`, `api_signature`) VALUES
(5, 'DOWN', 'TEST', '', '', 'testpay/returnurl.php', '', '', '', '', '', 'google', 'z_tK0gNQfodxfp4fjS7N8A', 'https://sandbox.google.com/checkout/', '675285858809325', ''),
(6, 'DOWN', 'LIVE', '', '', 'testpay/returnurl.php', '', '', '', '', '', 'google', 'Y8Zzo4_BaZBaJnPh2Mv85g', 'https://checkout.google.com/', '306596820749762', '');");

  $wpdb->query("INSERT INTO `paypal_settings` (`id`, `current_mode`, `type`, `business_id`, `form_action`, `return_url`, `cancel_return_url`, `notify_url`, `btn_image`, `pixel_image`, `verify_url`, `provider`, `api_key`, `api_url`, `api_user`, `api_signature`) VALUES
(7, 'DOWN', 'TEST', '', '', 'testpay/returnurl.php', '', '', '', '', '', 'amazon', 'tM0/3reogQA7zCu2WrFw1DG85myMPCakKCQOLPFJ', 'https://authorize.payments-sandbox.amazon.com/pba/paypipeline', 'AKIAJAAXDLHGNAAZAYNQ', ''),
(8, 'DOWN', 'LIVE', '', '', 'testpay/returnurl.php', '', '', '', '', '', 'amazon', 'QnzLBkUJHxs1nhVyI7EumCqYMI8YWqsVdG0l6eyE', 'https://authorize.payments.amazon.com/pba/paypipeline', 'AKIAIZCNG4MWNRYKURZQ', '');");

}

function upgrade_sitewide5(){ //change log for sitewide
  global $wpdb;

  $wpdb->query("CREATE TABLE IF NOT EXISTS
  sitewideLog
  (id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  dateTime DATETIME NOT NULL,
  changes VARCHAR(255) NOT NULL,
  notes VARCHAR(255) NOT NULL,
  PRIMARY KEY(`id`))");
}

function create_blog_charity_info(){
  global $wpdb;

  $wpdb->query("CREATE TABLE IF NOT EXISTS
  blogCharityInfo
  (blog_id INT(10) UNSIGNED NOT NULL,
  regions VARCHAR(255) NOT NULL,
  causes VARCHAR(255) NOT NULL,
  PRIMARY KEY(`blog_id`))");

  $wpdb->query("INSERT INTO `blogCharityInfo` (`blog_id`, `regions`, `causes`) VALUES (1,'','');");
  $wpdb->query("INSERT INTO `blogCharityInfo` (`blog_id`, `regions`, `causes`) VALUES (3,'india','health');");
  $wpdb->query("INSERT INTO `blogCharityInfo` (`blog_id`, `regions`, `causes`) VALUES (4,'india','education');");
  $wpdb->query("INSERT INTO `blogCharityInfo` (`blog_id`, `regions`, `causes`) VALUES (5,'india','education');");
  $wpdb->query("INSERT INTO `blogCharityInfo` (`blog_id`, `regions`, `causes`) VALUES (9,'india','education');");
  $wpdb->query("INSERT INTO `blogCharityInfo` (`blog_id`, `regions`, `causes`) VALUES (10,'india','misc');");
  $wpdb->query("INSERT INTO `blogCharityInfo` (`blog_id`, `regions`, `causes`) VALUES (11,'india','education');");
  $wpdb->query("INSERT INTO `blogCharityInfo` (`blog_id`, `regions`, `causes`) VALUES (12,'india','education');");
  $wpdb->query("INSERT INTO `blogCharityInfo` (`blog_id`, `regions`, `causes`) VALUES (14,'africa','misc');");

}

function upgrade_sitewide6(){
  global $wpdb;
  $wpdb->query("ALTER TABLE `sitewideLog`
  MODIFY COLUMN changes TEXT NOT NULL,
  MODIFY COLUMN notes TEXT NOT NULL,
  ADD COLUMN category VARCHAR(50) NOT NULL;");
}

function create_payment_table(){
  global $wpdb;
  $wpdb->query("CREATE TABLE IF NOT EXISTS
  payment (id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  dateTime DATETIME NOT NULL,
  amount DOUBLE NOT NULL,
  provider TINYINT(3) UNSIGNED NOT NULL,
  donationAcctTrans INT(10) UNSIGNED NOT NULL,
  donation INT(10) UNSIGNED NOT NULL,
  notes TEXT NOT NULL,
  raw TEXT NOT NULL,
  PRIMARY KEY(`id`))");
}

function activate_akismet()
{
   update_option('wordpress_api_key', '78ff42876dcf');
   update_option('akismet_discard_month', 'true');
}

function move_payments_to_table()
{
  global $wpdb;

  db_add_column('donation.paymentID', "INT(10) UNSIGNED NOT NULL");

  $wpdb->query("insert into payment (donation, dateTime, amount, raw) select donationID as donation, donationDate as dateTime, donationAmount_Total as amount, raw FROM donation");
  $wpdb->query("update donation d JOIN payment p ON d.donationID = p.donation SET d.paymentID = p.ID");

  db_remove_column('donation.raw');
}

function upgrade_sitewide_gc(){
  global $wpdb;

  $wpdb->query("INSERT INTO `paypal_settings` (`id`, `current_mode`, `type`, `business_id`, `form_action`, `return_url`, `cancel_return_url`, `notify_url`, `btn_image`, `pixel_image`, `verify_url`, `provider`, `api_key`, `api_url`, `api_user`, `api_signature`) VALUES
(9, 'DOWN', 'TEST', '', '', '', '', '', '', '', '', 'giftcert', '', '', '', ''),
(10, 'DOWN', 'LIVE', '', '', '', '', '', '', '', '', 'giftcert', '', '', '', '');");

}

function upgrade_payment_table(){
  global $wpdb;
  $wpdb->query("ALTER TABLE `payment`
  ADD COLUMN `testData` TINYINT(1) UNSIGNED DEFAULT 0 NOT NULL;");

  $wpdb->query("INSERT INTO `donationAcctType` (id,name) VALUES (4,'matching') ");
}

function upgrade_donation_acct(){
  global $wpdb;
  $wpdb->query("ALTER TABLE `donationAcct`
  ADD COLUMN `name` VARCHAR(100) NOT NULL;");

}

function add_donation_giver_notes(){
  global $wpdb;
  $wpdb->query("ALTER TABLE `donationGiver`
  ADD COLUMN `notes` VARCHAR(255) NOT NULL;");
}

function link_payment_to_acct_trans(){
  global $wpdb;
  $wpdb->query("ALTER TABLE `donationAcctTrans`
  ADD COLUMN paymentID INT(10) UNSIGNED NOT NULL;");
}

function upgrade_payment_table2(){
  global $wpdb;
  $wpdb->query("ALTER TABLE `payment`
  ADD COLUMN `tip` DOUBLE UNSIGNED DEFAULT 0 NOT NULL,
  ADD COLUMN `discount` DOUBLE UNSIGNED DEFAULT 0 NOT NULL;");
}

function adding_littledrops_info(){
  global $wpdb;
  $wpdb->query("INSERT INTO `blogCharityInfo` (`blog_id`, `regions`, `causes`) VALUES (16,'africa','education');");

}

function upgrade_sitewide_mg(){
  global $wpdb;

  $wpdb->query("INSERT INTO `paypal_settings` (`id`, `current_mode`, `type`, `business_id`, `form_action`, `return_url`, `cancel_return_url`, `notify_url`, `btn_image`, `pixel_image`, `verify_url`, `provider`, `api_key`, `api_url`, `api_user`, `api_signature`) VALUES
(11, 'DOWN', 'TEST', '', '', '', '', '', '', '', '', 'matching', '', '', '', ''),
(12, 'DOWN', 'LIVE', '', '', '', '', '', '', '', '', 'matching', '', '', '', '');");

}

function upgrade_donation_acct_mg(){
  global $wpdb;

  $wpdb->query("ALTER TABLE `donationAcct`
  ADD COLUMN `use` MEDIUMINT UNSIGNED DEFAULT 0 NOT NULL,
  ADD COLUMN `priority`  MEDIUMINT UNSIGNED DEFAULT 0 NOT NULL;");

}

function remove_donationDonors_table() {
  global $wpdb;

  $wpdb->query("update donation d join donationDonor dd on dd.donationID = d.donationID set d.donorID = dd.donationGiverID");
  $wpdb->query("drop table donationDonor");
}

function upgrade_donation_gifts_mg() {
  global $wpdb;

  $wpdb->query("ALTER TABLE `donationGifts` ADD COLUMN `matchingDonationAcctTrans` INT UNSIGNED DEFAULT 0 NOT NULL;");
}

function update_notifications() {
  db_add_column('notificationHistory.mailType', "int(10) UNSIGNED DEFAULT 0 NOT NULL AFTER notificationID");
  db_add_column('notificationHistory.postID', "int(10) UNSIGNED DEFAULT 0 NOT NULL AFTER donationID");
}

function add_test_donations() {
  global $wpdb;

  db_add_column('donation.test', "BOOL DEFAULT 0 NOT NULL AFTER donorID");
  $wpdb->query("update donation d join donationGiver donor on donor.ID = d.donorID set test=1 where firstName='Test'");
  $wpdb->query("update donation d join donationGiver donor on donor.ID = d.donorID set test=1 where donationAmount_Total < 5");
  $wpdb->query("update donation d join donationGiver donor on donor.ID = d.donorID join donationGifts item on item.donationID = d.donationID join wp_blogs blog on blog.blog_id = item.blog_id set test=1 where d.test=0 and blog.domain like 'test%'");
  $wpdb->query("update donation d join donationGiver donor on donor.ID = d.donorID set test=1 where donor.notes = 'TEST ME'");

}

function fix_gc_notes_length() {
  global $wpdb;
  $wpdb->query("ALTER TABLE donationGiver MODIFY COLUMN notes TEXT NOT NULL");

}

function fix_bug_200(){
  global $wpdb;
  $wpdb->query("UPDATE payment SET amount=240, discount=240 WHERE id = 392 AND amount = 516");
  $wpdb->query("UPDATE donation SET donationAmount_Total=240 WHERE donationID = 388 AND donationAmount_Total=516");
  $wpdb->query("UPDATE donationGifts SET amount=240 WHERE ID = 607 AND amount = 516");

  $wpdb->query("UPDATE payment SET amount=480, discount=480 WHERE id = 393 AND amount = 996");
  $wpdb->query("UPDATE donation SET donationAmount_Total=480 WHERE donationID = 389 AND donationAmount_Total=996");
  $wpdb->query("UPDATE donationGifts SET amount=240 WHERE ID = 608 AND amount = 498");
  $wpdb->query("UPDATE donationGifts SET amount=240 WHERE ID = 609 AND amount = 498");

  $wpdb->query("UPDATE payment SET amount=240, discount=240 WHERE id = 394 AND amount = 516");
  $wpdb->query("UPDATE donation SET donationAmount_Total=240 WHERE donationID = 390 AND donationAmount_Total=516");
  $wpdb->query("UPDATE donationGifts SET amount=240 WHERE ID = 610 AND amount = 516");

  $wpdb->query("UPDATE payment SET amount=240, discount=240 WHERE id = 395 AND amount = 516");
  $wpdb->query("UPDATE donation SET donationAmount_Total=240 WHERE donationID = 391 AND donationAmount_Total=516");
  $wpdb->query("UPDATE donationGifts SET amount=240 WHERE ID = 611 AND amount = 516");

  $wpdb->query("UPDATE payment SET amount=240, discount=240 WHERE id = 396 AND amount = 516");
  $wpdb->query("UPDATE donation SET donationAmount_Total=240 WHERE donationID = 392 AND donationAmount_Total=516");
  $wpdb->query("UPDATE donationGifts SET amount=240 WHERE ID = 612 AND amount = 516");

  $wpdb->query("UPDATE payment SET discount=17.25 WHERE id = 397 AND amount = 17.25");
  $wpdb->query("UPDATE donation SET donationAmount_Total=15 WHERE donationID = 393 AND donationAmount_Total=32.25");
  $wpdb->query("UPDATE donationGifts SET amount=17.25 WHERE ID = 613 AND amount = 32.25");

  $wpdb->query("DELETE FROM donationGifts WHERE ID >= 552 AND ID <= 558 AND blog_id = 5 AND donationID = 359");
  $wpdb->query("DELETE FROM donation WHERE donationID = 359 AND donationAmount_Total = 42");
  $wpdb->query("DELETE FROM payment WHERE id = 359 AND amount = 42");
}

function fix_bug_200_again(){
  global $wpdb;
  $wpdb->query("UPDATE payment SET discount = 30 WHERE id = 404 AND amount = 60");
  $wpdb->query("UPDATE donation SET donationAmount_Total = 30 WHERE donationID = 399 AND donationAmount_Total=60");
  $wpdb->query("UPDATE donationGifts SET amount = 30 WHERE ID = 623 AND amount = 60");

  $wpdb->query("DELETE FROM donationGifts WHERE ID = 612 AND amount = 240");
  $wpdb->query("DELETE FROM donation WHERE donationID = 392 AND donationAmount_Total = 240");
  $wpdb->query("DELETE FROM payment WHERE id = 396 AND amount = 240");
}

function fix_bug_200_again2(){
  global $wpdb;

  $wpdb->query("UPDATE payment SET amount=240, discount=240 WHERE id = 392 AND amount = 276");
  $wpdb->query("UPDATE payment SET amount=480, discount=480 WHERE id = 393 AND amount = 516");
  $wpdb->query("UPDATE payment SET amount=240, discount=240 WHERE id = 394 AND amount = 276");
  $wpdb->query("UPDATE payment SET amount=240, discount=240 WHERE id = 395 AND amount = 276");
  $wpdb->query("DELETE FROM payment WHERE id = 396 AND amount = 276");
  $wpdb->query("UPDATE payment SET discount = 30 WHERE id = 404 AND amount = 30");
}

function change_email_template(){
  global $wpdb;

  $wpdb->query("DELETE FROM EE_EMAIL_TEMPLATE WHERE blog_id > 1");
  $wpdb->query("UPDATE EE_EMAIL_TEMPLATE SET "
  . "MAIL_SUBJECT = 'Thank you from SeeYourImpact.org and $CHARITY_NAME!' "
  . "MAIL_CONTENT = '<p>Dear $DONOR_NAME,</p><p>On behalf of SeeYourImpact.org and $CHARITY_NAME, we thank you for giving $DONATION_AMOUNT, which will provide $GIFT_NAME for a beneficiary at $CHARITY_NAME.</p><p>Here is what will happen next:</p><ol><li>Your donation will be credited to $CHARITY_NAME within a few days.</li><li>$CHARITY_NAME will select a beneficiary for your gift.</li><li>Within a couple of weeks, you will receive a follow-up email with a photo and details of the person you helped.</li></ol><p>Thank you again for your generous gift!</p><p>Sincerely,<br />The SeeYourImpact Team</p>' "
  . "WHERE mail_type_id = 1 ");
  $wpdb->query("UPDATE EE_EMAIL_TEMPLATE SET "
  . "MAIL_SUBJECT = 'Update regarding your donation to SeeYourImpact.org and $CHARITY_NAME ' "
  . "MAIL_CONTENT = '<p>Dear $DONOR_NAME,</p><p>You recently donated $GIFT_NAME to a beneficiary at $CHARITY_NAME. A recipient for your gift has been selected and the following impact story has been published at: $POST_LINK.</p><p>We hope you have had a fulfilling donation experience. Help us by sharing your unique impact story with your friends so that they can also experience the joy of giving!</p><p>Sincerely,<br />The SeeYourImpact Team</p>' "
  . "WHERE mail_type_id = 2 ");
  $wpdb->query("UPDATE EE_EMAIL_TEMPLATE SET "
  . "MAIL_SUBJECT = '$GIFT_NAME has been donated to SeeYourImpact.org and $CHARITY_NAME' "
  . "MAIL_CONTENT = '<p>Dear Administrator,</p><p>A gift with the following details has been donated to $CHARITY_NAME via the SeeYourImpact network.</p><p>Donor Name: $DONOR_NAME<br />Donor Email: $DONOR_EMAIL<br />Gift Funded: $GIFT_NAME<br />Donation Amount: $DONATION_AMOUNT<br />Tip Amount: $TIP_AMOUNT<br />Transaction date: $TRANSACTION_DATE</p><p>Please initiate the process for identifying a beneficiary and upload the impact.</p><p>Sincerely,<br />The SeeYourImpact Team</p>' "
  . "WHERE mail_type_id = 3 ");
  $wpdb->query("UPDATE EE_EMAIL_TEMPLATE SET "
  . "MAIL_SUBJECT = 'An impact story for $CHARITY_NAME has been saved' "
  . "MAIL_CONTENT = '<p>An impact story has been saved:<br />$POST_LINK<br />Donor Name:$DONOR_NAME<br />Donor Email:$DONOR_EMAIL<br />Transaction date:$TRANSACTION_DATE</p><p>Regards,<br />$CHARITY_NAME</p>' "
  . "WHERE mail_type_id = 4 ");
  $wpdb->query("UPDATE EE_EMAIL_TEMPLATE SET "
  . "MAIL_SUBJECT = 'Thank you from SeeYourImpact.org and $CHARITY_NAME!' "
  . "MAIL_CONTENT = '<p>Dear $DONOR_NAME,</p><p>On behalf of SeeYourImpact.org and $CHARITY_NAME, we thank you for giving $DONATION_AMOUNT, which will provide $GIFT_NAME for a beneficiary at $CHARITY_NAME.</p><p>Here is what will happen next:</p><ol><li>Your donation will be credited to $CHARITY_NAME within a few days.</li><li>The beneficiary of your gift will be selected when donations reach $AGGREGATE_AMOUNT or $AGGREGATE_NAME. You can help us reach this amount quickly by forwarding this e-mail to your friends with a note to help make this happen!</li><li>Within a couple of weeks of this total being reached, you will receive a follow-up email with a photo and details of the person you helped.</li></ol><p>Thank you again for your generous gift!</p><p>Sincerely,<br />The SeeYourImpact Team</p>' "
  . "WHERE mail_type_id = 5 ");
  $wpdb->query("UPDATE EE_EMAIL_TEMPLATE SET "
  . "MAIL_SUBJECT = 'Update regarding your donation to SeeYourImpact.org and $CHARITY_NAME' "
  . "MAIL_CONTENT = '<p>Dear $DONOR_NAME,</p><p>You recently gave $DONATION_AMOUNT for $GIFT_NAME to a beneficiary at $CHARITY_NAME. After combining the gifts from $AGGREGATE_QUANTITY donors, a recipient has been selected and the following impact story has been posted at: $POST_LINK</p><p>We hope you have had a fulfilling donation experience. Help us by sharing your unique impact story with your friends so that they can also experience the joy of giving!</p><p>Sincerely,<br />The SeeYourImpact Team</p>' "
  . "WHERE mail_type_id = 6 ");
  $wpdb->query("UPDATE EE_EMAIL_TEMPLATE SET "
  . "MAIL_SUBJECT = '$GIFT_NAME has been donated to SeeYourImpact.org and $CHARITY_NAME' "
  . "MAIL_CONTENT = '<p>Dear Administrator,</p><p>An aggregate gift with the following details has been donated to $CHARITY_NAME via the SeeYourImpact network.</p><p>Donor Name: $DONOR_NAME<br />Donor Email: $DONOR_EMAIL<br />Gift Funded: $GIFT_NAME<br />Donation Amount: $DONATION_AMOUNT<br />Tip Amount: $TIP_AMOUNT<br />Transaction date: $TRANSACTION_DATE</p><p>Once $AGGREGATE_AMOUNT have been received, please initiate the process for identifying a beneficiary and upload the impact.</p><p>Sincerely,<br />The SeeYourImpact Team</p>' "
  . "WHERE mail_type_id = 7 ");
}

function add_donationAcct_params(){
  global $wpdb;
  $wpdb->query("ALTER TABLE `donationAcct` ADD COLUMN `params` TEXT NOT NULL;");
}

function add_payment_txnid(){
  global $wpdb;
  $wpdb->query("ALTER TABLE `payment` ADD COLUMN `txnID` VARCHAR(20) NOT NULL;");
}

function add_donation_blogid_giftid(){
  global $wpdb;
  $wpdb->query("ALTER TABLE `donationAcct` ADD COLUMN `blogId` SMALLINT UNSIGNED NOT NULL;");
  $wpdb->query("ALTER TABLE `donationAcct` ADD COLUMN `giftId` INT UNSIGNED NOT NULL;");
}

function add_cartdata_emailverify_fbconnect(){
  global $wpdb;
  $wpdb->query("ALTER TABLE `payment` ADD COLUMN `cart` TEXT NOT NULL;");
  $wpdb->query("ALTER TABLE `donationGiver` ADD COLUMN `verified` TINYINT(1) UNSIGNED DEFAULT 0;");
  $wpdb->query("ALTER TABLE `donationGiver` ADD COLUMN `fb_connect` VARCHAR(255) NOT NULL;");
  $wpdb->query("UPDATE `donationGiver` SET `verified`=1;");
}

function add_bloginfo_tip(){
  global $wpdb;
  $wpdb->query("ALTER TABLE `blogCharityInfo` ADD COLUMN `tipNote` TEXT NOT NULL;");
  $wpdb->query("ALTER TABLE `blogCharityInfo` ADD COLUMN `tipEnabled` TINYINT(1) UNSIGNED DEFAULT 1 NULL;");
  $wpdb->query("UPDATE `blogCharityInfo` SET `tipNote` = 'We\'ll show you the connection of your gift to the actual beneficiary, without taking a cent from your donation! Love the idea? Please contribute an additional amount to help us cover the costs of service.' WHERE blog_id = 1;");
}

function add_thankyou_page_shortcode(){
  global $wpdb;
  $wpdb->query("UPDATE wp_1_posts SET `post_title` = 'Thank You!', `post_content` = '".'<h1>Thank you!</h1>[caption id="" align="alignleft" width="300" caption=" "]<img class=" " src="http://staging.seeyourimpact.com/wp-content/images/thank_you.jpg" alt="" width="300" height="200" />[/caption]<p style="text-align: left;margin-top: 20px">Thank YOU for your generous support!</p><p>[thankyou]</p><p style="text-align: center;padding-top:20px"><a href="http://seeyourimpact.org"><strong><img class="size-full wp-image-194 aligncenter" src="http://naftb.seeyourimpact.org/files/2009/10/SYIButton.jpg" alt="SYIButton" width="200" height="25" /></strong></a></p>'."' WHERE `post_title` = 'Gift Certificate Purchased' AND `post_type` = 'page'");
  $wpdb->query("UPDATE wp_3_posts SET `post_title` = 'Thank You!', `post_content` = '".'<h1>Thank you!</h1>[caption id="" align="alignleft" width="300" caption=" "]<img class=" " src="http://staging.seeyourimpact.com/wp-content/images/thank_you.jpg" alt="" width="300" height="200" />[/caption]<p style="text-align: left;margin-top: 20px">Thank YOU for your generous support!</p><p>[thankyou]</p><p style="text-align: center;padding-top:20px"><a href="http://seeyourimpact.org"><strong><img class="size-full wp-image-194 aligncenter" src="http://naftb.seeyourimpact.org/files/2009/10/SYIButton.jpg" alt="SYIButton" width="200" height="25" /></strong></a></p>'."' WHERE `post_title` = 'Thank You!' AND `post_type` = 'page'");
  $wpdb->query("UPDATE wp_4_posts SET `post_title` = 'Thank You!', `post_content` = '".'<h1>Thank you!</h1>[caption id="" align="alignleft" width="300" caption=" "]<img class=" " src="http://staging.seeyourimpact.com/wp-content/images/thank_you.jpg" alt="" width="300" height="200" />[/caption]<p style="text-align: left;margin-top: 20px">Thank YOU for your generous support!</p><p>[thankyou]</p><p style="text-align: center;padding-top:20px"><a href="http://seeyourimpact.org"><strong><img class="size-full wp-image-194 aligncenter" src="http://naftb.seeyourimpact.org/files/2009/10/SYIButton.jpg" alt="SYIButton" width="200" height="25" /></strong></a></p>'."' WHERE `post_title` = 'Thank You!' AND `post_type` = 'page'");
  $wpdb->query("UPDATE wp_5_posts SET `post_title` = 'Thank You!', `post_content` = '".'<h1>Thank you!</h1>[caption id="" align="alignleft" width="300" caption=" "]<img class=" " src="http://staging.seeyourimpact.com/wp-content/images/thank_you.jpg" alt="" width="300" height="200" />[/caption]<p style="text-align: left;margin-top: 20px">Thank YOU for your generous support!</p><p>[thankyou]</p><p style="text-align: center;padding-top:20px"><a href="http://seeyourimpact.org"><strong><img class="size-full wp-image-194 aligncenter" src="http://naftb.seeyourimpact.org/files/2009/10/SYIButton.jpg" alt="SYIButton" width="200" height="25" /></strong></a></p>'."' WHERE `post_title` = 'Thank You!' AND `post_type` = 'page'");
  $wpdb->query("UPDATE wp_9_posts SET `post_title` = 'Thank You!', `post_content` = '".'<h1>Thank you!</h1>[caption id="" align="alignleft" width="300" caption=" "]<img class=" " src="http://staging.seeyourimpact.com/wp-content/images/thank_you.jpg" alt="" width="300" height="200" />[/caption]<p style="text-align: left;margin-top: 20px">Thank YOU for your generous support!</p><p>[thankyou]</p><p style="text-align: center;padding-top:20px"><a href="http://seeyourimpact.org"><strong><img class="size-full wp-image-194 aligncenter" src="http://naftb.seeyourimpact.org/files/2009/10/SYIButton.jpg" alt="SYIButton" width="200" height="25" /></strong></a></p>'."' WHERE `post_title` = 'Thank You!' AND `post_type` = 'page'");
  $wpdb->query("UPDATE wp_10_posts SET `post_title` = 'Thank You!', `post_content` = '".'<h1>Thank you!</h1>[caption id="" align="alignleft" width="300" caption=" "]<img class=" " src="http://staging.seeyourimpact.com/wp-content/images/thank_you.jpg" alt="" width="300" height="200" />[/caption]<p style="text-align: left;margin-top: 20px">Thank YOU for your generous support!</p><p>[thankyou]</p><p style="text-align: center;padding-top:20px"><a href="http://seeyourimpact.org"><strong><img class="size-full wp-image-194 aligncenter" src="http://naftb.seeyourimpact.org/files/2009/10/SYIButton.jpg" alt="SYIButton" width="200" height="25" /></strong></a></p>'."' WHERE `post_title` = 'Thank You!' AND `post_type` = 'page'");
  $wpdb->query("UPDATE wp_11_posts SET `post_title` = 'Thank You!', `post_content` = '".'<h1>Thank you!</h1>[caption id="" align="alignleft" width="300" caption=" "]<img class=" " src="http://staging.seeyourimpact.com/wp-content/images/thank_you.jpg" alt="" width="300" height="200" />[/caption]<p style="text-align: left;margin-top: 20px">Thank YOU for your generous support!</p><p>[thankyou]</p><p style="text-align: center;padding-top:20px"><a href="http://seeyourimpact.org"><strong><img class="size-full wp-image-194 aligncenter" src="http://naftb.seeyourimpact.org/files/2009/10/SYIButton.jpg" alt="SYIButton" width="200" height="25" /></strong></a></p>'."' WHERE `post_title` = 'Thank You!' AND `post_type` = 'page'");
  $wpdb->query("UPDATE wp_12_posts SET `post_title` = 'Thank You!', `post_content` = '".'<h1>Thank you!</h1>[caption id="" align="alignleft" width="300" caption=" "]<img class=" " src="http://staging.seeyourimpact.com/wp-content/images/thank_you.jpg" alt="" width="300" height="200" />[/caption]<p style="text-align: left;margin-top: 20px">Thank YOU for your generous support!</p><p>[thankyou]</p><p style="text-align: center;padding-top:20px"><a href="http://seeyourimpact.org"><strong><img class="size-full wp-image-194 aligncenter" src="http://naftb.seeyourimpact.org/files/2009/10/SYIButton.jpg" alt="SYIButton" width="200" height="25" /></strong></a></p>'."' WHERE `post_title` = 'Thank You!' AND `post_type` = 'page'");
  $wpdb->query("UPDATE wp_14_posts SET `post_title` = 'Thank You!', `post_content` = '".'<h1>Thank you!</h1>[caption id="" align="alignleft" width="300" caption=" "]<img class=" " src="http://staging.seeyourimpact.com/wp-content/images/thank_you.jpg" alt="" width="300" height="200" />[/caption]<p style="text-align: left;margin-top: 20px">Thank YOU for your generous support!</p><p>[thankyou]</p><p style="text-align: center;padding-top:20px"><a href="http://seeyourimpact.org"><strong><img class="size-full wp-image-194 aligncenter" src="http://naftb.seeyourimpact.org/files/2009/10/SYIButton.jpg" alt="SYIButton" width="200" height="25" /></strong></a></p>'."' WHERE `post_title` = 'Thank You!' AND `post_type` = 'page'");
  $wpdb->query("UPDATE wp_16_posts SET `post_title` = 'Thank You!', `post_content` = '".'<h1>Thank you!</h1>[caption id="" align="alignleft" width="300" caption=" "]<img class=" " src="http://staging.seeyourimpact.com/wp-content/images/thank_you.jpg" alt="" width="300" height="200" />[/caption]<p style="text-align: left;margin-top: 20px">Thank YOU for your generous support!</p><p>[thankyou]</p><p style="text-align: center;padding-top:20px"><a href="http://seeyourimpact.org"><strong><img class="size-full wp-image-194 aligncenter" src="http://naftb.seeyourimpact.org/files/2009/10/SYIButton.jpg" alt="SYIButton" width="200" height="25" /></strong></a></p>'."' WHERE `post_title` = 'Thank You!' AND `post_type` = 'page'");
  $wpdb->query("UPDATE wp_17_posts SET `post_title` = 'Thank You!', `post_content` = '".'<h1>Thank you!</h1>[caption id="" align="alignleft" width="300" caption=" "]<img class=" " src="http://staging.seeyourimpact.com/wp-content/images/thank_you.jpg" alt="" width="300" height="200" />[/caption]<p style="text-align: left;margin-top: 20px">Thank YOU for your generous support!</p><p>[thankyou]</p><p style="text-align: center;padding-top:20px"><a href="http://seeyourimpact.org"><strong><img class="size-full wp-image-194 aligncenter" src="http://naftb.seeyourimpact.org/files/2009/10/SYIButton.jpg" alt="SYIButton" width="200" height="25" /></strong></a></p>'."' WHERE `post_title` = 'Thank You!' AND `post_type` = 'page'");
  $wpdb->query("UPDATE wp_18_posts SET `post_title` = 'Thank You!', `post_content` = '".'<h1>Thank you!</h1>[caption id="" align="alignleft" width="300" caption=" "]<img class=" " src="http://staging.seeyourimpact.com/wp-content/images/thank_you.jpg" alt="" width="300" height="200" />[/caption]<p style="text-align: left;margin-top: 20px">Thank YOU for your generous support!</p><p>[thankyou]</p><p style="text-align: center;padding-top:20px"><a href="http://seeyourimpact.org"><strong><img class="size-full wp-image-194 aligncenter" src="http://naftb.seeyourimpact.org/files/2009/10/SYIButton.jpg" alt="SYIButton" width="200" height="25" /></strong></a></p>'."' WHERE `post_title` = 'Thank You!' AND `post_type` = 'page'");
  $wpdb->query("UPDATE wp_19_posts SET `post_title` = 'Thank You!', `post_content` = '".'<h1>Thank you!</h1>[caption id="" align="alignleft" width="300" caption=" "]<img class=" " src="http://staging.seeyourimpact.com/wp-content/images/thank_you.jpg" alt="" width="300" height="200" />[/caption]<p style="text-align: left;margin-top: 20px">Thank YOU for your generous support!</p><p>[thankyou]</p><p style="text-align: center;padding-top:20px"><a href="http://seeyourimpact.org"><strong><img class="size-full wp-image-194 aligncenter" src="http://naftb.seeyourimpact.org/files/2009/10/SYIButton.jpg" alt="SYIButton" width="200" height="25" /></strong></a></p>'."' WHERE `post_title` = 'Thank You!' AND `post_type` = 'page'");
  $wpdb->query("UPDATE wp_20_posts SET `post_title` = 'Thank You!', `post_content` = '".'<h1>Thank you!</h1>[caption id="" align="alignleft" width="300" caption=" "]<img class=" " src="http://staging.seeyourimpact.com/wp-content/images/thank_you.jpg" alt="" width="300" height="200" />[/caption]<p style="text-align: left;margin-top: 20px">Thank YOU for your generous support!</p><p>[thankyou]</p><p style="text-align: center;padding-top:20px"><a href="http://seeyourimpact.org"><strong><img class="size-full wp-image-194 aligncenter" src="http://naftb.seeyourimpact.org/files/2009/10/SYIButton.jpg" alt="SYIButton" width="200" height="25" /></strong></a></p>'."' WHERE `post_title` = 'Thank You!' AND `post_type` = 'page'");
  $wpdb->query("UPDATE wp_22_posts SET `post_title` = 'Thank You!', `post_content` = '".'<h1>Thank you!</h1>[caption id="" align="alignleft" width="300" caption=" "]<img class=" " src="http://staging.seeyourimpact.com/wp-content/images/thank_you.jpg" alt="" width="300" height="200" />[/caption]<p style="text-align: left;margin-top: 20px">Thank YOU for your generous support!</p><p>[thankyou]</p><p style="text-align: center;padding-top:20px"><a href="http://seeyourimpact.org"><strong><img class="size-full wp-image-194 aligncenter" src="http://naftb.seeyourimpact.org/files/2009/10/SYIButton.jpg" alt="SYIButton" width="200" height="25" /></strong></a></p>'."' WHERE `post_title` = 'Thank You!' AND `post_type` = 'page'");
}

function add_donationGiver_validated(){
  global $wpdb;
  $wpdb->query("ALTER TABLE `donationGiver` ADD COLUMN `validated` TINYINT(1) UNSIGNED DEFAULT 0 NULL;");
}

function validate_current_user(){
  global $wpdb;
  $wpdb->query("UPDATE `donationGiver` SET validated=1");
}

function upgrade_sitewide_fbconnect(){
  global $wpdb;

  $wpdb->query("INSERT INTO `paypal_settings` (`id`, `current_mode`, `type`, `business_id`, `form_action`, `return_url`, `cancel_return_url`, `notify_url`, `btn_image`, `pixel_image`, `verify_url`, `provider`, `api_key`, `api_url`, `api_user`, `api_signature`) VALUES
(13, 'DOWN', 'TEST', '', '', '', '', '', '', '', '', 'fbconnect', '', '', '', ''),
(14, 'DOWN', 'LIVE', '', '', '', '', '', '', '', '', 'fbconnect', '', '', '', '');");

}

function change_thankyoupage_template(){
  global $wpdb;
  //grab all blog ids
  $blog_ids = $wpdb->get_col("SELECT blog_id FROM wp_blogs");

  //iterate blog ids
  foreach($blog_ids as $v){

	//empty out the template
	$wpdb->query("UPDATE `wp_".intval($v)."_posts` "
	  . "SET `post_title` = 'Thank You!', `post_content` = '[thankyou]' "
	  . "WHERE `post_title` = 'Thank You!' AND `post_type` = 'page'"
	);

	//get the post id
	$pid = $wpdb->get_var("SELECT ID FROM `wp_".intval($v)."_posts` "
	  . "WHERE `post_title` = 'Thank You!' AND `post_type` = 'page'"
	);

	//get the meta id for tweet_this_hide
	$mid=$wpdb->get_var($wpdb->prepare(
	  "SELECT meta_id FROM `wp_".intval($v)."_postmeta` "
	  . "WHERE post_id=%d AND meta_key='tweet_this_hide'",$pid));

	if(NULL==$mid){
	  //insert if it doesnt exist
	  $wpdb->query(
	  $wpdb->prepare(
	  "INSERT INTO `wp_".intval($v)."_postmeta` "
	  . "(post_id,meta_key,meta_value) VALUES (%d,'tweet_this_hide','true')",$pid
	  ));
	}else{
	  //update if it exists
	  $wpdb->query(
	  $wpdb->prepare(
	  "UPDATE `wp_".intval($v)."_postmeta` "
	  . "SET meta_value = 'true' WHERE meta_id = %d ",$mid
	  ));
	}
  }
}

function update_gift_variable(){
  global $wpdb;
  $wpdb->query("ALTER TABLE gift ADD COLUMN `varAmount` TINYINT(1) UNSIGNED DEFAULT 0 NOT NULL AFTER unitAmount;");
  $wpdb->query("ALTER TABLE gift ADD COLUMN `pluralName` VARCHAR(255) AFTER displayName;");
}

function add_donationGift_tip(){
  global $wpdb;
  $wpdb->query("ALTER TABLE donationGifts ADD COLUMN `tip` DOUBLE UNSIGNED DEFAULT 0 NOT NULL AFTER amount");
  $donations = $wpdb->get_results("SELECT donationID, tip FROM donation ",ARRAY_A);

  if($donations != NULL && count($donations)>0){
	foreach($donations as $donation){
	  $dgs = $wpdb->get_results(
		$wpdb->prepare(
		"SELECT * FROM donationGifts WHERE donationID=%d AND matchingDonationAcctTrans = 0",
		$donation['donationID']),ARRAY_A);

	  if($dgs != NULL && count($dgs)>0){
		$distributedTip = floatval($donation['tip']/count($dgs));
		foreach($dgs as $dg){
		  $wpdb->query($wpdb->prepare(
		  "UPDATE donationGifts SET tip=%f WHERE ID = %d ",$distributedTip,$dg['ID']
		  ));
		}
	  }
	}
  }
}

function create_donationContact(){
  global $wpdb;

  $wpdb->query("CREATE TABLE IF NOT EXISTS
  donationContact
  (id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  donationID INT (10) UNSIGNED NOT NULL,
  value VARCHAR(255) NOT NULL,
  type VARCHAR(50) NOT NULL,
  PRIMARY KEY(`id`))");

}

function add_notificationHistory_donationContact(){
  global $wpdb;
  $wpdb->query("ALTER TABLE notificationHistory "
    ." ADD COLUMN `donationContactID` INT(10) UNSIGNED NOT NULL DEFAULT 0");
}

function create_featuredContent(){
  global $wpdb;
  $wpdb->query("CREATE TABLE IF NOT EXISTS
  featuredContent
  (ID INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  imageUrl VARCHAR(255) NOT NULL,
  title TEXT NOT NULL,
  content TEXT NOT NULL,
  css VARCHAR(255) NOT NULL,
  parent INT(10) UNSIGNED NOT NULL,
  status VARCHAR(20) NOT NULL,
  PRIMARY KEY(ID)
  )
  ");
}

function add_gift_image_region(){
  global $wpdb;
  $wpdb->query("ALTER TABLE gift ADD COLUMN imageUrl VARCHAR(255) NOT NULL");
  $wpdb->query("ALTER TABLE gift ADD COLUMN region VARCHAR(50) NOT NULL");
  $wpdb->query("ALTER TABLE gift ADD COLUMN location VARCHAR(255) NOT NULL");
}

function create_featuredGiftSet(){
  global $wpdb;
  $wpdb->query("CREATE TABLE IF NOT EXISTS
  featuredGiftSet
  (ID INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  imageUrl VARCHAR(255) NOT NULL,
  title TEXT NOT NULL,
  description TEXT NOT NULL,
  request TEXT NOT NULL,
  status VARCHAR(20) NOT NULL,
  PRIMARY KEY(ID)
  )
  ");
}

function switch_to_new_theme() {
  global $blog_id;

  $theme = ($blog_id == 1) ? "syi-home" : "charity";
  switch_theme("syi", $theme);
}

function create_new_home_pages() {
   global $user_id;

   db_new_page(1, $user_id, 'How to Get Involved', 'TEMPORARY CONTENT', 'how-to', 4);
   db_new_page(1, $user_id, 'Confirm donation', 'TEMPORARY CONTENT', 'donate', 5);
}

function migrate_promotions() {
  global $wpdb;

  $wpdb->query("UPDATE $wpdb->posts SET post_type='promo',post_name='certified' where post_name='promotion'");
}

function remove_unecessary_pages() {
  $posts = get_posts("post_type=page&name=gifts");
  if ($posts) {
    $post = $posts[0];
    wp_delete_post($post->ID);
  }
  $posts = get_posts("post_type=page&name=success-stories");
  if ($posts) {
    $post = $posts[0];
    wp_delete_post($post->ID);
  }
  $posts = get_posts("post_type=page&name=community");
  if ($posts) {
    $post = $posts[0];
    wp_delete_post($post->ID);
  }
}

function default_insert_featuredContent(){
  global $wpdb;

  $wpdb->query(" DELETE FROM `featuredContent` ");
  $wpdb->query("
INSERT INTO `featuredContent` (`ID`, `imageUrl`, `title`, `content`, `css`, `parent`, `status`) VALUES
(1, '/wp-content/images/home/tb-robert.jpg', 'Trailblazers', '<a class=\\\"button green-button\\\" href=\\\"http://trailblazer.charity.seeyourimpact.com/2010/06/18/one-happy-boy/\\\">Read the Story</a>', 'margin-top:410px; margin-left: 550px;', 0, 'published'),
(3, '/wp-content/images/home/ppes-sapna.jpg', 'Pardada Pardadi', '<a class=\\\"button green-button\\\" href=\\\"http://charity.seeyourimpact.com/give/#gift=141\\\">Learn More</a>', 'margin-top:410px; margin-left: 570px;', 0, 'published'),
(4, '/wp-content/images/home/lw-sisupan.jpg', 'Lao Water', '<a class=\\\"button green-button\\\" href=\\\"http://charity.seeyourimpact.com/give/#gift=133\\\">Learn More</a>\r\n', 'margin-top:410px; margin-left: 570px;', 0, 'published'),
(5, '/wp-content/images/home/vbp.jpg', 'Village Bicycle Project', '<a class=\\\"button green-button\\\" href=\\\"http://charity.seeyourimpact.com/give/#gift=155\\\">Learn More</a>', 'margin-top:410px; margin-left: 570px;\r\n', 0, 'published');
");
}

function rename_updates_to_stories() {
  global $wpdb;
  global $blog_id;

  if ($blog_id == 1)
    $wpdb->query("update $wpdb->posts SET post_name='blog' WHERE post_name='updates'");
  else
    $wpdb->query("update $wpdb->posts SET post_name='stories',post_title='All Stories' WHERE post_name='updates'");
}

function update_page_structure() {
  global $user_id;
  global $blog_id;
  global $wpdb;

  if ($blog_id == 1) {
    db_new_page(1, $user_id, "Give Now", '', 'give');
    db_new_page(1, $user_id, "How to Get Involved", '', 'how-to');
    $wpdb->query("update $wpdb->posts SET post_parent=2 where post_name in ('faq','jobs','idea')");
  } else {
    db_new_page($blog_id, $user_id, get_bloginfo('name'), '<h1>' . get_bloginfo('name') . '</h1><p></p>', 'header', 0, 1, 'promo');
    $wpdb->query("UPDATE $wpdb->posts SET post_type='promo',post_name='cause' where post_name='home'");

    $new_home = db_new_page($blog_id, $user_id, get_bloginfo('name'), '', 'home');
    $wpdb->query("update $wpdb->options SET option_value=%d WHERE option_name='page_on_front'", $new_home);
  }

}

function create_donation_stories(){
  global $wpdb;


  $wpdb->query(
  "
  CREATE TABLE IF NOT EXISTS
  donationStory
  (
  blog_id INT(10) UNSIGNED NOT NULL,
  post_id INT(10) UNSIGNED NOT NULL,
  gift_id INT(10) UNSIGNED NOT NULL,
  post_title TEXT NOT NULL,
  post_excerpt TEXT NOT NULL,
  post_name VARCHAR(255) NOT NULL,
  post_image VARCHAR(255) NOT NULL,
  post_status VARCHAR(20) NOT NULL,
  guid VARCHAR(255) NOT NULL,
  PRIMARY KEY  (`blog_id`,`post_id`)
  )
  "
  );
}

function copy_post_to_donation_stories(){
  global $wpdb;

  $blog_ids = $wpdb->get_col("
  SELECT blog_id FROM wp_blogs
  ");


  if(is_array($blog_ids) && count($blog_ids)>0){
    $wpdb->query("TRUNCATE donationStory; ");
    foreach($blog_ids as $blog_id){

    if($blog_id > 1)
    $posts = $wpdb->get_results(
    $wpdb->prepare(
    "
    SELECT ID, post_title, post_excerpt, post_name, post_content,
    post_status, guid
    FROM wp_%d_posts
    WHERE post_type = 'post' AND post_status = 'publish' ORDER BY ID

    ", $blog_id
    ), ARRAY_A
    );
    if(is_array($posts) && count($posts)>0)
    foreach($posts as $post){
      insert_donation_story($blog_id, $post);
    }

    }
  }
}

function alter_donation_story(){
  global $wpdb;

  $wpdb->query("ALTER TABLE donationStory "
  ." ADD COLUMN post_modified DATETIME NOT NULL, "
  ." ADD COLUMN featured TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 ");

  $blog_ids = $wpdb->get_col("SELECT blog_id FROM wp_blogs");

  foreach($blog_ids as $blog_id){
    $wpdb->query(
    $wpdb->prepare("UPDATE donationStory ds, wp_%d_posts p "
    ." SET ds.post_modified = p.post_modified "
    ." WHERE ds.post_id = p.ID AND ds.blog_id = %d ",$blog_id,$blog_id));
  }
}

function delete_donation_story($blog_id, $post_id){
  global $wpdb;
  $sql = $wpdb->prepare(
    "DELETE FROM donationStory WHERE blog_id = %d AND post_id = %d ",
    $blog_id, $post_id);
  $wpdb->query($sql);
}

function insert_donation_story($bid, $post){
  global $wpdb;
  global $blog_id;
  //global $switched;

  $post_id = $post['ID'];

  switch_to_blog($bid);
  $tags = wp_get_post_tags($post_id);
  if ($tags) {
    foreach($tags as $tag){
      if($tag->name == 'featured'){
        $is_featured = true;
      } else if($tag->name == 'story'){
        $is_story = true;
      } else if($tag->name == 'archived'){
        $is_archived = true;
      }
    }
  }

  $cats = get_the_category($post_id);
  if ($cats) {
    foreach($cats as $cat){
      if($cat->slug == 'featured'){
        $is_featured = true;
      } else if($cat->slug == 'story' || $cat->slug == 'impact-stories'){
        $is_story = true;
      } else if($cat->slug == 'archived'){
        $is_archived = true;
      }
    }
  }

  //echo '<br/>inserting donationStory for post #'.$post_id.' on blog#'.$bid;
  // if not a published story, or if it is archived, delete any existing and dont insert
  if($post['post_status'] != 'publish' || !$is_story || $is_archived){
    delete_donation_story($bid, $post_id);
    return;
  }

  $post['gift_id'] = $wpdb->get_var(
  $wpdb->prepare(
    "SELECT giftID FROM donationGifts "
      ."WHERE blog_id = %d AND story = %d LIMIT 1",$bid,$post_id));

  $image_id = get_post_thumbnail_id($post['ID']);
  $post['post_image'] = '';
  if ($image_id > 0) {
  $image_url = wp_get_attachment_image_src($image_id, 'large');
  if (!empty($image_url[0]))
    $post['post_image'] = $image_url[0];
  }
    
  // DEBUG
  //echo $post['post_image']; die;  

  //echo '<pre>';
  //var_dump($x);
  //echo '<br/>-'.$thumbnail_html;
  //echo '<br/>'.$blog_id.'-'.$thumbnail_id.'<a href="'.$post['guid'].'">view</a>'.'-'.$bid.'-'.$post['ID'].'-'.$post['post_image'];
  //echo '<pre>'.$sql.'</pre><br/>';
  //echo '<pre>'.$post['post_image'].'</pre><br/>';
  //echo '</pre>';

  if($post['post_excerpt'] == '') {
    $postContent = preg_replace('/<img[^>]+>/i','',$post['post_content']);
    $postContent = preg_replace("/\[caption.*\[\/caption\]/", '', $postContent);
    $post['post_excerpt'] = trim(getExcerpt(strip_shortcodes(strip_tags($postContent))));
  }

  $sql = $wpdb->prepare("
    INSERT INTO donationStory
    (blog_id, post_id, gift_id, post_title,
    post_excerpt, post_name, post_image,
    post_status, guid, post_modified, featured)
    VALUES(%d, %d, %d, %s,
    %s, %s, %s, %s, %s, %s, %d)
    ON DUPLICATE KEY UPDATE
    post_title = %s, post_excerpt = %s,
    post_name = %s, post_image = %s, guid = %s,
    post_modified = %s, featured = %d
    ",$bid,$post_id, $post['gift_id'],
    $post['post_title'], $post['post_excerpt'],
    $post['post_name'], $post['post_image'],
    $post['post_status'], $post['guid'],
    $post['post_modified'], $is_featured,
    $post['post_title'], $post['post_excerpt'],
    $post['post_name'], $post['post_image'],
    $post['guid'],
    $post['post_modified'], $is_featured
    );
//  echo '<br/>'.$sql;
  $wpdb->query($sql);// or $wpdb->print_error();

  restore_current_blog();
}

function set_default_gift_tags(){
  global $blog_id;
  global $wpdb;

  $region = $wpdb->get_var(
    $wpdb->prepare("SELECT regions FROM blogCharityInfo WHERE blog_id = %d", $blog_id));
  $cause = $wpdb->get_var(
    $wpdb->prepare("SELECT causes FROM blogCharityInfo WHERE blog_id = %d", $blog_id));

  $tags = array();

  if($region != '')
    $tags[] = $region;

  if($cause != '')
    $tags[] = $cause;

  $tags = implode(",", $tags);

  if($tags != ''){

  $gifts = $wpdb->get_col(
    $wpdb->prepare("SELECT id FROM gift WHERE blog_id = %d", $blog_id));

  if(is_array($gifts))
  foreach($gifts as $gift){
    $wpdb->query(
      $wpdb->prepare("UPDATE gift SET tags = %s WHERE id = %d",$tags,$gift)
    );
  }


  }

}

function fix_donation_story_guid(){
  global $wpdb;
  $wpdb->query("ALTER TABLE donationStory "
    ." MODIFY COLUMN `guid` VARCHAR(255) NOT NULL");
}

function fix_front_pages() {
  global $wpdb;
  $wpdb->query("update $wpdb->options join $wpdb->posts p set option_value=p.id where option_name='page_on_front' and p.post_name='home'");
}

function create_default_articles(){
  global $user_id;
  global $blog_id;

  $c_id = db_new_page(1, $user_id, "Sector", '', 'sector',0,0,'article');
  $p_id = db_new_page(1, $user_id, "People", '', 'people',0,0,'article');
  $r_id = db_new_page(1, $user_id, "Region", '', 'region',0,0,'article');

  db_new_page(1, $user_id, "Disease", '', 'disease',0,$c_id,'article');
  db_new_page(1, $user_id, "Education", '', 'education',0,$c_id,'article');
  db_new_page(1, $user_id, "Clean Water", '', 'clean-water',0,$c_id,'article');
  db_new_page(1, $user_id, "Jobs", '', 'jobs',0,$c_id,'article');
  db_new_page(1, $user_id, "Disabilities", '', 'disabilities',0,$c_id,'article');
  db_new_page(1, $user_id, "Hunger", '', 'hunger',0,$c_id,'article');

  db_new_page(1, $user_id, "Africa", '', 'africa',0,$r_id,'article');
  db_new_page(1, $user_id, "Asia", '', 'asia',0,$r_id,'article');
  db_new_page(1, $user_id, "India", '', 'india',0,$r_id,'article');
  db_new_page(1, $user_id, "Americas", '', 'americas',0,$r_id,'article');

}
function create_default_articles2(){
  global $user_id;
  global $wpdb;

  $p_id = $wpdb->get_var("select ID from $wpdb->posts WHERE post_name='people'");

  db_new_page(1, $user_id, "Newborns", '', 'newborns',0,$p_id,'article');
  db_new_page(1, $user_id, "Orphans", '', 'orphans',0,$p_id,'article');
  db_new_page(1, $user_id, "Families", '', 'families',0,$p_id,'article');
  db_new_page(1, $user_id, "Children", '', 'children',0,$p_id,'article');
  db_new_page(1, $user_id, "Girls and Women", '', 'girls',0,$p_id,'article');
}

function create_tab_pages() {
  global $user_id;
  global $wpdb;

  $wpdb->query("update $wpdb->posts SET post_name='team' WHERE post_name='about'");
  $team_id = $wpdb->get_var("select ID from $wpdb->posts WHERE post_name='team'");

  $about_id = db_new_page(1, $user_id, "How it Works", '', 'about', 1, $about_id);
  db_new_page(1, $user_id, "Our Partner Charities", '', 'partners', 15, $about_id);
  db_new_page(1, $user_id, "Contact Us and Press", '', 'contact', 20, $about_id);
  $wpdb->query("update $wpdb->posts SET post_parent=$about_id WHERE post_parent=$team_id");
  $wpdb->query("update $wpdb->posts SET post_parent=$about_id,post_title='Meet the team' WHERE post_name='team'");

  $howto_id =  $wpdb->get_var("select ID from $wpdb->posts WHERE post_name='how-to'");
  db_new_page(1, $user_id, 'Volunteer', '', 'volunteer', 10, $howto_id);

}

function update_gift_tags_default_final(){
  global $wpdb;

  $wpdb->query("UPDATE `gift` SET `tags` = 'clean-water,family,asia' WHERE  `gift`.`id` = 133;");
  $wpdb->query("UPDATE `gift` SET `tags` = 'clean-water,family,asia' WHERE  `gift`.`id` = 126;");
  $wpdb->query("UPDATE `gift` SET `tags` = ',family,india' WHERE  `gift`.`id` = 99;");
  $wpdb->query("UPDATE `gift` SET `tags` = 'clean-water,family,asia' WHERE  `gift`.`id` = 137;");
  $wpdb->query("UPDATE `gift` SET `tags` = 'clean-water,family,asia' WHERE  `gift`.`id` = 123;");
  $wpdb->query("UPDATE `gift` SET `tags` = 'clean-water,family,asia' WHERE  `gift`.`id` = 122;");
  $wpdb->query("UPDATE `gift` SET `tags` = 'disease,children,africa' WHERE  `gift`.`id` = 145;");
  $wpdb->query("UPDATE `gift` SET `tags` = 'disease,children,africa' WHERE  `gift`.`id` = 146;");
  $wpdb->query("UPDATE `gift` SET `tags` = 'disease,children,africa' WHERE  `gift`.`id` = 147;");
  $wpdb->query("UPDATE `gift` SET `tags` = 'disease,newborns,asia' WHERE  `gift`.`id` = 142;");
  $wpdb->query("UPDATE `gift` SET `tags` = 'disease,newborns,asia' WHERE  `gift`.`id` = 144;");
  $wpdb->query("UPDATE `gift` SET `tags` = 'disease,newborns,asia' WHERE  `gift`.`id` = 143;");
  $wpdb->query("UPDATE `gift` SET `tags` = 'disease,girls,americas' WHERE  `gift`.`id` = 136;");
  $wpdb->query("UPDATE `gift` SET `tags` = 'disease,girls,americas' WHERE  `gift`.`id` = 135;");
  $wpdb->query("UPDATE `gift` SET `tags` = 'disabilities,,india' WHERE  `gift`.`id` = 87;");
  $wpdb->query("UPDATE `gift` SET `tags` = 'disabilities,children,india' WHERE  `gift`.`id` = 88;");
  $wpdb->query("UPDATE `gift` SET `tags` = 'disabilities,,india' WHERE  `gift`.`id` = 89;");
  $wpdb->query("UPDATE `gift` SET `tags` = 'disabilities,,india' WHERE  `gift`.`id` = 90;");
  $wpdb->query("UPDATE `gift` SET `tags` = 'disease,,africa' WHERE  `gift`.`id` = 104;");
  $wpdb->query("UPDATE `gift` SET `tags` = 'disease,children,africa' WHERE  `gift`.`id` = 129;");
  $wpdb->query("UPDATE `gift` SET `tags` = 'disease,children,africa' WHERE  `gift`.`id` = 131;");
  $wpdb->query("UPDATE `gift` SET `tags` = 'education,children,americas' WHERE  `gift`.`id` = 134;");
  $wpdb->query("UPDATE `gift` SET `tags` = 'education,children,india' WHERE  `gift`.`id` = 98;");
  $wpdb->query("UPDATE `gift` SET `tags` = 'education,children,india' WHERE  `gift`.`id` = 97;");
  $wpdb->query("UPDATE `gift` SET `tags` = 'education,children,india' WHERE  `gift`.`id` = 100;");
  $wpdb->query("UPDATE `gift` SET `tags` = 'education,children,india' WHERE  `gift`.`id` = 161;");
  $wpdb->query("UPDATE `gift` SET `tags` = 'education,children,india' WHERE  `gift`.`id` = 101;");
  $wpdb->query("UPDATE `gift` SET `tags` = 'education,orphans,india' WHERE  `gift`.`id` = 103;");
  $wpdb->query("UPDATE `gift` SET `tags` = 'education,orphans,india' WHERE  `gift`.`id` = 102;");
  $wpdb->query("UPDATE `gift` SET `tags` = 'education,orphans,africa' WHERE  `gift`.`id` = 110;");
  $wpdb->query("UPDATE `gift` SET `tags` = 'education,orphans,africa' WHERE  `gift`.`id` = 113;");
  $wpdb->query("UPDATE `gift` SET `tags` = 'education,orphans,africa' WHERE  `gift`.`id` = 109;");
  $wpdb->query("UPDATE `gift` SET `tags` = 'education,girls,africa' WHERE  `gift`.`id` = 128;");
  $wpdb->query("UPDATE `gift` SET `tags` = 'education,girls,africa' WHERE  `gift`.`id` = 127;");
  $wpdb->query("UPDATE `gift` SET `tags` = 'education,girls,india' WHERE  `gift`.`id` = 141;");
  $wpdb->query("UPDATE `gift` SET `tags` = 'education,girls,india' WHERE  `gift`.`id` = 125;");
  $wpdb->query("UPDATE `gift` SET `tags` = 'education,girls,india' WHERE  `gift`.`id` = 124;");
  $wpdb->query("UPDATE `gift` SET `tags` = 'education, disabilities,children,india' WHERE  `gift`.`id` = 86;");
  $wpdb->query("UPDATE `gift` SET `tags` = 'education, disabilities,children,india' WHERE  `gift`.`id` = 84;");
  $wpdb->query("UPDATE `gift` SET `tags` = 'education, jobs,,india' WHERE  `gift`.`id` = 95;");
  $wpdb->query("UPDATE `gift` SET `tags` = 'education, jobs,,india' WHERE  `gift`.`id` = 96;");
  $wpdb->query("UPDATE `gift` SET `tags` = 'education, jobs,,africa' WHERE  `gift`.`id` = 155;");

}

function add_featured_gift_sets() {
  db_insert_row('featuredGiftSet', array(
    'imageUrl' => 'images/sets/girls-education.jpg',
    'title' => 'Send a girl to school',
    'request' => 'tags=girls,education',
    'status' => 'published'
  ));
  db_insert_row('featuredGiftSet', array(
    'imageUrl' => 'images/sets/children-hunger.jpg',
    'title' => 'Save a child from hunger',
    'request' => 'tags=hunger,children',
    'status' => 'published'
  ));
  db_insert_row('featuredGiftSet', array(
    'imageUrl' => 'images/sets/ten.jpg',
    'title' => 'Change a life for as little as $10',
    'request' => 'cost=1',
    'status' => 'published'
  ));
  db_insert_row('featuredGiftSet', array(
    'imageUrl' => 'images/sets/water.jpg',
    'title' => 'Give clean water and save a life',
    'request' => 'tags=clean-water',
    'status' => 'published'
  ));
  db_insert_row('featuredGiftSet', array(
    'imageUrl' => 'images/sets/children-diseases.jpg',
    'title' => 'Save a child from deadly malaria',
    'request' => 'tags=children,disease',
    'status' => 'published'
  ));
  db_insert_row('featuredGiftSet', array(
    'imageUrl' => 'images/sets/job-creation.jpg',
    'title' => 'Job Creation',
    'request' => 'tags=jobs',
    'status' => 'published'
  ));
}

function create_payment_page(){
   global $wpdb;
   $blog_id = $wpdb->get_var("SELECT blog_id FROM wp_blogs where blog_id = site_id");
   $user_id = $wpdb->get_var("SELECT ID FROM wp_users where user_login = 'admin'");
   $now     = date('Y-m-d H:i:s');
   $now_gmt = gmdate('Y-m-d H:i:s');
   $body    = '';
   $title   = 'How Would You Like to Donate Your Gift?';
   $slug    = 'pay';

   $wpdb->insert( $wpdb->posts, array(
        'post_author' => $user_id,
        'post_date' => $now,
        'post_date_gmt' => $now_gmt,
        'post_content' => stripslashes( repl($blog_id, $body) ),
        'post_excerpt' => '',
        'post_title' => stripslashes( repl($blog_id, $title)),
        'post_category' => 0,
        'post_name' => $slug,
        'post_modified' => $now,
        'post_modified_gmt' => $now_gmt,
        'post_status' => 'publish',
        'post_type' => 'page',
        'to_ping' => '',
        'pinged' => '',
        'post_content_filtered' => ''
   ) );
}

function import_gifts() {
  global $wpdb, $blog_id, $user_id;

  if ($blog_id == 1) {
    db_add_column('gift.title', "varchar(255) DEFAULT NULL AFTER pluralName");
    db_add_column('gift.excerpt', "varchar(255) DEFAULT NULL AFTER title");
    db_add_column('gift.post_id', "int(10)");
    return;
  }

  $sql = $wpdb->prepare("select * from gift g where g.blog_id=%d and g.active=1", $blog_id);
  foreach ($wpdb->get_results($sql) as $gift) {
    $id = $gift->id;
    $name = $gift->displayName;
    $title = "Give $name";
    $desc = $gift->description;
    $slug = sanitize_title_with_dashes($name);

    $gift_id = db_new_page(1, $user_id, $title, "<p>$desc</p>", $slug,0,0,'gift', $desc);
    add_post_meta($gift_id, 'gift_id', $id, true);
    $wpdb->query($wpdb->prepare("update gift g set title=%s,post_id=%d where id=%d", $title, $gift_id, $id));
  }
}

function create_payment_page_sidebar(){
   global $wpdb;
   $blog_id = $wpdb->get_var("SELECT blog_id FROM wp_blogs where blog_id = site_id");
   $user_id = $wpdb->get_var("SELECT ID FROM wp_users where user_login = 'admin'");
   $now     = date('Y-m-d H:i:s');
   $now_gmt = gmdate('Y-m-d H:i:s');
   $body    = '<p>100% of your donation goes to the gift you\'ve selected</p>
<p>In about 2 weeks, you\'ll receive the photo and story you\'ve made possible</p>
<p>All donations are fully tax-deductible in the US</p>
<p><em>Want to give more than one gift?</em></p>
<p>
You\'ve joined SeeYourImpact.org Beta to make change real for someone in need.
Today, our site is limited to giving one item, but multiple quantities of the same item.
To give another item, please complete your gift transaction and start again.
</p>
<p>
Any other suggestions for improving SeeYourImpact.org Beta? <a style="text-decoration:underline" href="mailto:info@seeyourimpact.org">Email us</a>!
</p>';
   $title   = 'Thank You!';
   $slug    = 'pay-sidebar';

   $wpdb->insert( $wpdb->posts, array(
        'post_author' => $user_id,
        'post_date' => $now,
        'post_date_gmt' => $now_gmt,
        'post_content' => stripslashes( repl($blog_id, $body) ),
        'post_excerpt' => '',
        'post_title' => stripslashes( repl($blog_id, $title)),
        'post_category' => 0,
        'post_name' => $slug,
        'post_modified' => $now,
        'post_modified_gmt' => $now_gmt,
        'post_status' => 'publish',
        'post_type' => 'promo',
        'to_ping' => '',
        'pinged' => '',
        'post_content_filtered' => ''
   ) );
}

function create_impact_page() {
  global $user_ID;

  db_new_page(1, $user_ID, 'Read Life-Changing Stories', '', 'stories', 0);
}

function create_payment_page_tipnote(){
   global $wpdb;
   $blog_id = $wpdb->get_var("SELECT blog_id FROM wp_blogs where blog_id = site_id");
   $user_id = $wpdb->get_var("SELECT ID FROM wp_users where user_login = 'admin'");
   $now     = date('Y-m-d H:i:s');
   $now_gmt = gmdate('Y-m-d H:i:s');
   $body    = 'Help us cover the cost of delivering photos and stories. Your contribution to SeeYourImpact.org is tax deductible in the US.';
   $title   = 'Do You Like The SeeYourImpact Idea?';
   $slug    = 'pay-tipnote';

   $wpdb->insert( $wpdb->posts, array(
        'post_author' => $user_id,
        'post_date' => $now,
        'post_date_gmt' => $now_gmt,
        'post_content' => stripslashes( repl($blog_id, $body) ),
        'post_excerpt' => '',
        'post_title' => stripslashes( repl($blog_id, $title)),
        'post_category' => 0,
        'post_name' => $slug,
        'post_modified' => $now,
        'post_modified_gmt' => $now_gmt,
        'post_status' => 'publish',
        'post_type' => 'promo',
        'to_ping' => '',
        'pinged' => '',
        'post_content_filtered' => ''
   ) );
}

function insert_default_featured_posts(){
  global $wpdb;
  $stories = array(
'ppes-96',
'jyoti-386',
'jyoti-449',
'ppes-121',
'doh-85',
'carlacristina-145',
'littledrops-85',
'padma-545',
'laowater-127',
'hfc-81',
'isha-200',
'padma-406',
'trailblazer-92',
'profamilia-37',
'doh-218',
'trf-73',
'laowater-123',
'vbp-43',
'bpa-447',
'bpa-830'
  );

  $posts = array(
//    1524,1372,1482,1418,1499,1424
  );

  //default featured stories
  $main_domain =
    $wpdb->get_var("SELECT domain FROM wp_blogs WHERE blog_id = 1");

  foreach($stories as $story){
    $s = explode("-",$story);
    $domain = $s[0].'.'.$main_domain;
    $story_id = $s[1];
    $blog_id = $wpdb->get_var(
      $wpdb->prepare("SELECT blog_id FROM wp_blogs WHERE domain = %s",$domain));


    //echo '<br/>featuring story#'.$story_id.' on blog#'.$blog_id;
    if($blog_id!=NULL){
      switch_to_blog($blog_id);
      wp_set_post_tags($story_id,'featured',true);
      restore_current_blog();
      $wpdb->query(
        $wpdb->prepare(
            "UPDATE donationStory SET featured = 1 "
              ."WHERE blog_id = %d AND post_id = %d ", $blog_id, $story_id
          )
        );
    }
  }

  //default featured posts
  switch_to_blog(1);
  foreach($posts as $post){
    wp_set_post_tags($post,'featured',true);
  }
  restore_current_blog();
}

function insert_story_tag(){
  global $blog_id;
  global $wpdb;

  $story_ids = $wpdb->get_col(
    $wpdb->prepare(
      "SELECT story FROM donationGifts WHERE story>0 AND blog_id = %d",$blog_id
    )
  );

  foreach($story_ids as $story_id){
    wp_set_post_tags($story_id,'story',true);
  }
}

function reset_donation_story(){
  insert_default_featured_posts();
  copy_post_to_donation_stories();
}

function reformat_charity_text() {
  global $wpdb;

  $text = $wpdb->get_var("select post_content from $wpdb->posts where post_type='promo' and post_name='cause'");

  $text = preg_replace('/The Challenge:\s*/', '<li>', $text);
  $text = preg_replace('/The Problem:\s*/', '<li>', $text);
  $text = preg_replace('/The Solution:\s*/', '</li><li>', $text);
  $text = preg_replace('/Success Metrics:\s*/', '</li><li>', $text);
  $text = preg_replace('/Success Statistics:\s*/', '</li><li>', $text);
  $text = $text . '</li>';

  $wpdb->query($wpdb->prepare("update $wpdb->posts set post_content='%s' where post_type='promo' and post_name='cause'", $text));
}

function add_donor_user_id() {
  global $wpdb;

  db_add_column('donationGiver.user_id', "bigint(20)");
  db_add_column('donationStory.donor_id', "int(10)");
  db_add_column('donationStory.item_id', "int(10)");
  $wpdb->query("update donationGiver dg join wp_users wu on wu.user_email=dg.email set dg.user_id=wu.id");
  $wpdb->query("update donationStory ds join donationGifts di on di.giftID=ds.cwgift_id AND di.story=ds.post_id join donation d on di.donationID=d.donationID set ds.donor_id = d.donorID, ds.item_id=dg.ID");
}

function add_spreedly_sitewide_settings(){
  global $wpdb;

  $wpdb->query("INSERT INTO `paypal_settings`
  (`id`, `current_mode`, `type`, `business_id`, `form_action`, `return_url`,
  `cancel_return_url`, `notify_url`, `btn_image`, `pixel_image`,
  `verify_url`, `provider`, `api_key`, `api_url`, `api_user`, `api_signature`) VALUES
(15, 'DOWN', 'TEST', '', '', '', '', '', '', '', '', 'spreedly', 'c04e14260cb9fa6a70f3e998e9fc524bdd614e07', '', 'SeeYourImpactSB', ''),
(16, 'DOWN', 'LIVE', '', '', '', '', '', '', '', '', 'spreedly', 'c04e14260cb9fa6a70f3e998e9fc524bdd614e07', '', 'SeeYourImpactSB', '');");
}

function add_recurly_sitewide_settings(){
  global $wpdb;

  $wpdb->query("INSERT INTO `paypal_settings`
  (`id`, `current_mode`, `type`, `business_id`, `form_action`, `return_url`,
  `cancel_return_url`, `notify_url`, `btn_image`, `pixel_image`,
  `verify_url`, `provider`, `api_key`, `api_url`, `api_user`, `api_signature`) VALUES
(17, 'DOWN', 'TEST', 'seeyourimpact-test', '', '', '', '', '', '', '', 'recurly', 'ec5708483aa04df69851df0d90c3eb25', '', 'recurly@seeyourimpact.org', ''),
(18, 'DOWN', 'LIVE', 'seeyourimpact', '', '', '', '', '', '', '', 'recurly', 'ec5708483aa04df69851df0d90c3eb25', '', 'recurly@seeyourimpact.org', '');");
}

function update_txnid_forty_varchar(){
  global $wpdb;
  $wpdb->query("ALTER TABLE payment MODIFY txnID VARCHAR(40) NOT NULL ");
}

function add_fbconnect_publish_template(){
  global $wpdb;
  $wpdb->query("UPDATE `EE_EMAIL_TEMPLATE`
SET MAIL_CONTENT = 'I just donated at SeeYourImpact. Together we can make a difference - http://www.seeyourimpact.org'
WHERE ID =123");
  $wpdb->query("UPDATE `EE_EMAIL_TEMPLATE`
SET MAIL_CONTENT = 'What an inspiring story: \$POST_LINK. Together we can make a difference - http://www.seeyourimpact.org'
WHERE ID =124");
  $wpdb->query("UPDATE `EE_EMAIL_TEMPLATE`
SET MAIL_CONTENT = 'I just donated at SeeYourImpact. Together we can make a difference - http://www.seeyourimpact.org'
WHERE ID =125");
  $wpdb->query("UPDATE `EE_EMAIL_TEMPLATE`
SET MAIL_CONTENT = 'What an inspiring story: \$POST_LINK. Together we can make a difference - http://www.seeyourimpact.org'
WHERE ID =126");

}

function untest_donations($ids){
  global $wpdb;
  if(is_array($ids) && count($ids)>0){
    $sql = $wpdb->prepare("UPDATE donation SET test=0 "
      ."WHERE donationID IN (".implode(",",$ids).")");
    $wpdb->query($sql);
  }
}

function untest_donations_091410(){
  untest_donations(
    array("771","760","757","755","756","769","770")
  );
}

function create_donorUsername_table(){
  global $wpdb;
  $wpdb->query("CREATE TABLE IF NOT EXISTS
  donorUsername
  (id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  first_name VARCHAR(50),
  counter INT(10) UNSIGNED NOT NULL,
  PRIMARY KEY(`id`))");
}

function fix_sendUpdate_after_august2010(){
  global $wpdb;

  $dd_ids = $wpdb->get_col("SELECT DISTINCT dd.ID FROM donationGiver dd JOIN donation d "
    ."ON d.donorID = dd.ID WHERE d.donationID>629");

  foreach($dd_ids as $dd_id){
    $wpdb->query(
      $wpdb->prepare("UPDATE donationGiver SET sendUpdates = 1 WHERE ID = %d",
        $dd_id
      )
    );
  }
  
}

function create_cart_structures(){
  global $wpdb;

  $wpdb->query("CREATE TABLE IF NOT EXISTS
  cart (id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  lastUpdated DATETIME NOT NULL,
  userID INT(10) UNSIGNED NOT NULL,
  paymentID INT(10) UNSIGNED NOT NULL,
  type VARCHAR(20) NOT NULL,
  status VARCHAR(20) NOT NULL,
  PRIMARY KEY(id)
  )");
  
  $wpdb->query("CREATE TABLE IF NOT EXISTS
  cartItem (cartID INT(10) UNSIGNED NOT NULL,
  giftID INT(10) UNSIGNED NOT NULL,
  price DOUBLE NOT NULL,
  quantity SMALLINT UNSIGNED NOT NULL,
  PRIMARY KEY(cartID,giftID)
  )");
}

function update_cartitem_pk(){
  global $wpdb;
  $wpdb->query("ALTER TABLE cartItem
  DROP PRIMARY KEY,
  ADD COLUMN id INT(10) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST,
  ADD COLUMN ref VARCHAR(20)
  ");
}
function create_cart_page(){
  db_new_page(1, 1, 'Your Donation Cart', '', 'cart', 0, 0, 'page', '', true);
}

function add_cart_data() {
  db_add_column("cart.data", "TEXT");
}

function create_cartitemdetails(){
  global $wpdb;

  $wpdb->query("CREATE TABLE IF NOT EXISTS
  cartItemDetails (id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  cartItemID INT(10) UNSIGNED NOT NULL,
  recipientID INT(10) UNSIGNED NOT NULL,
  message TEXT,
  PRIMARY KEY(id)
  )");

}

function set_payments_email() {
  update_site_option( 'payments_email', 'debug@seeyourimpact.org' /*'payments-received@seeyourimpact.org' */ );
  // for now devs can update:
  // update wp_sitemeta set meta_value='{your address}' where meta_key='payments_email'
  // but this will go away - add a line in config.txt
}

function update_thanks_page(){
  db_new_page(1, 1, 'Please take a moment to tell your friends about SeeYourImpact', 'Need text', 'thanks-share', 0, 0, 'promo', '', true);
}

function track_error_mails() {
  db_add_column('notificationHistory.error', "TEXT");
}

function create_cart_debug() {
  global $wpdb;
  $wpdb->query("CREATE TABLE IF NOT EXISTS
  cartDebug (id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  cartID INT(10) UNSIGNED NOT NULL,
  recorded DATETIME, message TEXT, note TEXT, PRIMARY KEY(id))");

}

function fix_zero_total_donation() {
  global $wpdb;
  $rows = $wpdb->get_results("
  SELECT d.donationID as dID,
  d.donationAmount_Total as dTotal,
  d.tip as dTip,
  SUM(dg.amount) as dgTotal,
  COUNT(dg.ID) as dgCount
  FROM donation d, donationGifts dg
  WHERE d.donationID = dg.donationID
  AND d.donationAmount_Total <= 0
  AND d.donationID >= 957
  GROUP BY d.donationID
  ",ARRAY_A);



  if (is_array($rows)) {
    foreach ($rows as $row) {
      if ($row['dgTotal'] > 0) {

        //echo '<pre>'.print_r($row,true).'</pre>';

        $sql = $wpdb->prepare("
        UPDATE donation d
        SET d.donationAmount_Total = %f
        WHERE d.donationID = %d
        ", $row['dgTotal'], $row['dID']);

        //echo '<pre>'.$sql.'</pre>';
        $wpdb->query($sql);

        $sql = $wpdb->prepare("
        UPDATE donationGifts dg
        SET dg.tip = (%f * dg.amount)/%f
        WHERE dg.donationID = %d
        ", $row['dTip'], $row['dgTotal'], $row['dID']);

        //echo '<pre>'.$sql.'</pre>';
        $wpdb->query($sql);

      }
    }
  }
}

function add_donation_campaigns() {
  db_add_column('donationGifts.event_id', "BIGINT UNSIGNED DEFAULT 0");
  db_add_column('cartItem.event_id', "BIGINT UNSIGNED DEFAULT 0");
}

function create_signin_promos(){
  //general message
  $title = 'Sign in to your account';
  $body = 'Use SeeYourImpact account to manage, track, and share your donations, more ways to share and see your impact! Please take a moment to provide your information below: ';
  $slug = "signin-message";
  db_new_page(1, 1, $title, $body, $slug, 0, 0, 'promo', '', true);

  //when user select "not now"
  $title = 'It only takes a minute or two to create an account!';
  $body = 'Signing in will help us assisting you better in your process. Your information will remain confidential and protected. Hopefully you can reconsider and connect soon!';
  $slug = "signin-notnow";
  db_new_page(1, 1, $title, $body, $slug, 0, 0, 'promo', '', true);
}

function add_cart_test() {
  global $wpdb;
  $wpdb->query("ALTER TABLE cart ADD COLUMN test TINYINT(1) UNSIGNED NOT NULL DEFAULT 0;");
}

function create_facebook_promos() {

  //Will be displayed above the facebook connect when user is NOT connected
  $title = 'Connect with us on Facebook!';
  $body = 'One click to make greater impact on your social network.';
  $slug = "facebook-connect-info";
  db_new_page(1, 1, $title, $body, $slug, 0, 0, 'promo', '', true);

  //Will be displayed next to the option to publish stories on Facebook
  $title = 'Share the impact on Facebook!';
  $body = 'Yes! Please publish my stories on Facebook.';
  $slug = "facebook-publish-info";
  db_new_page(1, 1, $title, $body, $slug, 0, 0, 'promo', '', true);

}

function add_gift_stats2() {
  global $wpdb;
  $DB_NAME = DB_NAME;

  $wpdb->query("CREATE TABLE `$DB_NAME`.`views` (
    `id` int(10) unsigned NOT NULL,
    `page` varchar(30) NOT NULL, 
    `date` date NOT NULL,
    `count` bigint unsigned default 0,
    UNIQUE KEY `entry` (`id`,`page`,`date`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8;"); 
}

function add_gift_stats() {
  db_add_table("gift_stats", array(
    'id' => "int(10) unsigned NOT NULL auto_increment /*PRIMARYKEY*/",
    'lastUpdate' => "datetime NOT NULL default '0000-00-00 00:00:00'",
    'donated_2wk' => "int unsigned default 0",
    'donated_4wk' => "int unsigned default 0",
    'views' => "bigint unsigned default 0",
    'sales' => "int unsigned default 0"
  ));
}

function new_profile_promo() {
  db_new_page(1, 1, 'This is your impact page!', 'Here is where you can...', 'my-profile', 0, 0, 'promo', '', true);
}

function new_signin() {
  global $wpdb;

  db_new_page(1, 1, 'Sign-in', '<img class="alignright size-medium wp-image-2615" title="Sign in" src="http://dev1.seeyourimpact.com/wp-content/images/kidsu.jpg" alt="" width="240" height="160" style="padding-left: 30px;" /></a>

Please sign in to continue', 'signin');
  db_new_page(1, 1, '(Sign-in sidebar)', 'Use your SeeYourImpact.org account to track, manage, and share your donations.', 0,0,'promo');

  $wpdb->query($sql = $wpdb->prepare(
    "UPDATE wp_1_posts SET post_content=%s WHERE post_name=%s",
    "It's the easiest way to sign in!  Just one click to connect with your Facebook account.", "facebook-connect-info"));
}

function add_dg_main_field() {
  global $wpdb;
  
  $wpdb->query("ALTER TABLE donationGiver ADD COLUMN main TINYINT(1) UNSIGNED NOT NULL DEFAULT 0");

}

function add_cash_gift() {
  global $wpdb;

  $wpdb->query("
    INSERT INTO gift
    (ID, displayName, title, excerpt, description, unitAmount, 
      varAmount, blog_id, unitsWanted, active)
    VALUES (50, 'a donation to the SeeYourImpact fund', 'we''ll allocate on your behalf!', 
      'We''ll allocate the donation on your behalf - all you need to do is sit back and enjoy the stories you''ve created!', 'GIFT description', 15, 1, 1, 10000, 1)
  ");
}

function add_give_any_accounts() {
  global $wpdb;
  $wpdb->query("INSERT INTO donationAcctType (id,name) VALUES (7,'allocate')");
}

function add_merged_thankyou_email_default_values() {

    update_blog_option(1, 'notify_merged_thankyou', 0);//
    update_blog_option(1, 'notify_thankyou_main', '<p>Dear, #DONOR_NAME#</p>
<p>On behalf of SeeYourImpact and our charity partners, thank you!</p>

#GIFT_SECTION#
#GC_SECTION#
#TIP_SECTION#

<p>Thanks again, we are looking forward to have you meet the live you\'ve changed.</p>
<p style="text-align:right">- The SeeYourImpact Team</p>');//
    update_blog_option(1, 'notify_thankyou_giftinfo', '<p>In about a few days, our corresponding charity partners will receive your fund, and we will send you a follow up email with a photo and details of the person you helped for each gift you give.</p>');//
    update_blog_option(1, 'notify_thankyou_vargiftinfo', '<p>The following gifts are donated in the variable amount that you specified: </p>');//
    update_blog_option(1, 'notify_thankyou_tipinfo', '<p>We also recognize your generous contribution to SeeYourImpact. Thanks to you, we will be able to fund operations and develop new features on the website!</p>');//
    update_blog_option(1, 'notify_thankyou_gcinfo', '<p>We have sent the following gift certificates on your behalf:</p>');//
    update_blog_option(1, 'notify_thankyou_gift_tpl', '<table width="300" cellspacing="0" cellpadding="0" border="0"><tr style="vertical-align: top;"><td width="140">
<a href="#GIFT_URL#"><img height="90" width="120" style="float: left; margin-right: 10px; border: 5px solid #ccc;" alt="" src="http://seeyourimpact.org/wp-content/gift-images/Gift_#GIFT_ID#.jpg"></a></td><td width="150">
<p style="margin-top: 0pt; line-height: 18px; margin-right: 10px; font-size:11px;font-family:Georgia,\'Times New Roman\',Times,serif"><b>#GIFT_TITLE#</b><br/>
<span style="font-size: 11px; font-family: Georgia,\'Times New Roman\',Times,serif;"><a style="color:#656465" href="#CHARITY_LINK#">#CHARITY_NAME#</a></span><br/><span style="font-size: 11px; font-family: Georgia,\'Times New Roman\',Times,serif;">Share this on: </span><br/>
<a target="_blank" style="text-decoration: none;" href="mailto:a-friend@some-domain.com?subject=Check+this+inspiring+website&body=Please+visit+this+web+page%3A+%3Ca+href%3D%22http%3A%2F%2Fseeyourimpact.org%2Fgive%2F%23gift%3Dhome%2F#GIFT_ID#%22%25+3Ehttp%3A%2F%2Fseeyourimpact.org%2Fgive%2F%23gift%3Dhome%2F#GIFT_ID#%3C%2Fa%3EI+just+make+a+difference+through+SeeYourImpact,+you+can+too!"><img style="border: 0pt none;" alt="Email" src="#PATH_TPL#/images/email.png"></a>
<a target="_blank" style="text-decoration: none;" href="http://www.facebook.com/sharer.php?u=http%3A%2F%2Fseeyourimpact.org%2Fgive%2F%23gift%3Dhome%2F#GIFT_ID#&t=#GIFT_TITLE#"><img style="border: 0pt none;" alt="Facebook" src="#PATH_TPL#/images/facebook.png"></a>
<a target="_blank" style="text-decoration: none;" href="http://twitter.com/home?status=I+just+make+a+difference+at+SeeYourImpact+-+see%3A+http%3A%2F%2Fseeyourimpact.org%2Fgive%2F%23gift%3Dhome%2F#GIFT_ID#"><img style="border: 0pt none;" alt="Twitter" src="#PATH_TPL#/images/twitter.png"></a>
<a target="_blank" style="text-decoration: none;" href="http://www.linkedin.com/shareArticle?mini=true&url=http%3A%2F%2Fseeyourimpact.org%2Fgive%2F%23gift%3Dhome%2F#GIFT_ID#&title=#GIFT_TITLE#"><img style="border: 0pt none;" alt="LinkedIn" src="#PATH_TPL#/images/linkedin.png"></a></p></td></tr><tr><td><br></td></tr></table>');//
    update_blog_option(1, 'notify_thankyou_vargift_tpl', '<table width="300" cellspacing="0" cellpadding="0" border="0"><tr style="vertical-align: top;"><td width="140">
<a href="#GIFT_URL#"><img height="90" width="120" style="float: left; margin-right: 10px; border: 5px solid #ccc;" alt="" src="http://seeyourimpact.org/wp-content/gift-images/Gift_#GIFT_ID#.jpg"></a></td><td width="150">
<p style="margin-top: 0pt; line-height: 18px; margin-right: 10px; font-size:11px;font-family:Georgia,\'Times New Roman\',Times,serif"><b>#GIFT_TITLE# - #GIFT_PRICE#</b><br/></p></td></tr><tr><td><br></td></tr></table>');//
    update_blog_option(1, 'notify_thankyou_gc_tpl', '<table width="600" cellspacing="0" cellpadding="0" border="0"><tr style="vertical-align: top;"><td width="140">
<a href="#GIFT_URL#"><img height="90" width="120" style="float: left; margin-right: 10px; border: 5px solid #ccc;" alt="" src="http://seeyourimpact.org/wp-content/gift-images/Gift_#GIFT_ID#.jpg"></a>
</td><td width="450" style="font-size: 11px; font-family: Georgia,\'Times New Roman\',Times,serif;"><p style="margin-top: 0pt; line-height: 18px; margin-right: 10px;"><b>#GIFT_TITLE#</b><br/><a style="color:#656465" href="#CHARITY_LINK#">#CHARITY_NAME#</a><br/><br/>To: #RECIPIENT_NAME# (#RECIPIENT_EMAIL#)<br/>Message: <i>#GIFT_MESSAGE#</i></p></td></tr><tr><td><br></td></tr></table>');//
    update_blog_option(1, 'notify_thankyou_profile', '');//
    update_blog_option(1, 'notify_thankyou_contact', '<p>Questions, comments, testimonials? We want to hear back from you! Please contact us at <a href="mailto:contact@seeyourimpact.org" style="color:#656465">contact@seeyourimpact.org</a> and
follow us on <a href="http://facebook.com/SeeYourImpact" style="color:#656465">Facebook</a> and <a href="http://twitter.com/seeyourimpact" style="color:#656465">Twitter</a>.</p>');//
    update_blog_option(1, 'notify_thankyou_taxinfo', '<p>SeeYourImpact is a registered 501(c)(3) nonprofit organization. All donations are fully tax-deductible. For your convenience, here is a list of tax-deductible items on your transaction.</p>
<p>Name of donor: #DONOR_NAME#</p>
<p>Date of donation: #DONATION_DATE#</p>

#TAX_LIST#');//
    update_blog_option(1, 'notify_thankyou_subject', 'Thank You from SeeYourImpact!');//
    update_blog_option(1, 'notify_thankyou_style', 'font-size:11px;font-family:Georgia,\'Times New Roman\',Times,serif');//

}

function create_donation_gift_details() {
  global $wpdb;
  $DB_NAME = DB_NAME;
  $wpdb->query("CREATE TABLE `$DB_NAME`.`donationGiftDetails` (
    `donationGiftID` int(10) unsigned NOT NULL,
    `actingDonorID` int(10) unsigned NOT NULL,
    `receivingDonorID` int(10) unsigned NOT NULL,
    `values` text NOT NULL,
    PRIMARY KEY (`donationGiftID`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

}

function update_acct_table() {
  global $wpdb;
  db_add_column('donationAcct.dateCreated', "DATETIME NOT NULL AFTER balance");
  $wpdb->query("update donationAcct set dateCreated=dateUpdated");
}

function create_donor_info_table() {
  db_add_table("donorInfo", array(
    'donorID' => "bigint not null default 0 /*PRIMARYKEY*/",
    'lastUpdate' => "datetime NOT NULL default '0000-00-00 00:00:00'",
    'donorType' => "int unsigned default 0",
    'total2010' => "int unsigned default 0",
    'deductible2010' => "int unsigned default 0",
    'gifts2010' => "int unsigned default 0",
    'errors2010' => "int unsigned default 0",
    'isFB' => "bool default 0",
    'storiesHTML' => "TEXT"
  ));
}

function more_donor_info() {
  db_add_column('donorInfo.story_title_1', 'text');
  db_add_column('donorInfo.story_url_1', 'text');
  db_add_column('donorInfo.story_img_1', 'text');
  db_add_column('donorInfo.story_title_2', 'text');
  db_add_column('donorInfo.story_url_2', 'text');
  db_add_column('donorInfo.story_img_2', 'text');
  db_add_column('donorInfo.story_title_3', 'text');
  db_add_column('donorInfo.story_url_3', 'text');
  db_add_column('donorInfo.story_img_3', 'text');
}
function more_donor_info2() {
  global $wpdb;

  $wpdb->query("alter table donorInfo change deductible2010 deductible2010 float;");
  $wpdb->query("alter table donorInfo change total2010 total2010 float;");
}

function create_donor_info_table2() {
  db_add_column('donorInfo.user_id', "bigint not null default 0 AFTER donorID");
}
function create_donor_info_table3() {
  db_add_column('donorInfo.total2011', "float default 0 AFTER errors2010");
  db_add_column('donorInfo.deductible2011', "float default 0 AFTER total2011");
  db_add_column('donorInfo.gifts2011', "int unsigned default 0 AFTER deductible2011");
  db_add_column('donorInfo.errors2011', "int unsigned default 0 AFTER gifts2011");
}

function create_donor_info_table4() {
  db_add_column('donorInfo.tip_rate', "float default 0 AFTER isFB");
}

function add_smtp_settings() {

  update_option('smtp_host','smtp.gmail.com');
  update_option('smtp_port','465');
  update_option('smtp_ssl','ssl');
  update_option('smtp_auth','true');
  update_option('smtp_user','impact@seeyourimpact.org');
  update_option('smtp_pass','microcharity');

  update_option('test_smtp_host','smtp.gmail.com');
  update_option('test_smtp_port','465');
  update_option('test_smtp_ssl','ssl');
  update_option('test_smtp_auth','true');
  update_option('test_smtp_user','impact@seeyourimpact.org');
  update_option('test_smtp_pass','microcharity');


  update_option('mail_from','impact@seeyourimpact.org');
  update_option('mail_from_name','SeeYourImpact.org');
  update_option('mailer','smtp');
  update_option('mail_set_return_path','');

}

function create_invite_table() {

  global $wpdb;
  $wpdb->query("CREATE TABLE IF NOT EXISTS
  invite
  (id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id INT(10) UNSIGNED NOT NULL,
  email VARCHAR(50) NOT NULL,
  date_added DATETIME NOT NULL,
  date_sent DATETIME,
  status VARCHAR(10) NOT NULL,
  context VARCHAR(10) NULL,
  PRIMARY KEY(`id`))");

}

function add_invite_page_and_promos() {

  db_new_page(1,1,'Invite Ad','You can invite your friend to view and support your cause. Click the invite button to start!','invite-ad',0,0,'promo','',false);
  db_new_page(1,1,'','I need your help to make a difference!','default-invite-subject',0,0,'promo','',false);
  db_new_page(1,1,'','Hi there!

Please check this site and see the impact that I have made: http://seeyourimpact.org

I hope you will also join me to make more impact in the world!','default-invite-message',0,0,'promo','',false);

  db_new_page(1,1,'Invite your friends',"Let's get started! Add your contacts' emails and personalize your invitation message. Click invite when you are done.",'invite',0,0,'page','',false);
  db_new_page(1,1,'','Enter name and email to the textbox, one recipient per line. Example: <br/><em>John Doe johndoe@domain.com</em><br/><em>Jane Doe janedoe@domain.com</em>','recipient-list-instruction',0,0,'promo','',false);

}

function add_cloudsponge_settings() {

  update_option('cloudsponge_key','TDFA62G3TY58WQHQBLES');
  update_option('cloudsponge_pass','7xwP3XIIbzUoUX');
  
}

function add_invite_name() {

  global $wpdb;
  $wpdb->query("ALTER TABLE invite MODIFY email VARCHAR(255) NOT NULL");
  $wpdb->query("ALTER TABLE invite ADD name VARCHAR(255) NOT NULL");

}

function create_invitation_table() {

  global $wpdb;
  $wpdb->query("CREATE TABLE IF NOT EXISTS
  invitation
  (id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id INT(10) UNSIGNED NOT NULL,
  date_added DATETIME NOT NULL,
  subject TEXT,
  message TEXT,
  PRIMARY KEY(`id`))");

  $wpdb->query("ALTER TABLE invite DROP user_id");
  $wpdb->query("ALTER TABLE invite DROP date_added");
  $wpdb->query("ALTER TABLE invite MODIFY name VARCHAR(255) NOT NULL AFTER id");
  $wpdb->query("ALTER TABLE invite ADD invitation_id INT(10) UNSIGNED NOT NULL");

}

function add_invite_email_templates() {

  $pid = db_new_page(1,1,'My Profile Invite Email','Dear #INVITEE_NAME#,

Your friend, <strong>#INVITER_NAME#</strong> has been a great partner of SeeYourImpact, and he/she wishes you to get inspired by the stories from the people benefited from his/her donation. SeeYourImpact is a revolutionary way to help those in need around the world and next door. Give a gift. In about 2 weeks, we\'ll tell you exactly who you helped and how. You can check out the stories here:

<a href="#INVITE_URL#">#INVITE_URL#</a>

Here is a personal message from #INVITER_NAME#:

<span style="margin:0 10px; font-size:0.8em;"><em>#INVITER_MESSAGE#</em></span>

Please take a moment to check this out. We hope you can get inspired and make your own impact stories!

Regards,


SeeYourImpact','my-profile-invite-email',0,0,'templates','',1);

  update_post_meta($pid,'template_subject','#INVITER_NAME# invites you to see his/her impact!');
  update_post_meta($pid,'template_note','Hi there! I thought you might want to check this. I just participated in this site and it feels great to help others!');

////////////////////////////////////////////////////////////////////////////////

  $pid = db_new_page(1,1,'My Campaign Invite Email','Dear #INVITEE_NAME#,

Your friend, <strong>#INVITER_NAME#</strong> has been a great partner of SeeYourImpact, and he/she just started a new campaign called <strong>#INVITE_NAME#</strong>, to help other in needs. SeeYourImpact is a revolutionary way to help those in need around the world and next door. Give a gift. In about 2 weeks, we\'ll tell you exactly who you helped and how. You can check out the campaign made by #INVITER_NAME# here:

<a href="#INVITE_URL#">#INVITE_URL#</a>

Here is a personal message from #INVITER_NAME#:

<span style="margin:0 10px; font-size:0.8em;"><em>#INVITER_MESSAGE#</em></span>

Please take a moment to check this out. We hope you can get inspired and give your support!

Regards,


SeeYourImpact','my-campaign-invite-email',0,0,'templates','',1);

  update_post_meta($pid,'template_subject','#INVITER_NAME# invites you to support his/her cause! ');
  update_post_meta($pid,'template_note','Hi there! I thought you might want to support me on this. I just started building this movement and it feels great to help others!');

////////////////////////////////////////////////////////////////////////////////

  $pid = db_new_page(1,1,'Any Profile Invite Email','Dear #INVITEE_NAME#,

Your friend, <strong>#INVITER_NAME#</strong> just viewed a profile of one of our donor, <strong>#INVITE_NAME#</strong>, at SeeYourImpact.org, and he/she wishes you to get inspired by the stories from the people benefited from the donation. SeeYourImpact is a revolutionary way to help those in need around the world and next door. Give a gift. In about 2 weeks, we\'ll tell you exactly who you helped and how. You can check out the stories here:

<a href="#INVITE_URL#">#INVITE_URL#</a>

Here is a personal message from #INVITER_NAME#:

<span style="margin:0 10px; font-size:0.8em;"><em>#INVITER_MESSAGE#</em></span>

Please take a moment to check this out. We hope you can get inspired and make your own impact stories!

Regards,


SeeYourImpact','any-profile-invite-email',0,0,'templates','',1);

  update_post_meta($pid,'template_subject','#INVITER_NAME# invites you to get inspired by this profile!');
  update_post_meta($pid,'template_note','Hi there! I thought you might want to check this. The stories are life-changing! ');

////////////////////////////////////////////////////////////////////////////////

  $pid = db_new_page(1,1,'Any Campaign Invite Email','Dear #INVITEE_NAME#,

Your friend, <strong>#INVITER_NAME#</strong> just viewed a charity campaign at SeeYourImpact.org called <strong>#INVITE_NAME#</strong>. SeeYourImpact is a revolutionary way to help those in need around the world and next door. Give a gift. In about 2 weeks, we\'ll tell you exactly who you helped and how. You can check out more about the campaign here:

<a href="#INVITE_URL#">#INVITE_URL#</a>

Here is a personal message from #INVITER_NAME#:

<span style="margin:0 10px; font-size:0.8em;"><em>#INVITER_MESSAGE#</em></span>

Please take a moment to check this out. We hope you can get inspired and give your support!

Regards,


SeeYourImpact','any-campaign-invite-email',0,0,'templates','',1);

  update_post_meta($pid,'template_subject','#INVITER_NAME# invites you to support his/her campaign!');
  update_post_meta($pid,'template_note','Hi there! I thought you might want to support this cause. Let\'s participate together and make a difference!');

}

function fix_invite_context() {
  global $wpdb;
  $wpdb->query("ALTER TABLE invite MODIFY context VARCHAR(50) NOT NULL");
  add_invite_email_templates(); // missed quote, created error, redo insertion
}

function convert_campaign_goals() {
  global $wpdb;
  $wpdb->query("update wp_1_postmeta set meta_key='syi_goal',meta_value=meta_value*25 where meta_key='syi_lives_goal'");
}

function fix_no_story_email_notifications () {
  global $wpdb;
  $rows = $wpdb->get_results("SELECT 
dg.ID AS dgID, d.donationID AS donationID, d.donorID AS donorID, 
IF(g.towards_gift_id>0,6,2) AS mailType, 
dg.blog_id AS blogID, 
dg.story AS postID, don.email AS email
FROM donationGifts dg   
LEFT JOIN donation d ON dg.donationID = d.donationID 
LEFT JOIN notificationHistory n ON (n.donationID = d.donationID AND n.postID=dg.story AND n.mailType in (2, 6))
LEFT JOIN gift g on g.id=dg.giftID 
LEFT JOIN wp_blogs b on b.blog_id=dg.blog_id 
LEFT JOIN donationGiver don ON d.donorID = don.ID
LEFT JOIN donorInfo di on di.donorID = d.donorID
JOIN wp_usermeta um ON (don.user_id = um.user_id AND um.meta_key = 'no_story_email' AND um.meta_value = 1)
WHERE (d.donationDate >= '2011-01-01') AND n.sentDate IS NULL AND dg.story IS NOT NULL AND dg.story > 0");

  foreach($rows as $row) {
    $post = $wpdb->get_row($wpdb->prepare("SELECT * FROM wp_%d_posts WHERE ID=%d",$row->blogID,$row->postID));
 
    $sql = $wpdb->prepare("INSERT INTO notificationHistory
(mailType, donorID, donationID, postID, sentDate, success, emailTo, emailSubject, emailText)
VALUES (%d, %d, %d, %d, %s, 1, %s, %s, %s)",
$row->mailType, $row->donorID, $row->donationID, $row->postID, 
$post->post_modified,'--','--','--');

    //pre_dump( $sql );  
    $wpdb->query($sql);	
  }
}

function add_matching_to_cart() {
  db_add_column('cartItem.matchingAcct', "INT(10) DEFAULT NULL AFTER quantity");
}

function add_invite_processing_time() {
  db_add_column('invite.process_time', "INT(10) DEFAULT 0 AFTER invitation_id");  
}

function add_thanks_message() {
  $promo = <<<END
<div>
<img style="float: right; padding-left: 20px; position: relative; top: 10px;" src="http://healafrica.seeyourimpact.org/files/2011/03/Congo-Pascal-2-046-1024x768.jpg" alt="" width="160" />
  <h2>Thanks for donating!</h2>
  <div>In about 2 weeks, you'll see just how much your gift mattered. We'll email you at [update_email] with the photos and stories of the lives you changed.</div>
</div>

<h2 style="clear: both;padding-top: 20px;color: #f98f01;margin-bottom: .25em;">The revolution begins with you.</h2>
<div style="margin-bottom: 1em;  position: relative;">Spread the word through Facebook and email!<img src="/wp-content/images/changelives.png" style="position: absolute; right: 15px; bottom:-90px;"></div>
END;
  db_new_page(1, 1, 'Thank you message on profile', $promo, 'thank-you-message', 0, 0, 'promo', '', true);
}

function add_notification_blog_id() {
  db_add_column('notificationHistory.blogID', "INT(10) UNSIGNED NOT NULL DEFAULT 0 AFTER postID");  	
}

function add_thanks_invite() {
  add_thanks_message(); // do it again
  db_new_page(1, 1, 'Let your friends know what we\'re doing!', <<<END
[invite_import] [invite_message]Enter a message that will be sent to the friends you invite:[/invite_message] [invite_thanks]Thanks for spreading the word!  It's actions like these that help us get more help to those in need.
[/invite_thanks]
END
, 'invite', 0, 0, 'page', '', true);

  db_new_page(1, 1, 'Contact us on the thank you page', <<<END
<strong>Have any questions or comments?</strong> Write us at
<a href="mailto:contact@seeyourimpact.org">
<span style="text-decoration: underline;">contact@seeyourimpact.org</span>
</a>
END
, 'thanks-contact', 0, 0, 'promo', '', true);

  $pid = db_new_page(1, 1, 'Thank you invitation mail', <<<END
<table width="600" border="0" cellspacing="0" cellpadding="0">
<tbody><tr><td colspan="2" style="text-align:left;font-family:Georgia, 'Times New Roman', Times, serif"><table><tbody><tr><td style="vertical-align:top;padding-right:30px"><span style="font-size:24px;color:#F47A20"><b>Give a small gift. <br>Meet the life you change.</b></span><br>
<div>Dear #INVITEE_NAME#,<br><br>#INVITER_MESSAGE#<br><br>- #INVITER_NAME#<br></div><br><a style="background:#F47A20;color:#ffffff;border:2.5px solid white;font-size:18px;padding:7px 26px;width:120px;display:inline-block;margin:0 2px;text-align:center;vertical-align:baseline" href="http://seeyourimpact.org/give/" target="_blank">GIVE NOW</a><br>
<br>P.S. If you decide not to join us, don't worry. Your email address is private and we won't email you again.<br><br><table><tbody><tr><td><a href="http://seeyourimpact.org/give/#gift=home/219" style="color:#0077CC;text-decoration:none" target="_blank"><img src="http://seeyourimpact.org/wp-content/gift-images/t/Gift_219.jpg" alt="" style="width:160px;min-height:95px;padding: 4px; border:2px solid #dddddd; display:block; margin-bottom: 4px;"><b>Build a latrine for a family in Nepal</b></a></td><td style="width:10px">&nbsp;</td><td><a href="http://seeyourimpact.org/give/#gift=home/123" style="color:#0077CC;text-decoration:none" target="_blank"><img src="http://seeyourimpact.org/wp-content/gift-images/t/Gift_123.jpg" alt="" style="width:160px;min-height:95px;padding: 4px; border:2px solid #dddddd; display:block; margin-bottom: 4px;"><b>Give a water filter to a family in Cambodia!</b></a></td></tr></tbody></table><br>
<br>
</td><td style="vertical-align:top;width:180px;font-size:12px">Get your own story like this!<br/>
#FEATURED_POST_PICTURE#
#FEATURED_POST_CONTENT#
</td></tr></tbody></table></td></tr>
</tbody></table>
END
, 'thankyou-invite-email', 0, 0, 'templates', '', true);

  switch_to_blog(1);
  update_post_meta($pid, 'template_featured_post', '20-333', true);
  update_post_meta($pid, 'template_note', "Check out this new website! It's an amazing way to help people, anywhere in the world. You can give as little as $10, and in 2 weeks, they'll email you the photo and story of the real person you helped!", true);
  update_post_meta($pid, 'template_subject', 'Come see how #INVITER_NAME# is changing the world on SeeYourImpact.org!', true);
  restore_current_blog();
}

function remove_cc_numbers_from_cart_debug() {
  global $wpdb;
  $wpdb->query("UPDATE cartDebug SET message = CONCAT(SUBSTR(message,1,LOCATE('[cc_num] => ',message)+11), 
  SUBSTR(message,LOCATE('    [cc_type]',message))) WHERE `message` LIKE '%[cc_num] => %'");	

}

function add_blogid_to_notificationhistory() {
  global $wpdb;	
  
  $wpdb->query("UPDATE donationGifts dg
JOIN donation d ON dg.donationID=d.donationID
JOIN notificationHistory n ON (d.donationID=n.donationID AND n.postID=dg.story)
SET n.blogID = dg.blog_id 
WHERE n.mailType IN (2,6) AND (n.blogID IS NULL OR n.blogID=0)");
  
  //NOTE: there might be errors in the case similar postIDs with diff blogIDs are in the same donation 

}

function fix_syi_goal_postmeta() {
  global $wpdb;
  $wpdb->query("UPDATE wp_1_postmeta SET meta_key='syi_goal' WHERE meta_key='g'");	
}

function add_event_id_to_donation_acct() {
  global $wpdb;	
  $wpdb->query("ALTER TABLE donationAcct ADD COLUMN event_id INT(10) UNSIGNED DEFAULT 0");
}

function update_campaign_invite_template() {

  $pid = db_new_page(1, 1, 'Any Campaign Invite Email', '<table width="600" border="0" cellspacing="0" cellpadding="0"><tbody><tr><td colspan="2" style="text-align:left;font-family:Georgia, Times New Roman, Times, serif"><div style="background:#f0f9cc; padding:15px;">#INVITER_MESSAGE#</div><br></td></tr><tr><td colspan="2" style="text-align:left;font-family:Georgia, Times New Roman, Times, serif"><table><tbody><tr><td style="vertical-align:top;padding-right:30px;">#CAMPAIGN_IMG#<div style="font-family: Arial; font-weight: normal; font-size: 22px; color: #2a4f62; margin: 3px 0;"><strong>Help me reach my goal and change lives!</strong></div><a href="#CAMPAIGN_URL#"><img src="#PATH_HOME#wp-content/templates/givenow.png" alt="Give Now"/></a><br style="clear: both;" /><br>#CAMPAIGN_DESC#<br><br><a href="#CAMPAIGN_URL#"><img src="#PATH_HOME#wp-content/templates/givenow.png" alt="Give Now"/></a><img src="http://dev5.seeyourimpact.com/wp-content/templates/lifechanging.jpg" alt="Life changing" /><br><br>#FEATURED_GIFTS_CONTENT#</td><td style="vertical-align:top;width:200px;font-size:12px;"><div style="background:#c3dce4;color:#2a4f62; font-family:Georgia, Times New Roman, Times, serif; font-size:13px; padding:4px; "><div style="padding:8px;border:1px dashed #fff;">100% of your donation goes to the gift you choose.<br><br>You\'ll see exactly who you helped in 2 weeks!</div></div>#FEATURED_POST_PICTURE#<br>#FEATURED_POST_CONTENT#</td></tr></tbody></table></td></tr></tbody></table>', 'any-campaign-invite-email', 0, 0, 'templates', '', true);	

   update_post_meta($pid,'template_gifts_count','2');
   update_post_meta($pid,'template_subject','#INVITER_NAME# invites you to support a campaign!');
   update_post_meta($pid,'template_note','Hi there! I thought you might want to support this cause. Let\'s participate together and make a difference!

- #INVITER_NAME#');
   update_post_meta($pid,'template_featured_post','random');
   update_post_meta($pid,'template_css','');
   update_post_meta($pid,'template_featured_gifts','random');
   
  $pid = db_new_page(1, 1, 'My Campaign Invite Email', '<table width="600" border="0" cellspacing="0" cellpadding="0"><tbody><tr><td colspan="2" style="text-align:left;font-family:Georgia, Times New Roman, Times, serif"><table><tbody><tr><td style="vertical-align:top;padding-right:30px;">#CAMPAIGN_IMG#<div style="font-family: Arial; font-weight: normal; font-size: 22px; color: #2a4f62; margin: 3px 0;"><strong>Help me reach my goal and change lives!</strong></div>#GIVE_NOW_BTN#<br style="clear: both;" /><br>#CAMPAIGN_DESC#<br><br>#GIVE_NOW_BTN#<img src="http://dev5.seeyourimpact.com/wp-content/templates/lifechanging.jpg" alt="Life changing" /><br><br>#FEATURED_GIFTS_CONTENT#</td><td style="vertical-align:top;width:200px;font-size:12px;"><div style="background:#c3dce4;color:#2a4f62; font-family:Georgia, Times New Roman, Times, serif; font-size:13px; padding:4px; "><div style="padding:8px;border:1px dashed #fff;">100% of your donation goes to the gift you choose.<br><br>You\'ll see exactly who you helped in 2 weeks!</div></div>#FEATURED_POST_PICTURE#<br>#FEATURED_POST_CONTENT#</td></tr></tbody></table></td></tr></tbody></table>', 'my-campaign-invite-email', 0, 0, 'templates', '', true);		

   update_post_meta($pid,'template_gifts_count','2');
   update_post_meta($pid,'template_subject','#INVITER_NAME# needs your support! ');
   update_post_meta($pid,'template_note','');
   update_post_meta($pid,'template_featured_post','random');
   update_post_meta($pid,'template_css','');
   update_post_meta($pid,'template_featured_gifts','random');

}

function fix_story_delays_aug2011() {
  global $wpdb;
  $wpdb->query($wpdb->prepare(" 
  INSERT INTO notificationHistory 
  (mailType,donorID,donationID,postID,blogID,sentDate,success,
  emailTo,emailSubject,emailText,donationContactID,error)
  VALUES 
  (2, 473, 2803, 1684, 33, '2011-03-18 11:53:09', 1, '','--', '--', 0, ''),
  (2, 1314, 2895, 141, 60, '2011-03-28 01:44:10', 1, '','--', '--', 0, ''),
  (2, 2403, 2914, 4713, 3, '2011-04-17 10:40:21', 1, '','--', '--', 0, ''),
  (2, 1505, 3298, 2004, 33, '2011-04-25 11:31:26', 1, '','--', '--', 0, ''),
  (2, 2705, 3523, 511, 14, '2011-05-09 05:58:57', 1, '','--', '--', 0, ''),
  (2, 1505, 3622, 172, 54, '2011-08-08 12:25:39', 1, '','--', '--', 0, ''),
  (2, 1505, 3622, 172, 54, '2011-08-08 12:25:39', 1, '','--', '--', 0, ''),
  (2, 2848, 3792, 172, 54, '2011-08-08 12:25:39', 1, '','--', '--', 0, ''),
  (2, 2848, 3792, 172, 54, '2011-08-08 12:25:39', 1, '','--', '--', 0, ''),
  (2, 2992, 4090, 358, 16, '2011-07-21 16:35:45', 1, '','--', '--', 0, ''),
  (2, 3002, 4103, 742, 9, '2011-07-19 00:18:26', 1, '','--', '--', 0, ''),
  (2, 3002, 4103, 1403, 22, '2011-07-19 00:11:40', 1, '','--', '--', 0, ''),
  (2, 2984, 4104, 784, 9, '2011-08-04 06:41:37', 1, '','--', '--', 0, ''),
  (2, 1504, 4108, 742, 9, '2011-07-19 00:18:26', 1, '','--', '--', 0, ''),
  (2, 608, 4117, 784, 9, '2011-08-04 06:41:37', 1, '','--', '--', 0, ''),
  (2, 680, 4176, 784, 9, '2011-08-04 06:41:37', 1, '','--', '--', 0, ''),
  (2, 1311, 4181, 784, 9, '2011-08-04 06:41:37', 1, '','--', '--', 0, ''),
  (2, 979, 4185, 359, 16, '2011-08-08 12:28:47', 1, '','--', '--', 0, ''),
  (2, 3057, 4235, 1186, 20, '2011-08-15 09:50:22', 1, '','--', '--', 0, '');"));
	
}

function create_unsubscribed_list() {
  global $wpdb;
  $wpdb->query("CREATE TABLE IF NOT EXISTS unsubscribed
    (id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    email VARCHAR(100) NOT NULL,
    PRIMARY KEY(`id`), UNIQUE KEY(`email`))");   
}

function add_cart_referrer() {
  global $wpdb;
  $wpdb->query("ALTER TABLE cart 
    ADD COLUMN referrer INT(10) UNSIGNED NOT NULL");		
}

function insert_campaign_update_template() {
  $pid = db_new_page(1, 1, 'Campaign Update Email', '<table width="600" border="0" cellspacing="0" cellpadding="0"><tbody>
<tr><td style="text-align: left;font-family: Georgia, Times New Roman, Times, serif" colspan="2"><table><tbody><tr><td style="vertical-align: top;padding-right: 30px"><div style="background:#eeeeee;padding:10px">#INVITER_MESSAGE#</div><br />
#CAMPAIGN_IMG#<div style="font-family: Arial;font-weight: normal;font-size: 22px;color: #2a4f62;margin: 3px 0"><strong>Lives changed so far</strong></div>Here is some update from <b>#CAMPAIGN_NAME#</b> campaign. You can donate to the campaign through the button below. <br /><br />
<a href="#CAMPAIGN_URL#"><img src="#PATH_HOME#wp-content/templates/givenow.png" alt="Give Now" /></a><br /><br />
#UPDATE_STATS# <br />
#FEATURED_GIFTS_CONTENT#</td>
<td style="vertical-align: top;width: 200px;font-size: 12px"><div style="background: #c3dce4;color: #2a4f62;font-family: Georgia, Times New Roman, Times, serif;font-size: 13px;padding: 4px"><div style="padding: 8px;border: 1px dashed #fff">100% of your donation goes to the gift you choose.

You\'ll see exactly who you helped in 2 weeks!</div></div>#FEATURED_POST_PICTURE# #FEATURED_POST_CONTENT#</td></tr></tbody></table></td></tr></tbody></table>', 'campaign-update-email', 0, 0, 'templates', '', true);		
  update_post_meta($pid,'template_gifts_count','2');
  update_post_meta($pid,'template_subject','Update on #CAMPAIGN_NAME#');
  update_post_meta($pid,'template_note','');
  update_post_meta($pid,'template_featured_post','random');
  update_post_meta($pid,'template_css','');
  update_post_meta($pid,'template_featured_gifts','random');
	
}

function add_invite_visited() {
  global $wpdb;
  $wpdb->query("ALTER TABLE invite 
    ADD COLUMN visited DATETIME");		
}

function create_campaign_stats_table() {
  global $wpdb;
  
  $wpdb->query("CREATE TABLE IF NOT EXISTS
  campaigns
  (post_id INT(10) UNSIGNED NOT NULL,
  donors_count INT(10) UNSIGNED NOT NULL,
  gifts_count INT(10) UNSIGNED NOT NULL,
  raised DOUBLE NOT NULL,
  goal DOUBLE NOT NULL,
  last_donated DATETIME NOT NULL,
  data TEXT NOT NULL,
  PRIMARY KEY(`post_id`))");
}

function create_donor_demographics() {
  db_add_column("donorInfo.i_location", "varchar(255)");
}

function add_inviter_name_on_invitation() {
  global $wpdb;
  $wpdb->query("ALTER TABLE  `invitation` 
  CHANGE  `subject`  `inviter_name` VARCHAR( 255 )");	
}

function add_cart_item_blog_id() {
  global $wpdb;
  $wpdb->query("ALTER TABLE cartItem 
    ADD COLUMN blog_id INT(10) UNSIGNED NULL DEFAULT 0");
}

function add_cart_txn_data() {
  global $wpdb;
  $wpdb->query("ALTER TABLE cart 
    ADD COLUMN txnID VARCHAR(40) NULL,
		ADD COLUMN txnData TEXT NULL");
}

function add_tipout_gift() {
  global $wpdb;

  $wpdb->insert('gift', array(
    'id' => 3,
    'displayName' => 'a donation to SeeYourImpact.org',
    'description' => 'Contribution to SeeYourImpact.org operating expenses',
    'blog_id' => 1,
    'unitAmount' => 0,
    'varAmount' => 1,
    'unitsWanted' => 0,
    'unitsDonated' => 0, 
    'active' => 0));
}

function fix_merged_thankyou_email_typeid() {
  global $wpdb;	
  $wpdb->query($wpdb->prepare("UPDATE notificationHistory
  SET mailType=21 WHERE mailType=11 AND emailSubject != '(FB wall post)'
  AND notificationID >= 25597"));
}

function fix_single_main_user_donor() {
  global $wpdb;
  
  $users = $wpdb->get_results($wpdb->prepare(
  "SELECT user_id,firstName,lastName,sum(main) as mains 
  FROM donationGiver 
  WHERE user_id > 0 
  GROUP BY user_id ORDER BY mains DESC"));
  
  foreach($users as $user) {
	$dgs = $wpdb->get_results($wpdb->prepare("SELECT ID  
	FROM donationGiver WHERE user_id=%d ORDER BY ID DESC",
	$user->user_id));  	
  
	if(count($dgs)>1) {
	  $wpdb->query($wpdb->prepare("UPDATE donationGiver
	  SET main = 0 WHERE user_id=%d AND ID <> %d",$user->user_id,$dgs[0]->ID)); 
	}
  }
}

function blog_charity_info_location() {
  global $wpdb;	
  $wpdb->query("ALTER TABLE blogCharityInfo ADD COLUMN location VARCHAR(255) NOT NULL");
}

function add_campaign_public_flag() {
  global $wpdb;	
  $wpdb->query("ALTER TABLE campaigns ADD COLUMN public TINYINT(1) NOT NULL");
}

function more_campaign_metas() {
  global $wpdb;	
  $wpdb->query("ALTER TABLE campaigns 
    ADD COLUMN featured TINYINT(1) NOT NULL,
    ADD COLUMN post_title TEXT NOT NULL,
	ADD COLUMN post_name VARCHAR(200) NOT NULL,
	ADD COLUMN guid VARCHAR(255) NOT NULL");	
}

function copy_campaign_public_tag() {
  global $wpdb;	
  $campaigns = $wpdb->get_results("SELECT post_id FROM campaigns");
  if(!empty($campaigns)) foreach($campaigns as $campaign){
    $p = get_post($campaign->post_id);
    if((has_tag('public',$p) && !has_tag('private',$p)) 
	  || (has_tag('featured',$p) && !has_tag('private',$p))) {
      $update = 'public=1'; 
	  if(has_tag('featured',$p))
	    $update .= ',featured=1';     

      $update .= $wpdb->prepare(',post_title = %s',$p->post_title);
      $update .= $wpdb->prepare(',post_name = %s',$p->post_name);
      $update .= $wpdb->prepare(',guid = %s',$p->guid);
      $wpdb->query($wpdb->prepare("UPDATE campaigns SET ".$update." WHERE post_id=%d",$campaign->post_id));
	}
  }
}

function create_pledges_table() {
  global $wpdb;	  
  $wpdb->query("CREATE TABLE IF NOT EXISTS 
  pledges
  (id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  event_id INT(10) UNSIGNED NOT NULL,
  user_id INT(10) UNSIGNED NOT NULL,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  status VARCHAR(10) NOT NULL,
  message VARCHAR(255) NULL,
  PRIMARY KEY(`id`))  
  ");
}

function more_pledge_fields() {
  global $wpdb;	
  $wpdb->query("ALTER TABLE pledges 
  ADD COLUMN `amount` DOUBLE UNSIGNED DEFAULT 0 NOT NULL,
	ADD COLUMN `date_created` DATETIME NOT NULL,
	ADD COLUMN `date_updated` DATETIME NOT NULL;");

}

function rename_pledge_amount() {
  global $wpdb;	
  $wpdb->query("ALTER TABLE pledges
    CHANGE COLUMN `amount` `due` DOUBLE UNSIGNED DEFAULT 0 NOT NULL");
}

function more_pledge_fields2() {
  global $wpdb;	
  $wpdb->query("ALTER TABLE pledges 
    ADD COLUMN `paid` DOUBLE UNSIGNED DEFAULT 0 NOT NULL AFTER due,
	ADD COLUMN `unit` VARCHAR(255) NULL AFTER message;");	
}

function more_pledge_fields3() {
  global $wpdb;	
  $wpdb->query("ALTER TABLE pledges 
    ADD COLUMN `visitor_id` INT UNSIGNED DEFAULT 0 NOT NULL AFTER user_id");	
}

function add_pledge_count()
{
  db_add_column('campaigns.allow_pledges', "BOOL DEFAULT 1 NOT NULL");
  db_add_column('campaigns.pledge_count', "INT(10) UNSIGNED NOT NULL DEFAULT 0");
  db_add_column('campaigns.end_date', "DATETIME");
}

function create_500_donors() {
  global $wpdb;
  
  $results = $wpdb->get_results(
"SELECT u.* FROM wp_users u
LEFT JOIN donationGiver dg ON dg.email = u.user_email
WHERE LOCATE('+',u.user_email)=0 AND dg.email IS NULL");

//    pre_dump($results);

  foreach ($results as $result) {	  

	$first = $name = ucwords(trim($result->display_name));
	$last = '';
	if(strpos($name,' ')!==false){
	  $cut = strrpos($name,' ');
      if($cut!==FALSE) { 
	    $first = substr($name,0,$cut);
		$last = substr($name,$cut+1,strlen($name)-$cut);
	  }
	}	

//pre_dump($result);

    $sql=$wpdb->prepare(
	  "INSERT INTO donationGiver 
(email, sendUpdates, firstName, lastName, donationOwner,
referrer, notes, verified, fb_connect, validated, user_id, main) 
VALUES (%s,0,%s,%s,0,'','',1,'',1,%d,1)",		
	$result->user_email,$first,$last,$result->ID);
//	pre_dump($sql);
	$wpdb->query($sql);
  }

}

function add_gc_delivery() {
  db_add_column('cartItemDetails.emailTo', "TEXT AFTER recipientID");
  db_add_column('cartItemDetails.mailTo', "TEXT AFTER emailTo");
}

function add_donor_demotag() {
  db_add_column('donationGiver.demo_tag', "VARCHAR(100) NOT NULL DEFAULT ''");
}

function insert_default_impactcard_values() {

switch_to_blog(1);

update_blog_option(1, 'ic_subject', '#IC_SENDER_NAME# has sent you a #IC_PRICE# SeeYourImpact.org gift card!');
update_blog_option(1, 'ic_main_content', '<p>#IC_TOP_CONTENT#</p><p>#IC_CARD_CONTENT#</p><p>#IC_FINEPRINTS#</p><p>#IC_INSTRUCTIONS#</p><p><img src="#PATH_TPL#sep.jpg" width="604" height="23" alt="" /><br /><br/></p><h2 style="font-family: Georgia, \'Times New Roman\', Times, serif; text-align:left; text-transform:uppercase; letter-spacing:2px; font-weight:normal;">Help us spread the word</h2><table><tr><td style="font-family: Georgia, \'Times New Roman\', Times, serif; font-size:15px; text-align:left;float:left;margin-right:10px; width:250px;">Share the joy of giving to your friends, family or colleagues!</td><td><a href="http://www.facebook.com/share.php?t=#IC_SHARE_TITLE#&u=#IC_SHARE_LINK#" title="Share this on Facebook"><img src="#PATH_TPL#facebook.png" alt="Share this on Facebook" border="0"/></a> <a href="http://twitter.com/home/?status=#IC_SHARE_TITLE#+#IC_SHARE_LINK#" title="Share this on Twitter"><img src="#PATH_TPL#twitter.png" alt="Share this on Twitter" border="0"/></a> <br/><br/><a href="http://facebook.com/SeeYourImpact/" style="color:#656465; font-family: Georgia, \'Times New Roman\', Times, serif;" title="Become our fan on Facebook">Become our fan on Facebook</a> | <a href="http://twitter.com/SeeYourImpact/" style="color:#656465; font-family: Georgia, \'Times New Roman\', Times, serif;" title="Follow us on Twitter">Follow us on Twitter</a></td></tr></table>');
update_blog_option(1, 'ic_instructions', '<div style="font: 16px Arial; color: #666;" id="instructions"><h3 style="color:#222;">How does it work?</h3> To use your Impact Card, simply select a life-changing gift on <a style="color:#222;" href="http://seeyourimpact.org">SeeYourImpact.org</a>, and click the donate button. Apply the code "#IC_CODE_FIRST#" during checkout.<h3 style="color:#222;">What happens next?</h3>In about 2 weeks, you will receive an individual story of the life you changed. <h3 style="color:#222;">Questions?</h3>We\'re never more than an email away. Send your questions and ideas to: <a style="color:#222;" href="mailto:contact@seeyourimpact.org">contact@SeeYourImpact.org</a>. For information about Impact Card redemption, please visit: <a style="color:#222;"  href="http://seeyourimpact.org/redeem">http://seeyourimpact.org/redeem</a>.</div>');
update_blog_option(1, 'ic_fineprints', '<div style="font-family:arial; color:black; font-size:11px; padding: 20px 35px">Unless otherwise specified, Impact Card purchases not fully used within 12 months from the date of purchase will convert to a charitable donation to SeeYourImpact.  Please review the SeeYourImpact terms and conditions for further information.</div>');
update_blog_option(1, 'ic_top_content', '<div style="font: 16px Arial; color: #666;">Greetings, #IC_RECIPIENT_NAME#!<br><br>Congratulations - #IC_SENDER_NAME# has given you <b>#IC_GIFT_NAME#</b> for use on <a href="http://seeyourimpact.org" style="color:#222;">SeeYourImpact.org</a>! You can apply this card towards over 200 donation choices in 18 countries worldwide, including the U.S.<br><br>Better yet, <b>100% of your donation</b> goes to your chosen charity.<br/><br/>For more information, and to print your own certificates, please visit:<br><div style="padding-left: 30px; padding-top: 4px;">#IC_CODE_LIST#</div></div>');
update_blog_option(1, 'ic_card_content', '<div style="font-family:arial; margin:20px 0 0 35px;background:#b7e3f0; width:531px;"><img style="display:block;" src="#PATH_TPL#images/impact-card-top.jpg?" width="531" height="18" /><div class="text" style="width: 531px; position: relative; z-index: 1;"><div style="color:black; text-align:center; padding:20px 0;">For: <b>#IC_RECIPIENT_NAME#</b><br/>Balance: <b>#IC_PRICE#</b><br/><br/>Dear #IC_RECIPIENT_FIRST#,<br/><br/>#IC_MESSAGE#<br/><br/>-- #IC_SENDER_NAME#</div><table id="t1" width="531" cellspacing="0" cellpadding="0" border="0" style="display:block;"><tr><td valign="top" width="229" valign="top"><table width="229" cellspacing="0" cellpadding="0" border="0"><tr><td valign="top" colspan="2" style="width:229;height:163;"><img style="display:block;" width="229" height="163" src="#PATH_TPL#images/impact-card-left-top.jpg"></td></tr><tr><td valign="top" width="109"><img style="display:block;" src="#PATH_TPL#images/impact-card-left-left.jpg" width="109" height="28"></td><td valign="top" class="code" width="120" style="background:#F26925; text-align:center; color: black; font: bold 13px Arial;"><img id="bkgd3" class="bkgd" style="display:block;" src="#PATH_TPL#wp-content/images/impact-card-code-bkgd.jpg" width="120" height="1"/><div class="text2" style="padding-top:7px;">#IC_CODE_FIRST#</div></td></tr><tr><td valign="top" colspan="2"><img style="display:block;" src="#PATH_TPL#images/impact-card-left-bottom.jpg" width="229" height="40"></td></tr></table></td><td valign="top" width="302" valign="top"><img style="display:block;" src="#PATH_TPL#images/impact-card-right.jpg" width="302" height="231"></td></tr></table><img id="bkgd2" class="bkgd" style="display:block;" src="#PATH_TPL#images/impact-card-bkgd.jpg" width="531" height="1"><img style="display:block;" src="#PATH_TPL#images/impact-card-bottom.jpg" width="531" height="18"></div></div>');
update_blog_option(1, 'ic_code_link', '<a style="color:#222; padding-left: 15px;" href="#PATH_HOME#/card/#IC_CODE#">#IC_CODE# (#IC_PRICE#)</a>');
update_blog_option(1, 'ic_share_title', 'Give the gift of giving!');
update_blog_option(1, 'ic_share_link', 'http://seeyourimpact.org/impact-cards/');

restore_current_blog();	

}

function add_donation_gift_onbehalf() {
  db_add_column('donationGifts.onbehalf', 'VARCHAR(255) NULL');		
}

function add_tip_info_promo() {
  db_new_page(1,1,'','<strong>Love the Idea?</strong> Please help us cover the costs of service. Your contribution to dev5.seeyourimpact.com is tax deductible in the US. <label id="more-info-tip-link"><u>view more info</u></label><div id="more-info-tip" style="display:none;">More explanation of tips. This is the popup</div>','tip-info-promo',0,0,'promo','',false);
}

function add_recurlycc_sitewide_settings(){
  global $wpdb;

  $wpdb->query("INSERT INTO `paypal_settings`
  (`id`, `current_mode`, `type`, `business_id`, `form_action`, `return_url`,
  `cancel_return_url`, `notify_url`, `btn_image`, `pixel_image`,
  `verify_url`, `provider`, `api_key`, `api_url`, `api_user`, `api_signature`) VALUES
(19, 'DOWN', 'TEST', 'syidev5-test', '', '', '', '', '', '', '', 'recurlycc', 'c7fe31fe011441dbb8d0cddf0dd0040b', '', 'yosia@seeyourimpact.org', ''),
(20, 'DOWN', 'LIVE', 'syidev5-test', '', '', '', '', '', '', '', 'recurlycc', 'c7fe31fe011441dbb8d0cddf0dd0040b', '', 'yosia@seeyourimpact.org', '');");
}

function add_dg_notes_table()
{
  db_add_table("gift_notes", array(
    'id' => "int(10) unsigned NOT NULL /*PRIMARYKEY*/",
    'notes' => "TEXT default NULL"
  ));
}

function add_more_campaign_columns() {
  db_add_column('campaigns.theme', 'VARCHAR(30) NULL');
  db_add_column('campaigns.restricted', 'TINYINT(1) UNSIGNED DEFAULT 1');
  db_add_column('campaigns.per_unit', 'VARCHAR(30) NULL');
  db_add_column('campaigns.status', 'VARCHAR(20) NULL');
  db_add_column('campaigns.tags', 'VARCHAR(40) NULL AFTER `goal`');
  db_add_column('campaigns.start_date', "DATETIME AFTER `pledge_count`");
}

function add_more_campaign_columns2() {
  db_add_column('campaigns.tip', 'DOUBLE NOT NULL AFTER raised');
}

function add_cart_tip_field() {
  db_add_column('cart.tip', 'VARCHAR(10) NULL');
}

function add_donor_share_email() {
  db_add_column('donationGiver.share_email', 'TINYINT(1) UNSIGNED DEFAULT 0');
}

function migrate_campaigns() {
  global $wpdb;

  $wpdb->query("
    update campaigns c
    left join wp_1_postmeta pm on pm.post_id=c.post_id and pm.meta_key='syi_theme'
    left join wp_1_postmeta pm2 on pm2.post_id=c.post_id and pm2.meta_key='syi_tag'
    left join wp_1_postmeta pm3 on pm3.post_id=c.post_id and pm3.meta_key='syi_restricted'
    set c.theme=pm.meta_value, c.tags=pm2.meta_value, c.restricted=pm3.meta_value");
}

function better_dat_tracking() {
  db_add_column('donationAcctTrans.kind', 'VARCHAR(10) NULL AFTER id');
}

function update_dat_kinds() {
  global $wpdb;

  $wpdb->query("update donationAcctTrans dat left join payment p on p.id=dat.paymentID
    set dat.kind='PURCHASE' where dat.amount > 0 and p.provider != 5");

  $wpdb->query("update donationAcctTrans dat left join payment p on p.id=dat.paymentID
    set dat.kind='XFER' where p.provider = 10");

  $wpdb->query("update donationAcctTrans dat left join payment p on p.id=dat.paymentID
    set dat.kind='ALLOCATE' where dat.amount < 0 and p.provider = 5");

  // REFUND back to recurly
  $wpdb->query("update donationAcctTrans dat left join payment p on p.id=dat.paymentID
    set dat.kind='REFUND' where dat.amount < 0 and p.provider = 9");

  $wpdb->query("update donationAcctTrans dat left join payment p on p.id=dat.paymentID
    set dat.kind='XFER IN' where dat.amount > 0 and (p.provider = 5 OR dat.paymentID = 0)");

  $wpdb->query("update donationAcctTrans dat 
    set dat.kind='XFER OUT' where dat.amount < 0 and dat.paymentID = 0");

  // Initial balance notes
  $wpdb->query("update donationAcctTrans dat 
    set dat.kind='NOTE' where dat.amount = 0 and (dat.note like 'Initial%registration%' OR dat.note='Initial account balance' OR dat.note like 'Initial%Japan%')");

  $wpdb->query("update donationAcctTrans dat left join payment p on p.ID=dat.paymentID
    left join donationAcct da on da.id=dat.donationAcctId
    set dat.kind='SPEND' where dat.amount < 0 and p.provider != 10
     and (da.donationAcctTypeId > 2 AND da.donationAcctTypeId != 7)");
}

function add_new_start_pages() {
  $text=<<<EOF
[sidebar id="start-sidebar"]
<h1>Birthdays. Weddings. Thursdays.<br>
Make any day special by giving back.</h1>

<img style="float: right; margin: 0 -20px 10px 30px; " alt="" src="http://seeyourimpact.org/files/2011/07/Landing-Page-Fundraisers1.png" width="400" height="237">
<p style="font-size:125%; color:#666; margin-top:20px;">Celebrations make everyone feel happy - create your own by starting a fundraiser that will change lives and make a difference.  The joy of giving is sweeter when shared!</p>

<h2 style="clear:both;">Give back through one of our partners...</h2>

<div style="margin: 0 -15px 0 5px;">
<div class="start-theme"><img class="logo" src="http://pratham.seeyourimpact.org/files/2010/08/PRA_PrathamUSA_tag.png"><a href="?theme=readathon">start a child's Read-a-thon</a><a href="?theme=pratham">start an adult fundraiser</a></div>

<div class="start-theme"><img class="logo" src="http://pratham.seeyourimpact.org/files/2010/08/PRA_PrathamUSA_tag.png"><a href="?theme=readathon">start a child's Read-a-thon</a><a href="?theme=pratham">start an adult fundraiser</a></div>

<div class="start-theme"><img class="logo" src="http://pratham.seeyourimpact.org/files/2010/08/PRA_PrathamUSA_tag.png"><a href="?theme=readathon">start a child's Read-a-thon</a><a href="?theme=pratham">start an adult fundraiser</a></div>

<div class="start-theme"><img class="logo" src="http://pratham.seeyourimpact.org/files/2010/08/PRA_PrathamUSA_tag.png"><a href="?theme=readathon">start a child's Read-a-thon</a><a href="?theme=pratham">start an adult fundraiser</a></div>

</div>

<h2>...or rally for a cause:</h2>

<div style="margin: 0 -15px;">

<a class="partner" href="?theme=water"><img class="partner-image" src="http://seeyourimpact.org/wp-content/charity-images/charity-trailblazer.jpg" alt="" width="200" height="90" /><span class="partner-name">clean water</span></a>

<a class="partner" href="?theme=education"><img class="partner-image" src="http://seeyourimpact.org/wp-content/charity-images/charity-pratham.jpg" alt="" width="200" height="90" /><span class="partner-name">education</span></a>

<a class="partner" href="?theme=hunger"><img class="partner-image" src="http://seeyourimpact.org/wp-content/charity-images/charity-granitosdepaz.jpg" alt="" width="200" height="90" /><span class="partner-name">hunger</span></a>

<a class="partner" href="?theme=asia"><img class="partner-image" src="http://seeyourimpact.org/wp-content/charity-images/charity-sankurathri.jpg" alt="" width="200" height="90" /><span class="partner-name">causes in Asia</span></a>

<a class="partner" href="?theme=africa"><img class="partner-image" src="http://seeyourimpact.org/wp-content/charity-images/charity-healafrica.jpg" alt="" width="200" height="90" /><span class="partner-name">causes in Africa</span></a>

<a class="partner" href="?theme=americas"><img class="partner-image" src="http://seeyourimpact.org/wp-content/charity-images/charity-rootsandwings.jpg" alt="" width="200" height="90" /><span class="partner-name">causes in Latin America</span></a>

</div>
EOF;

  db_new_page(1,1,'Start a fundraiser',$text,'start');

  $text = <<<EOF
<h3 class="heading" style="color:green;">Getting started on your fundraiser is as easy as:</h3>

<div class="step"><img class="number" src="/wp-content/images/number-1.png"> Choose your cause.</div>

<div class="step step-left"><img class="number" src="/wp-content/images/number-2.png"> Input your info.</div>

<div class="step"><img class="number" src="/wp-content/images/number-3.png"> Produce your page!</div>
EOF;
  db_new_page(1, 1, 'Start page sidebar', $text, 'start-sidebar', 0, 0, 'promo', '', true);

  $text = <<<EOF
<h3 class="heading" style="color:green;">Getting started on your fundraiser is as easy as:</h3>

<div class="step"><img class="number" src="/wp-content/images/number-1.png"> Choose your cause.</div>

<div class="step step-left"><img class="number" src="/wp-content/images/number-2.png"> Input your info.</div>

<div class="step"><img class="number" src="/wp-content/images/number-3.png"> Produce your page!</div>

[fundraiser_thumbnail width="220"]
EOF;
  db_new_page(1, 1, 'Start fundraiser sidebar', $text, 'start-fundraiser-sidebar', 0, 0, 'promo', '', true);

  $text = <<<EOF
EOF;
  db_new_page(1, 1, 'Edit fundraiser sidebar', $text, 'edit-fundraiser-sidebar', 0, 0, 'promo', '', true);
}

function add_account_expiration() {
  db_add_column('donationAcct.expired', "TINYINT(1) UNSIGNED DEFAULT 0");
  db_add_column('donationAcct.tip_amt', "DOUBLE DEFAULT 0");
  db_add_column('donationAcct.gift_amt', "DOUBLE DEFAULT 0");
  db_add_column('donationAcct.open_amt', "DOUBLE DEFAULT 0");
}

function add_campaign_archived() {
  db_add_column('campaigns.archived', "TINYINT(1) UNSIGNED DEFAULT 0");
}

function add_campaign_first_donated() {
  db_add_column('campaigns.first_donated', 'DATETIME'); 
  db_add_column('campaigns.status', 'VARCHAR'); 
}

function add_default_theme_data() {
  global $wpdb;

  $create_table = <<<EOS
CREATE TABLE `theme_data` (
  `name` varchar(45) NOT NULL default 'default' COMMENT 'the name of the theme',
  `contents` text NOT NULL COMMENT 'a json string with all the elements',
  PRIMARY KEY  (`name`)
) ENGINE=MyISAM;
EOS;

  if (FALSE === $wpdb->query($create_table)) {
    die("failed to create theme_data table");
  }

  $initial_rows = <<<EOS
INSERT INTO `theme_data` (`name`, `contents`)
VALUES
  ('africa', '{\"post_title\":\"\",\"post_content\":\"\",\"goal\":\"\",\"required_fields\":{\"post_content\":\"true\",\"post_title\":\"true\",\"goal\":\"true\"},\"gifts\":\"true\",\"tag\":\"africa\",\"fields\":{\"post_content\":\"true\",\"post_title\":\"true\",\"goal\":\"true\",\"photo\":\"true\"},\"start_banner\":\"\",\"banner\":\"\",\"about\":\"\"}'),
  ('americas', '{\"post_title\":\"\",\"post_content\":\"\",\"goal\":\"\",\"required_fields\":{\"post_content\":\"true\",\"post_title\":\"true\",\"goal\":\"true\"},\"gifts\":\"true\",\"tag\":\"americas\",\"fields\":{\"post_content\":\"true\",\"post_title\":\"true\",\"goal\":\"true\",\"photo\":\"true\"},\"start_banner\":\"\",\"banner\":\"\",\"about\":\"\"}'),
  ('children', '{\"post_title\":\"\",\"post_content\":\"\",\"goal\":\"\",\"required_fields\":{\"post_content\":\"true\",\"post_title\":\"true\",\"goal\":\"true\"},\"gifts\":\"true\",\"tag\":\"children\",\"fields\":{\"post_content\":\"true\",\"post_title\":\"true\",\"goal\":\"true\",\"photo\":\"true\"},\"start_banner\":\"\",\"banner\":\"\",\"about\":\"\"}'),
  ('water', '{\"post_title\":\"\",\"post_content\":\"\",\"goal\":\"\",\"required_fields\":{\"post_content\":\"true\",\"post_title\":\"true\",\"goal\":\"true\"},\"gifts\":\"true\",\"tag\":\"clean-water\",\"fields\":{\"post_content\":\"true\",\"post_title\":\"true\",\"goal\":\"true\",\"photo\":\"true\"},\"start_banner\":\"\",\"banner\":\"\",\"about\":\"\"}'),
  ('default', '{\"post_title\":\"\",\"post_content\":\"\",\"goal\":\"\",\"required_fields\":{\"post_content\":\"true\",\"post_title\":\"true\",\"goal\":\"true\"},\"gifts\":\"false\",\"tag\":\"\",\"fields\":{\"post_content\":\"true\",\"post_title\":\"true\",\"goal\":\"true\",\"photo\":\"true\"},\"start_banner\":\"<div style=\\\"width:690px; height: 120px; background: url(http:\\/\\/dev2.seeyourimpact.com\\/themes\\/placeholder_690x120.png) no-repeat 0 0; margin: -15px -35px 20px;\\\"><\\/div>\",\"banner\":\"<div style=\\\"width:990px; height: 120px; background: url(http:\\/\\/dev2.seeyourimpact.com\\/themes\\/placeholder_990x120.png) no-repeat 0 0;\\\"><\\/div>\",\"about\":\"<div style=\\\"width:649px; height:250px; background: url(http:\\/\\/dev2.seeyourimpact.com\\/themes\\/placeholder_649x250.png) no-repeat 0 0\\\"><p>(fill in general info about the campaign that will appear on all fundraiser pages)<\\/p><\\/div>\"}'),
  ('education', '{\"post_title\":\"\",\"post_content\":\"\",\"goal\":\"\",\"required_fields\":{\"post_content\":\"true\",\"post_title\":\"true\",\"goal\":\"true\"},\"gifts\":\"true\",\"tag\":\"education,jobs\",\"fields\":{\"post_content\":\"true\",\"post_title\":\"true\",\"goal\":\"true\",\"photo\":\"true\"},\"start_banner\":\"\",\"banner\":\"\",\"about\":\"\"}'),
  ('asia', '{\"post_title\":\"\",\"post_content\":\"\",\"goal\":\"\",\"required_fields\":{\"post_content\":\"true\",\"post_title\":\"true\",\"goal\":\"true\"},\"gifts\":\"true\",\"tag\":\"india,asia\",\"fields\":{\"post_content\":\"true\",\"post_title\":\"true\",\"goal\":\"true\",\"photo\":\"true\"},\"start_banner\":\"\",\"banner\":\"\",\"about\":\"\"}'),
  ('hunger', '{\"post_title\":\"\",\"post_content\":\"\",\"goal\":\"\",\"required_fields\":{\"post_content\":\"true\",\"post_title\":\"true\",\"goal\":\"true\"},\"gifts\":\"true\",\"tag\":\"hunger\",\"fields\":{\"post_content\":\"true\",\"post_title\":\"true\",\"goal\":\"true\",\"photo\":\"true\"},\"start_banner\":\"\",\"banner\":\"\",\"about\":\"\"}');
EOS;
    
  if (8 != $wpdb->query($initial_rows)) {
    die("");
  }
}

function add_campaign_updates() {
  // Comma-separated list of IDs
  db_add_column('campaigns.updates', "VARCHAR(3000) NOT NULL DEFAULT ''"); 
  db_add_column('campaigns.supporters_count', "VARCHAR(3000) NOT NULL DEFAULT '0' AFTER pledge_count"); 
}

function create_syi_mailer_queue() {
  global $wpdb;

  $sql = <<<EOS
CREATE TABLE IF NOT EXISTS syi_mailer_queue (
    id VARCHAR(32) NOT NULL PRIMARY KEY COMMENT 'created by PHPs uniqid() function',
    created DATETIME NOT NULL COMMENT 'should be NOW() at time of insert',
    content TEXT NOT NULL COMMENT 'json representation of the $content object IN SyiMailer::send'
) COMMENT 'the email holding queue, see the SyiMailer class'
EOS;

  $wpdb->query($sql);
}

function switch_to_charity_theme() {
  switch_theme('syi', 'charity');
}

function create_campaign_teams() {
  global $wpdb;

  db_add_column('campaigns.team', 'VARCHAR(80) AFTER goal');
  $wpdb->query("UPDATE campaigns c
    JOIN wp_1_postmeta pm ON pm.post_id=c.post_id AND pm.meta_key='readathon_city'
    SET c.team = pm.meta_value");
}

function upgrade_charity_table() {
  global $wpdb;
  $wpdb->query("RENAME TABLE blogCharityInfo TO charity");
  db_add_column('charity.name', "VARCHAR(200) NOT NULL DEFAULT '' AFTER blog_id");
  db_add_column('charity.description', "LONGTEXT NOT NULL DEFAULT '' AFTER name");
  db_add_column('charity.domain', "VARCHAR(40) AFTER blog_id");
  db_add_column('charity.live', "TINYINT(1) DEFAULT 0 AFTER description");
  db_add_column('charity.url', "VARCHAR(100) AFTER description");
  db_add_column('charity.terms', "LONGTEXT NOT NULL DEFAULT ''");

  $wpdb->query("ALTER TABLE charity ADD FULLTEXT (name,description,terms,location,domain)");
}
function upgrade_charity_table3() {
  db_add_column('charity.private', "TINYINT(4) NOT NULL DEFAULT 0");
}

function upgrade_charity_table2() {
  global $wpdb, $blog_id;

  $bi = get_blog_details($blog_id);
  $c = explode('.', $bi->domain);
  $d = get_bloginfo('description');
  if ($d == 'Just another  weblog' || $d == 'Just another weblog')
    $d = '';
  $mode = sw_get_local_payment_mode($bid);

  $wpdb->update('charity', array(
    'name' => html_entity_decode($bi->blogname, ENT_QUOTES | ENT_COMPAT | ENT_HTML401),
    'domain' => $c[0],
    'description' => $d,
    'live' => $mode == "LIVE",
    'url' => $bi->siteurl . $bi->path
  ), array('blog_id' => $blog_id));
}

function create_pratham_marathon_campaign() {
  Team::create_campaign("pratham/marathon", (object)array(
    'desc' => 'Pratham Marathon',
    'goal' => 51000
  ));
}

function add_offline_raised() {
  db_add_column('campaigns.offline', 'DOUBLE NOT NULL DEFAULT 0 AFTER raised');
}

function add_campaign_photos() {
  db_add_column('campaigns.photo', 'VARCHAR(255)');
}

function create_teton_science_school_campaign() {
  Team::create_campaign("tss/tssgrad", (object)array(
    'desc' => 'Teton Science School Graduates',
    'goal' => 50000
  ));

  $teams = array();
  for( $i = 2012; $i >= 1997; $i--) {
    $teams[] = (object) array(
      'name' => "Class of '$i",
      'goal' => 10000
    );
  }

  foreach ($teams as $t) {
    Team::create_team('tss/tssgrad', (object)$t);
  }
}

function create_kidsco_bbq_teams() {
  # just to save Jamie a lot of clicking and typing
  $teams = <<<EOS
Troop 636
Troop 574
Troop 647
Troop 167
Troop 407
Troop 166
Troop 80
Troop 498
Troop 438
Troop 570
Troop 571
Troop 745
Troop 600
Troop 186
Troop 430
Troop 284
Troop 282
EOS;

  foreach (explode("\n", $teams) as $name) {
    print "creating $name";
    Team::create_team('seattlebsa/bsacamp', (object)array(
      'name' => $name,
      'goal' => 1000
    ));
  }
}

function add_reason_to_syi_mailer_queue() {
  db_add_column('syi_mailer_queue.reason', "TEXT COMMENT 'reason for this job being in this table'");
}

function add_h2o_for_readathon() {
  global $wpdb;

  $exists = $wpdb->get_var('select contents from theme_data where name = "readathon"');
  if ($exists) {
    ?><pre>***<br/>***<br/>***<br/><p>readathon theme already exists, manually merge in h2o</p>***<br/>***<br/>***<br/></pre><?
  }
  else {
    $theme = array(
      "h2o" => array(
        "header_image_url" => "http://seeyourimpact.org/themes/pratham/email_header.jpg",
        "first_banner_bg"  => "#000000",
        "first_banner_fg"  => "#ffffff",
        "second_banner_bg" => "#f4c93d",
        "second_banner_fg" => "#ffffff",
        "template_name"    => "readathon"
      )
    );

    $wpdb->query($wpdb->prepare(
      "insert into theme_data(name, contents) values(%s, %s)", 'readathon', json_encode($theme)
    ));
  }
}

function add_charity_fundraiser() {
  db_add_column('charity.fundraiser', "BIGINT UNSIGNED DEFAULT 0");
}

function add_html_body_column() {
  db_add_column('syi_mailer_queue.html_body', 'TEXT COMMENT "copy of the email that was sent to approve this queue entry"');
}

function migrate_campaign_owners() {
  db_add_column('campaigns.owner', "BIGINT UNSIGNED DEFAULT 0 AFTER goal");

  global $wpdb;
  $wpdb->query(
    "UPDATE campaigns c
     JOIN wp_1_postmeta pm ON pm.post_id=c.post_id AND pm.meta_key='syi_active_owner'
     SET c.owner = pm.meta_value");
}

function add_donor_addresses() {
  db_add_column('donationGiver.address', "VARCHAR(250) DEFAULT ''");
  db_add_column('donationGiver.address2', "VARCHAR(250) DEFAULT ''");
  db_add_column('donationGiver.city', "VARCHAR(100) DEFAULT ''");
  db_add_column('donationGiver.state', "VARCHAR(80) DEFAULT ''");
  db_add_column('donationGiver.zip', "VARCHAR(30) DEFAULT ''");
  db_add_column('donationGiver.phone', "VARCHAR(50) DEFAULT ''");
  db_add_column('payment.data', "TEXT DEFAULT ''");
}

function create_redirect_table() {
  db_add_table("redirects", array(
    'id' => "int(10) unsigned NOT NULL auto_increment /*PRIMARYKEY*/",
    'from_url' => "varchar(255) default NULL /*KEY*/",
    'to_url' => "varchar(255) default NULL",
    'owner' => "varchar(255) default NULL"
    ));
}

// transition from using "fb_sessions" usermeta to plain "fb_access_token" usermeta
function extract_access_token_from_sessions() {
  global $wpdb;

  $rows = $wpdb->get_results('select * from wp_usermeta where meta_key = "fb_session"');
  foreach ($rows as $row) {
    # does the user already have "fb_access_token" meta? if so, skip him
    $token = get_user_meta($row->user_id, 'fb_access_token', true);
    if ($token) {
      continue;
    }

    if (!$row->meta_value) {
      error_log("user $row->user_id has null fb_session, skipping");
      continue;
    }

    $json = json_decode($row->meta_value);
    if (!$json) {
      error_log("extract_access_token_from_sessions: fb_session for $row->user_id didn't decode as json");
      continue;
    }

    update_user_meta($row->user_id, 'fb_access_token', $json->access_token);
  }
}

// collapse "fb_publish_thanks" and "fb_publish_story" usermeta into "fb_perms" usermeta
function collapse_fb_permissions() {
  global $wpdb;

  $rows = $wpdb->get_results('select * from wp_usermeta where meta_key IN ("fb_publish_thanks", "fb_publish_story", "fb_publish_update")');
  foreach ($rows as $row) {
    # does the user already have "fb_perms" meta? if so, skip him
    $perms = get_user_meta($row->user_id, 'fb_perms', true);
    if (!$perms) {
      $perms = (object)array();
    }
    else {
      $perms = json_decode($perms);
    }

    // "publish_invite" didn't exist, so we always make sure it's set
    if (!property_exists($perms, 'publish_invite')) {
      $perms->publish_invite = true;
    }

    $key = preg_replace('/^fb_/', '', $row->meta_key);

    $perms->{$key} = $row->meta_value == "1" ? true : false;

    update_user_meta($row->user_id, 'fb_perms', json_encode($perms));
  }
}

function add_transaction_tables() {
  global $wpdb;

  db_add_table("a_transaction", array(
    'id' => "int(11) unsigned NOT NULL auto_increment /*PRIMARYKEY*/",
    'donation_id' => "int(11) unsigned NOT NULL /* KEY */",
    'donor_id' => "int(11) unsigned NOT NULL /* KEY */",
    'type' => "varchar(20) default NULL /*KEY*/",
    'date' => "datetime /* KEY */",
    'amount' => "DECIMAL(7,2) NOT NULL",
    'gift_id' => "int(11) unsigned /* KEY */",
    'fr_id' => "int(11) unsigned /* KEY */",
    'acct_id' => "int(11) unsigned /* KEY */",
    'notes' => "text"
    ));

  $wpdb->query("CREATE TABLE a_payment LIKE payment");
  db_add_column('a_payment.donationID', "int(11) unsigned NOT NULL AFTER id /* KEY */");
  db_add_column('a_payment.acct_id', "int(11) unsigned NOT NULL AFTER provider /* KEY */");
  db_remove_column('a_payment.donationAcctTrans');
  db_remove_column('a_payment.donation');
  db_remove_column('a_payment.testData');
  db_remove_column('a_payment.tip');
  db_remove_column('a_payment.discount');
  db_remove_column('a_payment.cart');

  $wpdb->query("CREATE TABLE a_account LIKE donationAcct");
  db_add_column('a_account.type', 'int(5) NOT NULL AFTER dateUpdated /* KEY */');
  db_remove_column('a_account.testData');
  db_remove_column('a_account.donorId');
  db_remove_column('a_account.creator');
  db_remove_column('a_account.name');
  db_remove_column('a_account.giftId');
  db_remove_column('a_account.archived');
  db_remove_column('a_account.tip_amt');
  db_remove_column('a_account.gift_amt');
  db_remove_column('a_account.open_amt');

  $wpdb->query("CREATE TABLE a_donation LIKE donation");
  db_add_column('a_donation.amount', "DECIMAL(7,2) NOT NULL AFTER donationDate");
  db_remove_column('a_donation.impactStatus');
  db_remove_column('a_donation.distributionStatus');
  db_remove_column('a_donation.fundTransferStatus');
  db_remove_column('a_donation.notificationsSent');
  db_remove_column('a_donation.instructions');
  db_remove_column('a_donation.notifications');
  db_remove_column('a_donation.campaign');
  db_remove_column('a_donation.step');
  db_remove_column('a_donation.testData');
  db_remove_column('a_donation.donationPromoCode');
  db_remove_column('a_donation.paymentID');

  $wpdb->query("CREATE TABLE a_gift LIKE donationGifts");
  db_add_column('a_gift.trans_id', 'int(11) unsigned NOT NULL AFTER donationID /* KEY */');
  db_remove_column('a_gift.distributionStatus');
  db_remove_column('a_gift.fundTransferStatus');
  db_remove_column('a_gift.campaign');
  db_remove_column('a_gift.unitsDonated');
  db_remove_column('a_gift.onbehalf');

}

function add_donor_data_column() {
  db_add_column('donationGiver.data', 'TEXT');
}

function add_transaction_tables2() {
  global $wpdb;

  db_add_column('a_transaction.tip', "DECIMAL(7,2) NOT NULL DEFAULT 0 AFTER amount /* KEY */");
  db_add_column('a_transaction.card', "DECIMAL(7,2) NOT NULL DEFAULT 0 AFTER amount /* KEY */");
}

function add_campaign_blog_id() {
  db_add_column('theme_data.blog_id', "bigint(20) AFTER name /* KEY */");
  db_add_column('theme_data.page_id', "bigint(20) AFTER blog_id /* KEY */");
}

function add_campaign_fr_id() {
  db_add_column('theme_data.fr_id', "bigint(20) AFTER page_id /* KEY */");
}

function add_gift_images() {
  db_add_column('gift.image', "VARCHAR(255) AFTER active");
  db_add_column('a_gift.image', "VARCHAR(255) AFTER active");

  global $wpdb;
  $path = SITE_URL . "/wp-content/gift-images/";
  $wpdb->query("UPDATE gift SET image=CONCAT('{$path}Gift_', id, '.jpg')");
  $wpdb->query("UPDATE a_gift SET image=CONCAT('{$path}Gift_', id, '.jpg')");
}

function add_fullcontact() {
  db_add_column('donationGiver.fullcontact', "TEXT");
  db_add_column('donationGiver.fullcontact_date', 'DATETIME /* KEY */');
}

function db_set_default_fundraisers() {
  global $wpdb;
  $wpdb->query("update theme_data t
    join campaigns c on c.owner=0 AND c.theme=t.name 
    set t.fr_id = c.post_id
    where c.post_id != IFNULL(t.fr_id, -1) AND t.name != 'give-big' AND t.name != 'africa'");
}

function import_custom_skins() {
  $result = CampaignApi::getOne('banks');
  $result->campaign_page->champions->title = 'Support a fundraiser';
  $result->campaign_header = "http://esperanza.seeyourimpact.org/files/2013/01/esperanza-header2.jpg";
  $result->og_image = "x_405,w_200,h_200,c_crop/" . $result->campaign_header;
  CampaignApi::update($result);

  $result = CampaignApi::getOne('bowlathon');
  $result->campaign_page->champions->title = 'Support a fundraiser';
  $result->campaign_header = "http://skyway.seeyourimpact.org/files/2013/01/bowlathon-hero2.jpg";
  $result->og_image = "x_405,w_200,h_200,c_crop/" . $result->campaign_header;
  CampaignApi::update($result);

  $result = CampaignApi::getOne('computers');
  $result->campaign_page->champions->title = 'Support a fundraiser';
  $result->campaign_header = "http://bgcbellevue.seeyourimpact.org/files/2013/01/Computer-Referb-Program-Banner.jpg";
  $result->og_image = "x_390,w_200,h_200,c_crop/" . $result->campaign_header;
  CampaignApi::update($result);

  $result = CampaignApi::getOne('givingtuesday');
  $result->campaign_header = 'http://newleaders.seeyourimpact.org/files/2012/11/Giving-Tuesday-Banner-public_997_2.png" style="position:relative; margin-top: -8px; margin-left: -12px; width: 997px;';
  $result->campaign_page->champions->title = "The New Leaders team";
  $result->team_sort = "donors";
  $result->hide_teams = TRUE;
  $result->og_image = "x_205,y_15,w_200,h_200,c_crop/" . $result->campaign_header;
  $result->goal_themes = 'givingtuesday, newleaders-staff';
  CampaignApi::update($result);

  $result = CampaignApi::getOne('grub_giving');
  $result->campaign_page->champions->title = 'Support a fundraiser';
  $result->campaign_header = "http://grub.seeyourimpact.org/files/2012/12/grub_header_image.jpg";
  $result->og_image = "x_405,w_200,h_200,c_crop/" . $result->campaign_header;
  CampaignApi::update($result);

  $result = CampaignApi::getOne('guatemala');
  $result->campaign_page->champions->title = 'Support a fundraiser';
  $result->campaign_header = "http://timmy.seeyourimpact.org/files/2012/12/TimmyGH_Banner2.png";
  $result->og_image = "x_190,w_200,h_200,c_crop/" . $result->campaign_header;
  CampaignApi::update($result);

  $result = CampaignApi::getOne('newleaders-staff');
  $result->campaign_header = 'http://newleaders.seeyourimpact.org/files/2012/11/Giving-Tuesday-Banner-public_997_2.png" style="position:relative; margin-top: -8px; margin-left: -12px; width: 997px;';
  $result->campaign_page->champions->title = "The New Leaders team";
  $result->team_sort = "donors";
  $result->og_image = "x_205,y_15,w_200,h_200,c_crop/" . $result->campaign_header;
  $result->donor_goal = 500;
  CampaignApi::update($result);

  $result = CampaignApi::getOne('next-generation');
  $result->campaign_page->champions->title = 'Support a fundraiser';
  $result->campaign_header = "http://doh.seeyourimpact.org/files/2012/12/doh-header.jpg";
  $result->og_image = "x_405,w_200,h_200,c_crop/" . $result->campaign_header;
  CampaignApi::update($result);

  $result = CampaignApi::getOne('okigolf');
  $result->campaign_page->champions->title = 'Support a fundraiser';
  $result->campaign_header = "http://d2eocsfaa3d93y.cloudfront.net/V2.0025/files/2012/12/okigolf_hero1.jpg";
  $result->og_image = "x_190,w_200,h_200,c_crop/" . $result->campaign_header;
  CampaignApi::update($result);

  $result = CampaignApi::getOne('pacific-crest');
  $result->campaign_page->champions->title = 'Support a fundraiser';
  $result->campaign_header = "http://ettaprojects.seeyourimpact.org/files/2013/01/etta_projects_banner.jpg";
  $result->og_image = "x_338,w_250,h_275,c_crop/" . $result->campaign_header;
  CampaignApi::update($result);

  $result = CampaignApi::getOne('partner-with-youth');
  $result->campaign_page->champions->title = 'Support a fundraiser';
  $result->is_home_page = TRUE;
  $result->campaign_header = "http://auburnymca.seeyourimpact.org/files/2013/02/PWY_FB-Banner_GreenBlue.jpg";
  $result->og_image = "x_405,w_200,h_200,c_crop/" . $result->campaign_header;
  $result->team_sort = "donors";
  CampaignApi::update($result);

  $result = CampaignApi::getOne('rgi-give');
  $result->campaign_page->champions->title = 'Support a fundraiser';
  $result->campaign_header = "http://rgi.seeyourimpact.org/files/2013/02/RGI-Give_Banner.jpg";
  $result->og_image = "x_390,w_200,h_200,c_crop/" . $result->campaign_header;
  CampaignApi::update($result);

  $result = CampaignApi::getOne('service-leadership');
  $result->campaign_page->champions->title = 'Support a fundraiser';
  $result->campaign_header = "http://blueenergy.seeyourimpact.org/files/2012/12/bE-web-banner.jpg";
  $result->og_image = "x_405,w_200,h_200,c_crop/" . $result->campaign_header;
  CampaignApi::update($result);

  $result = CampaignApi::getOne('thanks-marti');
  $result->campaign_page->champions->title = 'Support a fundraiser';
  $result->campaign_header = "http://crim.seeyourimpact.org/files/2012/12/crim_header.jpg";
  $result->og_image = "x_390,w_200,h_200,c_crop/" . $result->campaign_header;
  CampaignApi::update($result);

  $result = CampaignApi::getOne('virtual-garden');
  $result->campaign_header = "http://ghi.seeyourimpact.org/files/2012/12/virtualgardencampaign_hero.jpg";
  $result->og_image = "x_405,w_200,h_200,c_crop/" . $result->campaign_header;
  $result->campaign_page->champions->title = 'Support a fundraiser';
  $result->campaign_page->gifts->title = '';
  $result->fundraisers->gifts->title = '';
  CampaignApi::update($result);

  $result = CampaignApi::getOne('water_is_life');
  $result->campaign_page->champions->title = 'Support a fundraiser';
  $result->campaign_header = 'http://medrix.seeyourimpact.org/files/2013/02/SYI-Water-Campaign-Banner-990-Pixels.jpg';
  $result->og_image = "x_405,w_200,h_200,c_crop/{$result->campaign_header}";
  $result->is_home_page = TRUE;
  $result->team_sort = 'donors';
  CampaignApi::update($result);

  $result = CampaignApi::getOne('www');
  $result->campaign_page->champions->title = 'Support a fundraiser';
  $result->campaign_header = "http://seavuria.seeyourimpact.org/files/2013/01/SEAVURIA_WWW_header2.jpg";
  $result->og_image = "x_405,w_200,h_200,c_crop/" . $result->campaign_header;
  $result->team_sort = "donors";
  $result->donor_goal = 200;
  CampaignApi::update($result);

  $result = CampaignApi::getOne('doug-walker');
  $result->campaign_header = "http://tss.seeyourimpact.org/files/2012/11/syi_challenge_course_banner_980x200.png";
  $result->campaign_page->champions->title = "Our fundraising team";
  $result->campaign_page->show->give = TRUE;
  $result->fundraisers->show->give = TRUE;
  CampaignApi::update($result);

  $result = CampaignApi::getOne('iraqi-children');
  $result->campaign_header = "http://sicf.seeyourimpact.org/files/2012/10/iraqi_kids_980.jpg";
  $result->campaign_page->champions->title = "Our fundraising team";
  CampaignApi::update($result);

  $result = CampaignApi::getOne('dayofgiving');
  $result->campaign_header = "http://hopeww.seeyourimpact.org/files/2012/08/IDG_2012_banner_980.jpg";
  $result->campaign_page->champions->title = "Our fundraising team";
  CampaignApi::update($result);

  $result = CampaignApi::getOne('crimraces');
  $result->campaign_page->champions->title = 'Support a fundraiser';
  $result->campaign_header = "http://dev1.seeyourimpact.com/files/2012/04/New_Crim_WebBanner3_small1-1024x189.jpg";
  $result->goal = 50;
  $result->tag = 'crimkids';
  CampaignApi::update($result);

  $result = CampaignApi::getOne('sowa-25th');
  $result->campaign_page->champions->title = 'Support a fundraiser';
  $result->campaign_page->show->banner = TRUE;
  $result->campaign_page->show->header = FALSE;
  CampaignApi::update($result);

  $result = CampaignApi::getOne('nycriders');
  $result->campaign_page->champions->title = "Support a fundraiser - click on a name below";
  CampaignApi::update($result);

  $result = CampaignApi::getOne('msaf');
  $result->campaign_page->champions->title = 'Support a fundraiser';
  $result->title = "Microsoft Alumni Foundation";
  $result->can_join = FALSE;
  $result->team_sort = "donors";
  CampaignApi::update($result);
}

function update_fundraiser_tags() {
  global $wpdb;

  // Delete some specific cases (easier than writing a query)
  $wpdb->query("delete from wp_1_postmeta where meta_key='syi_tag' and post_id in (7720,7799,10999, 11012, 11013, 10844, 11023, 11026, 10836, 10908, 10890, 10966, 10988, 11000, 10996, 10841, 11097, 11103, 12643 )");

  // Delete where tags = theme name
  $wpdb->query("delete pm from wp_1_postmeta pm
    left join campaigns c on c.post_id = pm.post_id
    where pm.meta_key = 'syi_tag'
    and c.theme != ''
    and pm.meta_value = c.theme");

  // Delete where fundraiser tag is empty
  $wpdb->query("delete pm from wp_1_postmeta pm
    left join campaigns c on c.post_id = pm.post_id
    where pm.meta_key = 'syi_tag'
      and pm.meta_value = ''");

  $campaigns = CampaignApi::get(array());
  foreach ($campaigns as $campaign) {

    // Fix tags
    if ($campaign->name == 'bsacamp' || $campaign->name == 'isha') {
      $campaign->tag = $campaign->name;
      CampaignApi::update($campaign); 
    } else if ($campaign->name == 'readathon') {
      $campaign->tag = "pratham";
      CampaignApi::update($campaign); 
    }

    if (empty($campaign->tag))
      continue;

    // Delete all redundant campaign tags
    $wpdb->query($wpdb->prepare(
      "delete pm from wp_1_postmeta pm
      left join campaigns c on c.post_id = pm.post_id
      where pm.meta_key='syi_tag' 
       and c.theme=%s and pm.meta_value=%s",
      $campaign->name, $campaign->tag));
  }

  // Update all old theme-less campaigns to the latest default
  // so we don't expose further tag bugs in old code
  $wpdb->query("update campaigns set theme='default' where ISNULL(theme,'')=''");
}

function add_fundraiser_custom() {
  db_add_column('campaigns.custom', "TEXT");
}

function add_payment_gc_amount() {
  db_add_column('payment.gc_amount', 'DECIMAL(7,2)');
}
function add_gift_levels() {
  db_add_column('gift.prices', "VARCHAR(100) AFTER unitAmount");
}
