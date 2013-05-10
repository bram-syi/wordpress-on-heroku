<?php $ilogo = get_option('mybrandadminlogo'); $ibg = get_option('mybrandadminbg'); ?>
<style type="text/css"> 
	#header-logo { width:40px;height:40px; background : transparent url(<?php echo $ilogo; ?>) no-repeat scroll center center;} 
	html { background : url(<?php echo $ibg; ?>) repeat; }
	#footer-upgrade {color:transparent;display:none;} 
	#footer {background-color:transparent;border-top:0px;} 
</style>