<?

global $post;
standard_page();

sharing_init(TRUE);
remove_action('syi_pagetop', 'draw_the_crumbs', 0);
add_filter('body_class', 'add_cart_class');
add_action('wp_head', 'card_head');

get_header(); 

$price = 25;

if ($_POST) {
  $quantity = intval($_REQUEST['quantity']);
  $price = intval($_REQUEST['price']);
  $delivery = $_REQUEST['delivery'];
  $firstname = trim($_REQUEST['firstname']);
  $lastname = trim($_REQUEST['lastname']);
  $message = trim($_REQUEST['message']);

  $card = array(
    'price' => $price,
    'quantity' => $quantity,
    'message' => $message,
    'recipient' => array(
      'first_name' => $firstname,
      'last_name' => $lastname
    )
  );

  if ($quantity <= 0) {
    $error = "X"; // No displayed error, just fall through
  } else if ($delivery == 'print') {
    if (empty($firstname) && !empty($lastname) ||
        !empty($firstname) && empty($lastname)) {
      $error = $print_error = "Please enter the recipient's name.";
    }
  } else if ($delivery == 'email') {
    $email = trim($_REQUEST['email']);

    if (empty($firstname) || empty($lastname) || empty($email)) {
      $error = $email_error = "Please enter the recipient's name and e-mail address.";
    } else if (!is_email($email)) {
      $error = $email_error = "Please enter a valid e-mail address.";
    } else {
      $card['recipient']['email'] = $email;
    }
  } else if ($delivery == 'mail') {
    $address = trim($_REQUEST['address']);
    $address2 = trim($_REQUEST['address2']);
    $city = trim($_REQUEST['city']);
    $state = trim($_REQUEST['state']);
    $zipcode = trim($_REQUEST['zipcode']);

    if (empty($firstname) || empty($lastname) || empty($address) || empty($city) || empty($state) || empty($zipcode)) {
      $error = $mail_error = "Please enter the name and postal address for delivery.";
    } else {
      $card['mailTo'] = array(
        'first_name' => $firstname,
        'last_name' => $lastname,
        'address' => $address,
        'address2' => $address2,
        'city' => $city,
        'state' => $state,
        'zipcode' => $zipcode
      );
    }
  } else
    $error = $delivery_error = "Please select a delivery method.";

  if (empty($error)) {
    cart_add_gift_certificates(0, $card);
    wp_redirect(get_cart_link()); 
    die;
  }
}

// Set up initial gift card defaults
if ($quantity <= 0)
  $quantity = 1;
if ($price <= 0)
  $price = 25;



?>

<? draw_sharing_vertical(); ?>
<form id="cart-page" class="based standard-form card-form standard-page evs" action="<?= get_permalink($post->ID) ?>" method="post">
  <div class="page-main">
    <section class="right" id="page-sidebar">
      <div class="based">
        <? draw_promo_content("impact-card-faq", NULL, true); ?>
        <? draw_sharing_horizontal(); ?>
      </div>
    </section>

  <? the_content(); ?>

  <div class="padded-row fields" style="padding-left: 30px;">
    <div class="labeled" style="width:110px;"><label for="price">card amount</label>
      <?= display_gc_amount_ddl('price', $price, ' tabindex="2"') ?>
    </div>
    <div class="labeled" style="padding:5px 0;">
      quantity:
    </div>
    <div style="margin-left:5px;" class="labeled" style="width:75px;"><label for="quantity" style="padding-left:15px;">quantity</label>
      <input class="focused" type="number" name="quantity" id="quantity" maxlength="2" min="1" max="100" size="2" tabindex="2" value="<?= esc_attr($quantity) ?>" style="width:60px;" />
    </div>
  </div>

  <h3 style="padding-top:10px;">How would you like us to send your gift?</h3>
  <div class="padded-row fields">
    <? show_error($delivery_error); ?>
    <label class="radio-option" for="delivery-email">
      <input class="delivery" type="radio" name="delivery" id="delivery-email" tabindex="3" <?= radio_option('email', $delivery) ?> />Immediate notification via e-mail<div class="instructions">Impact Cards will be delivered to the recipient within an hour.</div>
    </label>
    <label class="radio-option" for="delivery-mail" style="color:#888;">
      <input class="delivery" type="radio" name="delivery" id="delivery-mail" disabled="" tabindex="3" <?= radio_option('mail', $delivery) ?> />Mailed via US Postal Service<div class
