$(function() {
  var selCss = { opacity: 1 };
  var unselCss = { opacity: 0.5 };
  var maskOpacity = 0.7;

  function post_load(item) {

    item.css(selCss).find('.item-excerpt, .gave').hide().fadeIn(200);
    item.find('.mask').css('opacity',maskOpacity);
    item.next().goLoad();
    item.find(".item-buttons a img").addTip();

	item.find('.youtube-video').each(function() {
      var player = new YT.Player($(this).attr('id'), {
        height: $(this).height(),
        width: $(this).width(),
        videoId: $(this).attr('id').replace('youtube-',''),
        autohide: 1,
        egm: 1,
        rel: 0,
        showinfo: 0,
        showsearch: 0
      });
      item.data('player', player);
    });

	item.find('.vimeo-video').each(function() {
	  var player = $('<iframe frameborder="0">').attr('id', $(this).attr('id')).attr(
	    'src','http://player.vimeo.com/video/'+$(this).attr('id').replace('vimeo-','') + '?title=0&byline=0&portrait=0&api=1&rel=0'
		).attr('width',$(this).width()).attr('height',$(this).height()).appendTo($(this));
    $(this).css('overflow','hidden');
	  item.data('player', player);
	});

	item.find(".item-image").loadImages();
  }

  $('xa.videobox').live( 'click', function(ev){
    if (!Shadowbox)
      return;

    var el = $(this);
    var href= el.attr('href');
    if (!href || href == '')
      return;

    // Convert YouTube and Vimeo links
    href = href.replace(/http:\/\/vimeo\.com\/([^\?]*).*/, 'http://player.vimeo.com/video/$1?title=0&byline=0&portrait=0&autoplay=1');
    href = href.replace(/http:\/\/www\.youtube\.com\/watch\?v=([^&^?]*).*/, 'http://www.youtube.com/embed/$1?autoplay=1&rel=0&showinfo=0&showsearch=0&egm=1&autohide=1');
    alert(href);

    Shadowbox.open({
      el: el,
      title: el.attr('title')||'',
      player: 'iframe',
      content: href
    });
    ev.preventDefault();
  });

  var gallery = $(".gallery-widget");
  if (gallery.length > 0) {
    //gallery.find('.item-thumb').loadImages();
    var scr = gallery.find(".scrollable").scrollable({
      vertical: true, 
      keyboard: false,
      onBeforeSeek: function(event,index) {
        var item = api.getItems().eq(index);
        var oldItem = api.getItems().eq(api.getIndex());
        
        var player = oldItem.data('player');
        if (player && player.stopVideo)
          player.stopVideo();
        
        item.addClass('selected').animate(selCss)
          .siblings().removeClass('selected').animate(unselCss);
        item.goLoad();
        // Already loaded?
        if (item.hasClass('loaded'))
          return true;

        var c = item.find('.item-content');
        $.ajax({
          url: '/ajax-campaign.php?p=' + item.attr('id'),
          success: function(html) {
            item.replaceWith(html);
            item = api.getItems().eq(index);
            post_load(item);
          }
        });   
      }
    });
    var api = scr.data("scrollable");
    var items = api.getItems();
    items.not('.selected').css(unselCss).show();

    var sel = items.filter('.selected').css(selCss);
    if (sel.length > 0) {
      var i = items.index(sel);
      api.seekTo(i, 0);
    }
    items.find('.mask').css('opacity', maskOpacity);

    gallery.find('.item-thumb').live('click', function(ev) {
      var i = $(this).index();
      var dist = Math.abs(i - api.getIndex());
      api.seekTo(i, dist * 200 + (dist < 3 ? 300 : 0));
      $(this).addClass('selected').siblings().removeClass('selected');

      ev.preventDefault();
    });
    gallery.find('.vertical .item').live('click', function(ev) {
      if ($(this).is('.selected'))
        return true;
      var i = api.getItems().index(this);
      var dist = Math.abs(i - api.getIndex());
      api.seekTo(i, dist * 200 + (dist < 3 ? 300 : 0));
      ev.preventDefault();
    });
    gallery.find('.story-link').live('click', function(ev) {
      $(this).colorbox({
        href: '/ajax-story.php?id=' + $(this).attr('rel'),
        transition:'elastic',
        open: true,
        opacity: 0.4,
        speed:300,
        rel: 'nofollow',
        maxWidth: 740,
        initialWidth: 50,
        initialHeight: 50,
        onLoad: function() {
          $("#cboxClose").hide();
        },
        onComplete: function() {
          clip_captions();
          $("#cboxClose").fadeIn();
        }
      });
      ev.preventDefault();
    });
    gallery.find('.move-next').live('click', function (ev) {
      api.seekTo((api.getIndex() + 1) % api.getItems().length);
      return false; // prevent default + propagation
    });
    gallery.find('.item.loaded').each(function() {
      post_load($(this));
    });
  }
});

$(function() {
  var fc = $(".featured-campaigns");
  if (fc.length > 0) {
    var scr = fc.scrollable({
      keyboard: false,
      circular: true
    }).api('scrollable');
  }
});

