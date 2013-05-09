<?
include_once( ABSPATH . 'a/api/api.php');
include_once( ABSPATH . 'a/api/gift.php');

// TODO: should we start writing these in Javascript?

class Widget {
  public static function gift_browser($where) {
    global $GIFTS_EVENT,$GIFTS_LOC,$event_id;

    $where['available'] = TRUE;
    $where['view'] = 'gallery';
    $where['whole'] = TRUE; // Check how many whole gifts there are
    $gifts = GiftApi::get($where);

    $new_browser = count($gifts) > 0 && count($gifts) < 4;
    foreach ($gifts as $gift) {
      if (!$gift->variable)
        $new_browser = FALSE;
    }
    $cl = ($where['indented'] === FALSE) ? '' : 'indented';

    // Is this a legacy partner/campaign?
    if (!$new_browser) {
      $opts = array(
        'page_title' => eor($where['title'],' '),
        'header' => FALSE,
        'preload' => TRUE,
        'shrink' => TRUE,
        'regions' => eor($where['tags'], $where['tag']),
        'blog_id' => $where['blog_id'],
        'event_id' => eor($where['fr_id'], $GIFTS_EVENT),
        // 'limit' => 36,
        'show_private' => $where['show_private']
      );

      if (!empty($where['exclude'])) 
        $opts['exclude_hack'] = $where['exclude'];

      gift_browser_widget($opts);
      return;
    }

    foreach ($gifts as $gift) {
      $pay_link = pay_link($gift->gift_id);
      if ($GIFTS_EVENT > 0)
        $pay_link = "$pay_link&eid=$GIFTS_EVENT";
        $prices = as_ints($gift->prices);

      ?>
      <div class="design give-gifts <?=$cl?>"><div class="give-gift">
        <div class="right images over-right">
          <div class="big-pic"><img src="<?= $gift->image ?>" width="280" height="210"></div>
        </div>
        <div class="gift-band cf">
          <h2 class="gift-title"><?= $gift->title ?></h2>
          <? if ($gift->variable) { 
            ?><div class="actions"><?

            foreach ($prices as $price) {
              ?><a class="left button gift-grid green-button medium-button" href="<?= $pay_link ?>&amount=<?=$price?>">Give $<?=$price?></a><?
            }

            ?>
            </div>
            <form action="<?= $pay_link ?>" method="POST" class="left give-any actions">
              <label>or any amount:
                <span class="dollar">$</span>
                <input name="amount" value="" size="3" maxlength="4" class="any-amount box">
              </label>
              <input type="submit" class="submit button green-button medium-button" value="Give">
            </form>
          <? } else { ?>
            <div class="actions">
              <div class="right price">$<?= $gift->price ?></div>
              <a class="left button orange-button big-button" href="<?= $pay_link ?>">Donate &raquo;</a>
            </div>
        <? } ?>
        </div>
        <? draw_gallery_part($gift->gallery['gift_description']); ?>
      </div></div>
      <? 
    }
  }
}


// Submit all contact form leads from the home page to our desk.com
// support portal.  This hooks into the mail being sent by WP-ContactForm7.
function submit_leads_to_deskcom($a) {
  // if (!IS_LIVE_SITE || IS_STAGING_SITE) return;
    
  $data = $a->posted_data;

  try {
    // TODO: actually submit the data via CURL
    $url = "http://requestb.in/xm7jdcxm";

    $post = array(
      'ticket[desc]' => 'ticket:desc',
      'ticket[subject]' => 'ticket:subject',

      'email[subject]' => 'New request',
      'email[body]' => 'email:body',

      'interaction[email]' => eor($data['your-email'],'N/A'),
      'interaction[name]' => eor($data['your-name'],'N/A'),

      'customer[email]' => eor($data['your-email'],'N/A'),
      'customer[first_name]' => eor($data['your-name'],'N/A'),
      'customer[company]' => eor($data['your-organization'],'N/A'),
      'customer[custom_organization]' => eor($data['your-organization'],'N/A')
    );

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
    $output = curl_exec($curl);
    curl_close($curl);
  } catch (Exception $e) {
    // Log the error
  }
}
add_action('wpcf7_mail_sent', 'submit_leads_to_deskcom');
                                           
