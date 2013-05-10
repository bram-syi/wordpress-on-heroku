<?
/*
Plugin Name: Gift Catalog
Plugin URI: http://seeyourimpact.org/
Description: Disply catalog of gifts as a widget or interactive page
Author: Steve Eisner
Version: 1.0
*/

global $gift_page;
$gift_page = 0;

function tag_to_topic($tags1, $tags2, $tags3) {
  return NULL; // DISABLED for now.

  $tag = NULL;
  if ($tags1) {
  foreach ($tags1 as $t) {
    if (!empty($tag))
      return null;
    $tag = $t;
  }}
  foreach ($tags2 as $t) {
    if (!empty($tag))
      return null;
    $tag = $t;
  }
  foreach ($tags3 as $t) {
    if (!empty($tag))
      return null;
    $tag = $t;
  }
  return $tag;
}

function draw_gift_browser_pages($tags1, $tags2, $tags3, $min_amt, $max_amt, $gpg) {
  global $gift_page, $GIFTS_LOC;

  $args = array(
    'tags1' => $tags1,
    'tags2' => $tags2,
    'tags3' => $tags3,
    'min_amt' => $min_amt,
    'max_amt' => $max_amt,
    'exclude' => 'no_browser',
    'limit' => 100
  );

  // Handle special case first pages
  if (count($tags1) + count($tags2) + count($tags3) + $min_amt + $max_amt == 0) {
    $args['tags1'] = 'featured';
    $args['gpg'] = 'g/featured';
    $args['limit'] = 6;
    $args['page_title'] = 'Give a gift, get a story of the life you change!';
    draw_gift_pages($args);
    $args['tags1'] = $tags1;
    $args['gpg'] = $gpg;
    $args['limit'] = $limit;
    unset($args['page_title']);
  } else {
    $tag = tag_to_topic($tags1,$tags2,$tags3);
    draw_topic_page($tag);
  }

  draw_gift_pages($args);
}

function draw_gift_pages($args) {
  global $gift_page, $GIFTS_LOC;

  extract($args);
  $args['cols'] = "id,displayName,title,unitAmount,varAmount,unitsWanted,active"; // cut down on fetch time
  if (empty($gifts))
    $data = list_gifts($args);
  else
    $data = $gifts;

  $pos = array(
    array(0,1,2,3,4,5),
    array(0,3,1,4,2,5)
  );

  if (empty($args['gpg']))
    $gpg = $GIFTS_LOC;
  else
    $gpg = $args['gpg'];

  $c = 0;
  $p = 0;
  $page_size = 6;

  $cl = false;
  $page_title = $data['title'];
  if (empty($page_title))
    $page_title = "featured gifts";
  $co = count($data['items']);
  foreach ($data['items'] as $gift) {
    if ($c == 0) {
      if ($cl) echo '</div>';
      ?><div id="page_<?=$gift_page?>" class="gift-page item"><?
      $cl = true;
      $gift_page++; $p++;
      $GIFTS_LOC = "$gpg-$p";

      if (!empty($page_title)) {
        ?><div class="page-title"><?= htmlspecialchars(ucfirst($page_title)) ?></div><?
        unset( $page_title);
      }

      $p = $pos[($co >= 5) ? 1 : 0];
    }

    ?><div class="position-<?=$p[$c]?>"><?
      draw_gift($gift);
    ?></div><?

    $c = ($c + 1) % $page_size;

    $args = array(
      'give_label' => '$'
    );

    if (isset($give_any) && $give_any && ($gift_page == 1) && ($c == 5 || $co == 1)) {
      ?><div class="position-<?=$p[$c]?>"><div class="gift give-any based"><?
      syi_giveany_widget($args);
      ?></div></div><?
      $c = ($c + 1) % $page_size;
    }

    $co--;
  }
  if ($cl) echo '</div>';

  return $p;
}

function draw_topic_page($tag) {
  if (empty($tag) || $tag=="featured")
    return;

  global $gift_page, $GIFTS_LOC;

  ?><div id="page_<?=$gift_page?>" class="gift-page item">
    <h3 class="page-title"><?= tag_named($tag) ?></h3>
  <?
      $gift_page++;
  
  ?></div><?
}

/*
function gifts_catch_uri() {
  $uri = $_SERVER['REQUEST_URI'];
  $uri = str_replace( '?' . $_SERVER['QUERY_STRING'], '', $uri );
  $uri = split( '/', $uri );
  $uri = array_values( array_filter( $uri ) );
 
  if ($uri[0] == 'gifts') {
    $args = array();
    draw_gift_pages($args);
    die;
  }
}
add_action('init', 'gifts_catch_uri', 0);
*/

?>
