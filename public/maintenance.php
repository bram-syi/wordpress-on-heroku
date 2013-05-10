<?

header('Expires: Wed, 11 Jan 1984 05:00:00 GMT');
header('Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT');
header('Cache-Control: no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

if ($_GET['maintain'] != null) {
  setcookie('maintain', $_GET['maintain'], 0, '/', '.seeyourimpact.org');
  header('Location: /');
  die();
}

if ($_GET['url'] != null)
  $url = trim($_GET['url']);
if (empty($url))
  $url = "/";

if ($_GET['iamsyi'] != null) {
  setcookie('iamsyi', $_GET['iamsyi'], time()+60*60*24*1000, '/', '.' . $_SERVER['SERVER_NAME']);
  header("Location: $url");
  die();
}

?>

<html><head>
<title>Maintenance</title>
<style>
body { font-family: Arial; }
</style>
</head>
<body style="text-align:center; padding: 100px;">
<img src="/wp-content/images/maintenance.jpg" />
</div>
<!-- <?= $_SERVER["REMOTE_ADDR"] ?> -->
</body>
</html>
