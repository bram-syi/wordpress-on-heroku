<?
require_once('api/api.php'); // This does a SHORTINIT load of the API

if (!is_user_logged_in()) {
  $url = $_SERVER["REQUEST_URI"];
  wp_redirect(SITE_URL . "/signin/?redirect_to=http://" . $_SERVER['HTTP_HOST'] . urlencode($url));
  die;
}

global $blog_id;
if ($blog_id == 1)
  $subsite = FALSE;

if ( !current_user_can($blog_id == 1 ? 'level_10' : 'level_2') ) {
  wp_redirect('/'); die;
}

$ver = sub_version();
$server = (IS_LIVE_SITE && !IS_STAGING_SITE) ? "http://s.seeyourimpact.org/" : "/";
$wpdir = "{$server}V{$ver}";
$dir = "{$wpdir}/a";

?>
<!DOCTYPE HTML>
<html>
<head>
  <meta charset='utf-8'>
  <title>Administration Console - SeeYourImpact.org</title>
  <link rel='stylesheet' type='text/css' href='<?=$dir?>/v/dbootstrap/theme/dbootstrap.css'>
  <link rel='stylesheet' type='text/css' href='<?=$dir?>/v/dbootstrap/theme/dbootstrap-steve.css'>
  <link rel='stylesheet' type='text/css' href='<?=$dir?>/v/slickgrid/slick.grid.css'>
  <link rel='stylesheet' type='text/css' href='<?=$dir?>/v/slickgrid/controls/slick.pager.css'>
  <link rel='stylesheet' type='text/css' href='<?=$dir?>/v/slickgrid/controls/slick.columnpicker.css'>
  <link rel='stylesheet' type='text/css' href='<?=$dir?>/i/steve-grid.css'>
  <link rel='stylesheet' type='text/css' href='<?=$dir?>/v/pnotify/jquery.pnotify.default.css'>

  <script type="text/javascript" src="//use.typekit.com/nbw4bxb.js"></script>
  <script type="text/javascript">try{Typekit.load();}catch(e){}</script>

  <? js_open_home(); ?>

<script type="text/javascript">
var dojoConfig = {
  baseUrl: '<?=$dir?>',
  isDebug: true,
  async: true,
  tlmSiblingOfDojo: false,
  dojoBlankHtmlUrl: 'v/dojo/resources/blank.html',
  // parseOnLoad: true,
  // cacheBust: true,

  packages: [
    { name: "dojo", location: "//ajax.googleapis.com/ajax/libs/dojo/1.8.1/dojo" }, // or v/dojo
    { name: "can", location: "//canjs.com/release/latest/amd/can", main: "../can" },
    { name: "slick", location: "v/slickgrid", main: "slick.core" },
    { name: "dbootstrap", location: "v/dbootstrap" },
    { name: "plupload", location: "<?=$wpdir?>/wp-includes/js/plupload", main: "plupload" }, // Included with wordpress
    { name: "my", location: ".", main: "main" },
    { name: "t", location: "/a/t" } // can't access from CDN until we figure out 
  ],

  paths: {
    "jquery": "//code.jquery.com/jquery-1.9.1.min",
    "jquery-local": "v/jquery-1.9.1.min",
    "pnotify": "v/pnotify/jquery.pnotify",
    "zepto": "v/zepto", // lighter jquery
    "use": "v/use" // shims
  },

  aliases: [
    ["declare", "dojo/_base/declare"],
    ["ready", "dojo/domReady"],
    ["text", "dojo/text"],

    // ["jquery", "zepto"],
    // ["can/util/library", "can/util/dojo"],

    // patch for https://github.com/bitovi/canjs/issues/108
    ["can/view/scanner", "v/can-scanner"],
    ["v/elements", "can/view/elements"]
  ],

  deps: [
    // 'jquery', <-- want to load jquery from here, but it's not Dojo AMD-compat
    'my'
  ]

};
</script>
<script type='text/javascript' src='//ajax.googleapis.com/ajax/libs/dojo/1.8.1/dojo/dojo.js' defer></script>
</head>

<body class="bootstrap" id="body">
<!-- style="width:100%;height:100%;margin:0;padding:0;overflow:hidden;">-->
</body>

</html> 
