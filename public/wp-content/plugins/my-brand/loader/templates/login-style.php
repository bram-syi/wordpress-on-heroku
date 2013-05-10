<?php 
	$imgurl = get_option('mybrandimg'); 

	$bodyclr = get_option('mybrandbody'); 

	$bodypic = get_option('mybrandpic'); 

	$bodytop = get_option('mybrandbdytp'); 

	$backlogin = get_option('mybrandbaklgin');
 
	$lgnnav = get_option('mybrandloginnav'); 

	$formposi = get_option('mybrandformposition');
 ?>
<style type="text/css">
html{background-color:<?php echo $bodyclr; ?>;background:<?php echo $bodyclr; ?>!important;}
body{background: url(<?php echo $bodypic; ?>) repeat; } 
h1 a { background: url(<?php echo $imgurl; ?>) no-repeat; } 
body.login{border-top-color:<?php echo $bodytop; ?>;}
form {background:url(<?php echo $backlogin; ?>); } 
.login #nav a{color:<?php echo $lgnnav; ?>!important;}
label{color:<?php echo $bodytop; ?>; }
#backtoblog a {color:<?php echo $lgnnav; ?>!important;} 
#login {margin:<?php echo $formposi; ?>;}
</style>
