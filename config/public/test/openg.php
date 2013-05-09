<?php

include_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-includes/wp-db.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-admin/includes/user.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-includes/syi/syi-includes.php');

nocache_headers();
foreach ($_REQUEST as $k => $v) {
  $_REQUEST[$k] = stripslashes($v);
}

echo "<pre style=\"white-space:pre-line\">\n";

if (!is_user_logged_in()) {
  echo "wordpress user is not logged in, nothing to do";
  exit;
}

$user_id = get_current_user_id();
$fb = new SyiFacebook($user_id);
echo "user_id: ", $user_id, "\n";

if (preg_match('/seeyourimpact\.org$/i', $_SERVER['HTTP_HOST'])) {
  $domain = 'seeyourimpact.org';
}
else {
  preg_match('/([^\.]+\.seeyourimpact\.com)$/i', $_SERVER['HTTP_HOST'], $m);
  $domain = $m[1];
}

?>

<p>Enter the url of an Open Graph object, and then push the button that corresponds to the action to take on it.</p>
<ul>
  <li>example fundraiser: http://<?= $domain ?>/members/alexd</li>
  <li>example story: http://eastsidepathways.<?= $domain ?>/2012/11/09/ensuring-every-child-in-bellevue-reads-at-grade-level/</li>
</ul>
<form id="publish_methods" method="POST">
  <div style="width:100%;">
     <label for="ogurl">Open&nbsp;Graph&nbsp;url</label>
    <input style="width:100%;" id="ogurl" type="text" name="ogurl" value="<?= str_replace('"', '&quot;', $_REQUEST['ogurl']) ?>"/>
  </div>
  <table>
    <tr>
      <td>OG Custom Objects</td>
      <td>
        <input class="use_open_graph" type="submit" name="do" value="Invite" />
        <input class="use_open_graph" type="submit" name="do" value="Story ready" />
        <input class="use_open_graph" type="submit" name="do" value="Donate to fundraiser" />
      </td>
    </tr>
    <tr>
      <td>OG Default Objects</td>
      <td>
        <input type="submit" name="do" value="Invite" />
        <input type="submit" name="do" value="Story ready" />
        <input type="submit" name="do" value="Story photo ready" />
        <input type="submit" name="do" value="Story photo to FB Page" />
        <input type="submit" name="do" value="Donate to fundraiser" />
      </td>
    </tr>
  </table>
</form>
<form id="raw_api" method="POST">
  <div style="width:100%">
    <label for="raw_url">URL to post to (url path only, no domain, eg "/me/feed")</label>
    <input type="text" name="raw_url" id="raw_url" size="100" value="<?= str_replace('"', '&quot;', $_REQUEST['raw_url']) ?>"/>
    <label for="raw_data">Raw Data (json)</label>
    <textarea id="raw_data" name="raw_data" rows="15" cols="100"><?= $_REQUEST['raw_data'] ?></textarea>
  </div>
  <table>
    <tr>
      <td>
        <input type="submit" name="do_as_json" value="json"/>
      </td>
    </tr>
  </table>
</form>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script>
$(document).ready(function() {
  console.log("go");
  $('#publish_methods input[type="submit"]').click(function() {
    console.log("more");
    $('<input />').attr('type', 'hidden')
      .attr('name', 'use_open_graph')
      .attr('value', ($(this).attr('class') == "use_open_graph") ? 1 : 0)
      .appendTo('form');
  });
});
</script>
<?

function fundraiser_non_archived($obj) {
  $x = !preg_match('/^c\d+$/', $obj->post_name) && !preg_match('/^\d\d\d\d(-\d+)?$/', $obj->post_name);
  return $x;
}

function fundraiser_url_to_id($url) {
  global $wpdb;
  $parts = parse_url($url);
  if (!preg_match('/([^\/]+)\/?$/', $parts['path'], $m)) {
    error_log("fundraiser_as_array: invalid url: $url");
    return;
  }
  else {
    $user_login = $m[1];
  }

  $rows = $wpdb->get_results($wpdb->prepare(
    'select * from campaigns where owner = (select ID from wp_users where user_login = %s) order by post_id desc limit 1', $user_login
  ));

  if (!$rows[0]->post_id) {
    error_log("failed to parse url, parts = ".var_export($parts, true));
    error_log("failed to parse url, rows = ".var_export($rows, true));
  }
  return $rows[0]->post_id;
}

function story_url_to_ids($url) {
  global $wpdb;

  $parts = parse_url($url);
  if (!preg_match('/([^\/]+)\/?$/', $parts['path'], $m)) {
    error_log("story_as_array: invalid url: $url");
    return;
  }
  else {
    $slug = $m[1];
  }

  if (preg_match('/(.+\.?)seeyourimpact\.(com|org)$/i', $parts['host'], $m)) {
    $x = explode('.', $m[1]);
    $rows = $wpdb->get_results($wpdb->prepare(
      'select blog_id from wp_blogs where domain like %s',
      $x[0] . '%'
    ));
    $blog_id = $rows[0]->blog_id;
  }

  $stories = $wpdb->get_results($wpdb->prepare(
    'select post_id, post_image, guid from donationStory where blog_id = %d and post_name = %s',
    $blog_id, $slug
  ));

  return array($blog_id, $stories[0]->post_id);
}

try {
  if (count($_REQUEST) > 0) {
    // do something
    if ($_REQUEST['do_as_json']) {
      $params = json_decode($_REQUEST['raw_data'], true);
      if ($params) {
        echo "posting: ", var_export($params, true), "\n";
        $json = $fb->api($_REQUEST['raw_url'], 'POST', $params);
      }
      else {
        echo "failed to parse json\n";
      }
    }
    else {
      echo "request: ", var_export($_REQUEST, true), "\n";
      $fb->use_open_graph = $_REQUEST['use_open_graph'] == 1;
      $url = $_REQUEST['ogurl'];

      if (preg_match('/^invite/i', $_REQUEST['do'])) {
        $post_id = fundraiser_url_to_id($url);
        echo "fundraiser #$post_id as array: ", var_export($fb->fundraiser_as_array($post_id), true), "\n";
        echo "publish_invite: ";
        $json = $fb->publish_invite($post_id, "this is a test invitation from openg.php");
      }
      else if (preg_match('/^donate/i', $_REQUEST['do'])) {
        $post_id = fundraiser_url_to_id($url);
        echo "fundraiser #$post_id as array: ", var_export($fb->fundraiser_as_array($post_id), true), "\n";
        echo "publish_donation: ";
        $json = $fb->publish_donation($post_id);
      }
      else {
        list($blog_id, $post_id) = story_url_to_ids($url);
        echo "story #$blog_id, #$post_id as array: ", var_export($fb->story_as_array($blog_id, $post_id), true), "\n";
        $params = array();
        if ($_REQUEST['do'] == 'Story photo ready') {
          $params['as_photo'] = 1;
        }
        else if ($_REQUEST['do'] == 'Story photo to FB Page') {
          $params['to_page'] = '135481816605727'; // Basir's "SyiTestPage"
        }
        echo "publish_story: ";

        $json = $fb->publish_story($blog_id, $post_id, $params);
      }
    }

    echo "\$fb returned: ", json_pretty($json), "\n";

    if (is_array($json) && array_key_exists('id', $json)) {
      echo "<p style=\"font-size=200%; color:green\">facebook publish succeeded</p>\n";
    }
    else {
      echo "<p style=\"font-size=200%; color:red\">facebook publish FAILED</p>\n";
    }
  }
}
catch (Exception $e) {
  echo "exception: $e";
}
