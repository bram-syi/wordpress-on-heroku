<?php 

/* Begin My Brand Login Functions. This Contains the CSS and JS For The Login Page. Edit At Own Risk. */

function mybrandlogin_notice() { ?>

<br /><div class="update-message" style="background:#731c1c;color:#fff;width:63%;">&nbsp;&nbsp;&nbsp;The Next Version of My Brand Login is Available</div>

<?php 
	}
function my_brand_body(){ ?>

<link rel="stylesheet" href="<?php echo plugins_url('my-brand/loader/templates/css/css-login.css'); ?>" type="text/css" />
<?php include('login-style.php'); ?>
<?php 
	}
function my_brand_js() { ?>

<script type='text/javascript' src='<?php echo plugins_url('my-brand/loader/js/jquery-latest.js'); ?>'></script>
<?php 
	}
/* End My Brand Login CSS and JS Functions */

function my_brand_template() { ?>

<link rel="stylesheet" href="<?php echo plugins_url('my-brand/loader/templates/uploads/style.css'); ?>" type="text/css" />
<?php }

/* Begin Admin Footer Text Version and Admin Page Functions */

   function mybrandlogin_remove_version()
     { 
	return '';
    } 
	function remove_footer_admin ()

 { ?> Copyright &copy; <?php bloginfo('title'); ?>

<?php
	 }

	function change_logo_link ()

 { ?> <?php bloginfo('url'); ?> 

<?php
	 }

	function change_logo_title ()

 { ?> <?php bloginfo('title'); ?> 

<?php
	 } 

	function mybrand_gravatar ($avatar_defaults)
 {
	$mybrandavatar = plugins_url('my-brand/loader/images/bravatar.png');

		$avatar_defaults[$mybrandavatar] = "Bravatar (MBL)";

	return $avatar_defaults;
	}

	function my_brand_admin()

 { ?> 

<?php $ilogo = get_option('mybrandadminlogo'); $ibg = get_option('mybrandadminbg'); ?>
<style type="text/css"> 
	#header-logo { width:40px;height:40px; background : transparent url(<?php echo $ilogo; ?>) no-repeat scroll center center;} 
	html { background : url(<?php echo $ibg; ?>) repeat; }
	#footer-upgrade {color:transparent;display:none;} 
	#footer {background-color:transparent;border-top:0px;} 
</style>
<?php 
	}

/* End Admin Footer Text and Admin Page Functions */ ?>