="instructions">Delivery in 3-5 days.  Please submit orders by <b>December 18</b> for delivery by December 24.</div>
    </label>
    <label class="radio-option" for="delivery-print">
      <input class="delivery" type="radio" name="delivery" id="delivery-print" tabindex="3" <?= radio_option('print', $delivery) ?> />Don't send; I'll print and deliver myself<div class="instructions">We'll e-mail you a printable page with your Impact Card and instructions. (<a href="/card/sample" class="link" target="_new">see a sample</a>)</div>
    </label>
  </div>

  <div class="left spacer <?= empty($delivery) ? '' : 'hidden' ?>" style="width:0; height:300px;"></div>

  <div class="<?= when('email', $delivery) ?>">
    <h3>Email address for delivery</h3>
    <? show_error($email_error); ?>
  </div>
  <div class="<?= when('mail', $delivery) ?>">
    <h3>Mailing address for delivery</h3>
    <? show_error($mail_error); ?>
  </div>
  <div class="<?= when('print', $delivery) ?>">
    <h3>Recipient information</h3>
    <? show_error($print_error); ?>
  </div>

  <div class="hidden when when-email when-mail when-print">
  <div class="padded-row fields" style="margin-bottom: 0;">
    <div class="gap-after">
      <div class="labeled" style="width: 203px;"><label for="firstname">first name</label>
        <input class="focused" type="text" name="firstname" id="firstname" size="20" tabindex="4" value="<?= esc_attr($firstname) ?>"/>
      </div>
      <div class="labeled next-field" style="width: 203px;"><label for="lastname">last name</label>
        <input class="focused" type="text" name="lastname" id="lastname" size="20" tabindex="5" value="<?= esc_attr($lastname) ?>"/>
      </div>
    </div>
  </div>
  </div>

  <div class="<?= when('email', $delivery) ?>">
  <div class="padded-row fields" style="margin-top:0;">
    <div class="labeled gap-after" style="width: 430px;"><label for="email">e-mail address</label>
      <input class="focused" type="text" name="email" id="email" size="48" tabindex="6" value="<?= esc_attr($email) ?>"/>
    </div>
  </div>
  </div>

  <div class="<?= when('mail', $delivery) ?>">
  <div class="padded-row fields" style="margin-top:0;">
    <div class="labeled gap-after" style="width:430px;"><label for="address">street address </label>
      <input class="focused" type="text" name="address" id="address" size="48" tabindex="6" value="<?= esc_attr($address) ?>"/>
    </div>
    <div class="labeled gap-after" style="width:430px;"><label for="address2">street address - 2nd line (optional)</label>
      <input class="focused" type="text" name="address2" id="address2" size="48" tabindex="7" value="<?= esc_attr($address2) ?>"/>
    </div>
    <div class="gap-after">
      <div class="labeled" style="width:203px;"><label for="city">city</label>
        <input class="focused" type="text" name="city" id="city" size="20" tabindex="8" value="<?= esc_attr($city) ?>"/>
      </div>
      <div class="labeled next-field" style="width:60px;"><label for="state">state</label>
        <input class="focused" type="text" name="state" id="state" size="2" tabindex="9" value="<?= esc_attr($state) ?>"/>
      </div>
      <div class="labeled next-field" style="width:119px;"><label for="zipcode">zipcode</label>
        <input class="focused" type="text" name="zipcode" id="zipcode" size="10" tabindex="10" value="<?= esc_attr($zipcode) ?>"/>
      </div>
    </div>
  </div>
  </div>

  <div class="hidden when when-email when-mail when-print" style="margin-top: -20px;">
    <div class="padded-row fields">
      <div class="labeled gap-after"><label for="message">add a personal message? (optional)</label>
        <textarea class="focused" type="text" name="message" id="message" style="width:430px;" size="45" tabindex="20" lines="5"/><?= xml_entities($message) ?></textarea>
      </div>
    </div>
  </div>

  <div class="padded-row fields actions">
    <input type="submit" class="left button big-button orange-button w150" name="submit" value="Add to Cart">
    <p class="left terms when-mail when-email <?= empty($delivery) ? 'hidden' : '' ?>">Unless otherwise specified, Impact Card purchases not fully used within 12 months from the date of purchase will convert to a charitable donation to SeeYourImpact.  Please review our <a class="link" target="_new" href="/gifts/">terms and conditions</a> for further information.</p>
  </div>


<? post_admin_bar(); ?>
</div>
</form>


<? get_footer();

function add_cart_class($classes) {
  $classes[] = "page-cart";
  return $classes;
}

function card_head() {
?>
<style>
.card-form {
  font: 12pt Arial;
}

.card-form img.right {
  position: relative;
  left: 30px;
  top: -10px;
}
.card-form .error {
  margin: 0.5em 0;
}

.card-form .terms {
  width: 320px;
  font-size: .8em;
  color: #444;
  padding: 0 30px;
}

.card-form h1 {
  font-size: 20pt;
  font-weight: normal;
  color: #2B4E64;
  margin-bottom: 0;
}
.card-form h3 {
  margin: 0;
  font-size: 16pt;
  font-weight: normal;
  color: #2B4E64;
}

.card-form .padded-row {
  margin: 20px 0;
}

.card-form .when { 
  width: 600px; 
  position: relative;
}

.card-form .instructions {
  font-size: 80%;
  margin-left: 21px;
  padding: 4px 0;
}

</style>
<script type="text/javascript">
$(function() {
  function update_when(val) {
    if (val == null || val == '')
      return;

    $(".spacer").hide();
    $(".when:not(.when-" + val + ")").stop().css('position','absolute').fadeOut(200);
    $(".when-" + val).stop().css('position','relative').fadeIn(500);
  }

  update_when($('.delivery:checked').val()); // Initial state
  $(".delivery").live('change', function() {
    var val = $(this).val();
    update_when(val);
  }); 
});
</script>
<?
}

function show_error($msg) {
  if (empty($msg))
    return;

  ?><div class="error"><?= xml_entities($msg) ?></div><?
}

function when($opt, $val) {
  $s = "when when-$opt";
  if ($opt != $val)
    $s .= " hidden";
  return $s;
}

function radio_option($opt, $val) {
  $s = ' value="' . esc_attr($opt) . '"';
  if ($opt == $val)
    $s .= ' checked="checked"';
  return $s;
}
