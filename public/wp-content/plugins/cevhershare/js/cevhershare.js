/*
 *  CevherShare - Creates a dynamic sharing bar for WordPress posts
 *  Copyright 2010-2011 Rashad Aliyev, http://wpusta.com/
 *  Released under the MIT, BSD, and GPL Licenses.
 *  More information: http://wpusta.com/cevhershare
 */
jQuery.fn.cevhershare = function(options) {
	var defaults = {horizontal: true, swidth: 65, minwidth: 1000, position: 'left', leftOffset: 20, rightOffset: 10};
	var opts = jQuery.extend(defaults, options); var o = jQuery.meta ? jQuery.extend({}, opts, jQueryjQuery.data()) : opts;

	var w = jQuery(window).width();
	var cevhershare = jQuery('#cevhershare');
	var cevhersharex = jQuery('#cevhersharex');
	var parent = jQuery(cevhershare).parent().width();
	var start = cevhershare_init();

	function cevhershare_init(){
		jQuery(cevhershare).css('width',o.swidth+'px');
		if (o.position == 'left') jQuery(cevhershare).css('marginLeft',(0-o.swidth-o.leftOffset));
		else {
			jQuery(cevhershare).css('marginLeft',(parent+o.rightOffset));
		}
		if(w < o.minwidth && o.horizontal) jQuery(cevhersharex).slideDown();
		else jQuery(cevhershare).fadeIn();
		jQuery.event.add(window, "scroll", cevhershare_scroll);
		jQuery.event.add(window, "resize", cevhershare_resize);
		return jQuery(cevhershare).offset().top;
	}
	function cevhershare_resize() {
		var w = jQuery(window).width();
		if(w<o.minwidth){
			jQuery(cevhershare).fadeOut();
			if(o.horizontal) jQuery(cevhersharex).slideDown();
		}else{
			jQuery(cevhershare).fadeIn();
			if(o.horizontal) jQuery(cevhersharex).slideUp();
		}
	}
	function cevhershare_scroll() {
		var p = jQuery(window).scrollTop();
		var w = jQuery(window).width();
		jQuery(cevhershare).css('position',((p+10)>start) ? 'fixed' : 'absolute');
		jQuery(cevhershare).css('top',((p+10)>start) ? '10px' : '');
	}

};