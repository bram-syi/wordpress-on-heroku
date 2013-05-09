if (!window.BlobBuilder && window.WebKitBlobBuilder)
    window.BlobBuilder = window.WebKitBlobBuilder;

function ajax_error(xhr, status, e) {
  var foo = 2;
  // TODO :)
}
function ajax_html_call(xhr) {
  xhr.setRequestHeader("ajax-method","html");
}

function trackViews() {
  if (!$.fn.trackViews || !window.trackViews)
    return;

  $("body").trackViews();
  $(window).bind("scroll resize", $.trackViews);
}
trackViews();

function user_did(action, params) {
  if (!window.analytics)
    return;

  window.analytics.track(action, params);
}
function user_view(selector, action, params) {
  if ($(selector).length <= 0)
    return;

  if ($.isFunction(params))
    params = params($(this));
  user_did(action, params);
}

function user_click(selector, action, params) {
  $(selector).live('click', function(ev) {
    if ($.isFunction(params))
      params = params($(this));
    user_did(action, params);
  });
}

function set_focus(n) { 
  n = n || $("body");

  // Set cursor focus on the first empty field of class "focused"
  var focused = n.find(".focused:visible");
  for (var i = 0; i < focused.length; i++) {
    var field = $(focused.get(i));
    if (field.val() == '' && isScrolledIntoView(field)) {
      field.focus();
      break;
    }
  }
}

// http://stackoverflow.com/questions/487073/jquery-check-if-element-is-visible-after-scrolling
function isScrolledIntoView(elem)
{
  var docViewTop = $(window).scrollTop();
  var docViewBottom = docViewTop + $(window).height();

  var elemTop = $(elem).offset().top;
  var elemBottom = elemTop + $(elem).height();

  return ((elemBottom >= docViewTop) && (elemTop <= docViewBottom)
    && (elemBottom <= docViewBottom) &&  (elemTop >= docViewTop) );
}

$(function() {
  $(".expanded.js-hide").removeClass('expanded');

  // Turn captioned images into paperclipped photos
  if (typeof clip_captions == 'function')
    clip_captions();

  // Disable forms when classed "no-enter"
  $("form.no-enter input").live('keypress keydown', function(e) {
    if (e.which == 13)
      e.preventDefault();
  });
  $("#cc_num").live('keypress keydown', function(e) {
    if (e.which == 32 || e.which == 189 || e.which == 45)
      e.preventDefault();
  });

  if ($.pjax) {
    $("a.pjax").pjax('#container', {
      timeout: null
    });
  }

  // Confirm links
  $("a.confirm-ok[@alt]").live('click', function(ev) {
    return confirm($(this).attr('alt'));
  });

  // FAQ clicks to expand/collapse
  // $("dl.faq dt, dt.faq").first().next('dd').andSelf().addClass('expanded');
  $("dt.faq, dl.faq dt").live('click', function(ev) {
    var el = $(this);
    if (el.hasClass('expanded')) {
      el.removeClass('expanded');
      el.next('dd').removeClass('expanded');
    } else {
      el.addClass('expanded').siblings().removeClass('expanded');
      el.next('dd').addClass('expanded');
    }
    $(".progress-widget").trigger('rszpw');
  });

  // Automatically handle expand and collapse clicks
  $(".expander").live('click', function() {
    var panel = $(this).closest('.collapser');
    var ifc = panel.find('.if-collapsed');
    var ife = panel.find('.if-expanded');

    var t1=1000, t2=600;
    if (panel.hasClass('fast') || $(this).hasClass('fast')) {
      t1 = t2 = 0;
    }

    if (panel.hasClass('expanded')) {
      if (!$(this).hasClass('expand')) {
        ifc.filter(':not(.slide)').fadeIn(t1);
        ifc.filter('.slide').slideDown(t2);
        ife.filter(':not(.slide)').fadeOut(t1);
        ife.filter('.slide').slideUp(t2);
        panel.removeClass('expanded');
      }
    } else {
      if (!$(this).hasClass('collapse')) {
        ife.filter(':not(.slide)').fadeIn(t1);
        ife.filter('.slide').slideDown(t2);
        ifc.filter(':not(.slide)').fadeOut(t1);
        ifc.filter('.slide').slideUp(t2);
        panel.addClass('expanded');
      }
    }
    set_focus(panel);
  });

  $(".standard-form .labeled input, .standard-form .labeled select").each(function(i, el) {
    var label = $(el).closest('.labeled').find('label');
    label.data('color', label.css('color'));

    // Is blank AND selected text is blank
    var empty = $(el).val() == '' && ($(el).find('option:selected').text() || '') == '';
    var color = label.hasClass('stay') ? label.data('color') : 'white';
    if (!empty)
      label.css({ color: color });
  });
  $(".standard-form").on("input paste change focusin focusout", ".labeled input", function(ev) { 
    var label = $(this).closest('.labeled').find('label');
    var empty = $(this).val() == '';
    var color = label.hasClass('stay') ? label.data('color') : 'white';
    switch (ev.type) {
      case 'focusin': 
        label.stop().animate({ color: empty ? '#ccc' : color }, 300);
        break;
      case 'focusout': 
      case 'input': 
      case 'paste': 
      case 'change': 
        label.stop().animate({ color: empty ? label.data('color') : color }, 200);
        break;
    }
  });

  var currentSel;
  var lastOrg;
  // Fundraiser search box
  if ($.fn.select2) {
    $("#fr-search").select2({
        placeholder: "Search for a fundraiser",
        minimumInputLength: 1,
        ajax: {
            url: "//" + window.location.host + "/ajax-campaign.php",
            dataType: 'jsonp',
            quietMillis: 100,
            data: function (term, page) { // page is the one-based page number tracked by Select2
                return {
                    q: term, //search term
                    page_limit: 10, // page size
                    page: page // page number
                };
            },
            results: function (data, page) {
                lastOrg = null;
                var more = (page * 10) < data.total; // whether or not there are more results available

                // Support paging? more: true
                return {results: data.orgs, more: false};
            }
        },
        formatResult: function(org) {
          var markup = "";

          if (!lastOrg || (lastOrg.type != org.type))
            markup += "<div class='select2-hdr'>" + org.type + "</div>";
          lastOrg = org;

          markup += "<div class='clearfix'>";
          if (org.image !== undefined) {
              markup += "<img class='org-image' src='//res.cloudinary.com/seeyourimpact/image/fetch/w_50,h_50,c_thumb,g_faces/" + org.image + "'/>";
          }
          markup += "<div class='org-info'><span class='org-name'>" + org.name + "</span>";
          if (org.location !== undefined) {
              markup += "<div class='org-location'>" + org.location + "</div>";
          }
          markup += "</div></div>";
          return markup;
        },
        formatSelection: function(org) {
          currentSel = org;
          return org.name
        },
        formatInputTooShort: function(term,minLength) {
          return "Please enter the name of a fundraiser or organization, or <a href='/partners'>see all charity partners</a>.";
        },
        formatNoMatches: function(term,minLength) {
          return "No matches found.";
        }
      }).on('change', function(ev, val) {
        if (!currentSel || !currentSel.url)
        return;
      window.location = currentSel.url;
    });
  }

  if ($.fn.scrollable) {
    $('.gift-row').scrollable({
      onBeforeSeek: function(e, i) {
        var width = 3;
        if ((i != 0) && (i > this.getSize() - width))
          e.preventDefault();
      },
      onSeek: $.trackViews
    });

    // Any scrollable with more than 3 gifts should have visible scrolling arrows
    $('.scrollable').each(function() {
      $(this).find('.gift:eq(3)').each(function() {
        var x = $(this).closest(".scrollable").siblings(".home-frame");
        x.find(".nav").removeClass("notyet").fadeIn();
      });
    });

  }

  // A click on the main photo is like a click on the first button
  $(".home-page #featured-photo").live('click', function(ev) {
    var a = $(ev.target).closest('a');
    if (a.length > 0) {
      window.location = $(a).attr('href');
      return false;
    }
    $(this).find('a:eq(0)').click();
  });

  // "Add more gifts" link in the cart
  $('.add-more-gifts').click(function(ev) {
    window.location = $("#logo").attr('href') + "/give/";
    ev.preventDefault();
  });

  // Clear the BlockUI defaults
  if ($.blockUI)
    $.blockUI.defaults.css = {};

  // Prevent dragging on elements of class "ui-x"
  $(".ui-x").each(function() {
    $(this).attr("unselectable", "on")
      .css("MozUserSelect", "none")
      .bind("selectstart.ui", function() { return false; })
      .bind("dragstart.ui", function() { return false; });
  });

  set_focus();

  // If there is a progress bar, resize it to fit the stats
  var bar = $(".stats .meter");
  if (bar.length > 0) {
    var left_stats = $(".stats2 .left:last");
    var right_stats = $(".stats2 .right:first");

    var r = left_stats.position().left + left_stats.outerWidth(true);
    var l = right_stats.position().left;
    var m = bar.outerWidth(true) - bar.width();
    var w = l - r - m - 30;
    bar.width(w);
  }

  // Handle tip explanation
  $("#tip").live('change', function() {
    var amt = $(this).val();
    if (amt == '0')
      $("#more-info-tip-link").addClass("red");
    else
      $("#more-info-tip-link").removeClass("red");
  });
  $("#more-info-tip-link").hover(
    function() { $("#more-info-tip").show(); },
    function() { $("#more-info-tip").hide(); }
  );


  // Toggle featured stories
  $(".set-featured").live('click', function(ev) {
    var f = $(this);
    if (f.hasClass('feature-loading'))
      return false;

    data = {
      id: this.id,
      featured: !f.hasClass('is-featured')
    };
    if (data.featured == true)
      f.addClass('is-featured');
    else
      f.removeClass('is-featured');
    f.addClass('feature-loading');
    $.post('/ajax-story.php', data, function(resp, status, xhr) {
      f.removeClass('feature-loading');
    });
    return false;
  });

  // Handle pledges
  var p = $.cookie('PLEDGE') || '';
  if (p != '') {
    var id = ".pledge-" + p.replace('|','_');
    p = $(id);
    if (p.length > 0) {
      p.css({fontWeight: 'bold'});
      $("#pledge").addClass('pledged').find('h2').html('Thanks for your pledge!');
      var amount = p.find('.pledge-amount').html();
      $("#your-pledge").html(amount);
      $("#pledge input[type=text]").replaceWith('<input type="hidden" name="name" />');
      $("#pledge .pledge-amount").html(amount).css('font-size','140%');
      $("#pledge-info").removeClass('hidden');
      $("#pledge:not(.closed) .button").text('Change your pledge');
    }
  }


  $(".open-pledge-list").live('click', function(ev) {
    ev.preventDefault();
    $.colorbox({
      href: $(this).attr('href'),
      transition:'elastic',
      opacity: 0.3,
      speed:300,
      current: '',
      initialWidth: 50,
      initialHeight: 50,
      onLoad: function() {
        $("#cboxClose").hide();
      },
      onComplete: function() {
        $("#cboxClose").fadeIn();
        set_focus();
      },
      onClosed:function() { 
	    window.location.reload(); 
	  },
      open: true
    });
  });

  $("#pledge-form").live('submit', function(ev) {
    ev.preventDefault();

    var form = $(this);
    form.block({
      message: '<b>Submitting your changes</b>',
      overlayCSS: { opacity: 0.3 }
    });
    $.ajax({
      type: 'POST',
      url: form.attr('action'),
      data: form.serialize(),
      headers: {"AJAX-Method":'AJAX'},
      success: function(data) {
        if (data == "OK") {
/*
          var url = window.location + '';
          $("a[name=social]").remove();
          window.location = url.split('#')[0] + '#social';
*/
          window.location.reload(true);
          return;
        } else if (data == "OK-LIST") {
          $('#pledge-info').show();
    }
//        form.replaceWith(data);
        $.colorbox.resize();
        set_focus();
        $(document).unblock();
      }
    });
  });


  // Animate user boxes
  $(".avatar-link:has(.user-tag)").live('hover', function(ev) {
    var a = $(this).find('.avatar');
    var tag = $(this).find('.user-tag');

    var anim1 = 'swing',
     anim2 = 'swing';

    if (ev.type == 'mouseenter') {
      a.stop().animate({
        width: [60, anim1],
        left: [-10, anim1],
        height: [60, anim2],
        top: [-10, anim2],
        rotate: -6
      }, 300);
      tag.css({
        width: 0, opacity: 1
      }).animate({
        width: tag.find('div').outerWidth(), opacity: 1
      });
    } else {
      a.stop().animate({
        width: [40, anim1],
        left: [0, anim1],
        height: [40, 'easeInOutQuart'],
        top: [0, anim2],
        rotate: 0
      }, 300);
      tag.stop().animate({
        width:0, opacity: 0
      });
    }
  });

  // Auto-share to twitter or facebook (FB soon)
  if ($.fn.qtip) {
  $(".to-share p:not(.no-share)").qtip({
    content: { text: 'Click to share this on Twitter' },
    position: {
      my: 'bottom center',
      at: 'top center',
      adjust: { y: 12 }
    }, style: {
      classes: 'ui-tooltip-shadow ui-tooltip-jtools'
    }
  }).live('click', function(ev) {
    var width  = 550,
        height = 300,
        left   = ($(window).width()  - width)  / 2 + 200,
        top    = ($(window).height() - height) / 2

    var text = $(this).text();
    if (text.length > 120)
      text = text.substring(0, 117) + "...";
    else {
      var title = $(document).attr('title');
      if (text.length + title.length < 119)
        text = title + ' ' + text;
      else {
        var title = $("h1").text();
        if (text.length + title.length < 119)
          text = title + ' ' + text;
      }
    }

    var p = {
      url: $("link[rel=canonical]").attr('href') || window.location,
      text: text,
      related: 'SeeYourImpact'
    };
    var url = 'http://twitter.com/share?' + $.param(p);
    window.open(url, 'share', ['status=0,location=0,resizable=0,scrollbars=0,toolbar=0,width=',width,',height=',height,',top=',top,',left=',left].join(''));

    ev.preventDefault();
  });
  }

  $(".closer").live('click', function() {
    $(this).closest('.closable').fadeOut(500);
  });

  // Form fields
  $('.error-field').live('input paste change', function(e) {
    $(this).removeClass('error-field');
  });

  function untab_other(e) {
    $("#state_other").attr('tabIndex', $(e).val() == '' ? 0 : -1);
  }
  untab_other($(".cc_form #state").change(function(ev) { untab_other(this); }));

  $("#cvv_link").hover(
    function() { $("#cvv_img").show(); },
    function() { $("#cvv_img").hide(); }
  );

  function dotsize(el, r, r2) {
    el = $(el);
    el = el.find('.center');
    el.stop().animate({
      margin: -r,
      borderWidth: r
    }, 300, function() {
      if (r2 == null)
        return;
      el.stop().animate({
        margin: -r2,
        borderWidth: r2
      }, 200);
    });
  }

  $(".team-page .dot").each(function(e) {
    var dot = $(this).show(); //fadeIn(100);
    $('<div class="center" />').appendTo(dot);
    var url = window.location + '';
    if (url.indexOf(dot.attr('href')) !== -1)
      dot.addClass('selected');
  }).filter(":not(.selected)").on({
    mouseover: function(ev) {
      dotsize(this, 6);
    },
    mouseout: function(ev) {
      dotsize(this, 3);
    }
  });

  // Pledges
  $('.amount-row .amount').live('input paste change', function(e) {
    $(this).closest('.amount-row').siblings('.amount-row').find('.amount').val('');
  });

  // Fit videos
  if ($.fn.fitVids) {
    $(".fr-update").fitVids();
  }

/*
  // Google Analytics event tracking
  $('.ev').live('click', function() {
    var id = $(this).attr('ID');
    var action = $(this).closest('.evs').attr('ID') || 'user-action';
    var _gaq = window._gaq || [];
    _gaq.push(['_trackEvent', action, 'click', id]);
  });
  $(".conv").live('click', function() {
    var _gaq = window._gaq || [];
    _gaq.push(['_trackEvent','convert','donate', window._donor]);
  });
*/

  // Track events
  user_click('.pay-button', "Add gift to cart");
  user_click('.conv.button', "Pay for cart");
  user_click('.home-page .sidebar-panel .button', 'Click "See ways to help"');
  user_click('#give-link', 'Click "Give now"');
  user_click('#apply-gift-code', 'Apply a gift code');
  user_click('#facebook-share', "Share on Facebook");
  user_click('#invite-friends', "Invite Friends");
  user_click('#frame.home-frame .nav', "Use the home page slider");
  user_click('#frame.home-frame .panel .button', "Click a home page panel");
  user_click('.causes_widget a', "Choose a need from home page");
  user_click('.gift-row .gift, #gifts .gift', "Click on a gift");
  user_click('#gifts .paging, #gifts .page-num', "Page through the gift browser");
  user_click('#give-any', 'Add a "Give Any" amount');
  user_click('.story', 'Click on a story');
  user_click('.sidebar .widget', 'Click a sidebar widget');
  user_click('.story-slideshow .slide', 'Choose a profile story');

  user_view('.single-event:not(.my-profile), .profile-page.campaign:not(.my-profile)', "View a fundraiser");
  user_view('.my-profile.campaign.edit', 'Edit my fundraiser');
  user_view('.my-profile.campaign:not(.edit)', 'View my fundraiser');
  user_view('.profile-page:not(.my-profile):not(.campaign)', 'View a profile');
  user_view('.my-profile.profile', 'View my profile');
  user_view('.my-profile.settings', 'View my profile settings');
  user_click('#start-campaign', 'Click to start campaign');


  // File uploads

  $('.image-uploader').each(function(i, el) {
    var u = $(el);

    var uploader, uploader_main;
    var plu_runtimes = 'html5, html4';
    var plu_flash = '/wp-includes/js/plupload/plupload.flash.swf';
    var plu_sl = '/wp-includes/js/plupload/plupload.silverlight.xap';
    var plu_url = '/ajax-campaign.php?';
    var plu_max = '8mb';
    var plu_chunk = '150kb';
    var plu_resize = [{width : 540, height : 360, quality : 80}];
    var plu_filters = [{title : "Image files", extensions : "jpg,gif,png,jpeg"}];
    var w; // progress bar width

    function upload_main_start(up) {
      if (up.runtime == 'html4')
        up.settings.url=up.settings.url+'&html4';

      u.append($("<div class='progress1 ui box'><div class='progress2 box'/></div>"));
      u.find('.image-button').fadeOut(400);
      w = u.find('.progress1').width();

      up.start();
      up.refresh();
    }

    function upload_main_progress(up, file) {
      if (uploader_main.runtime!='html4') {
        u.find('.progress2').animate({
          width: (file.percent * w) / 100
        }, 50);
      }
    }

    function upload_main_finish(up, file, ret) {
      var data = ret.response;
      if (up.runtime == "html4") {
        data = $("<div/>").html(data).text();
      }
      data = data || '';

      var s = data.toString();
      if (s.indexOf('<img') >= 0) {
        u.find('img').remove();
        u.prepend(data).find('img').hide().fadeIn(100);
        u.addClass('has-picture');

        u.find('.progress2').stop().animate({
          width: w
        }, 50, function() {
          u.find('.progress1').remove();
        });
        u.find('.image-button').removeClass('green-button').addClass('gray-button');

      } else { 
        if (s.indexOf('Error') >= 0) {
        } else if (s == 'File size error') {
          s = 'Sorry, that file is too large.  Photos may be up to 4mb. ';
        } else {
          s = 'Sorry, the upload failed. Please try again.';
        }

        u.find('.progress1').remove();
        alert(s);
      }

      u.find('.image-button').fadeIn(100);
      up.refresh();
    }

    function upload_main_error(up,err) {
      upload_main_finish(up,'',err.message);
    }

    var AJAX_KEY = $("#ajax-key").val();

    var uploader_main = new plupload.Uploader({
      runtimes : plu_runtimes,
      browse_button : 'button', //u.find('.image-button').first().attr('id'),
      drop_element : u.attr('id'),
      container : u.attr('id'),
      max_file_size : plu_max,
      unique_names: true,
  //    chunk_size: plu_chunk,
      url: plu_url + 'key=' + AJAX_KEY,
      flash_swf_url: plu_flash,
      silverlight_xap_url: plu_sl,
      resize : plu_resize,
      filters : plu_filters
    });
    uploader_main.init();
    uploader_main.bind('FilesAdded', upload_main_start)
    uploader_main.bind('UploadProgress', upload_main_progress)
    uploader_main.bind('FileUploaded', upload_main_finish)
    uploader_main.bind('Error', upload_main_error);
  });

});

$('.xpay-button').live('click', function() {
  var b = $(this);
  if (b.closest(".no-ajax").length > 0)
    return true;

  if (b.hasClass('working'))
    return false;

/* verify it's a gift link
  var re = /item=.*(\d+)/;
  var match = re.exec($(this).attr('href'));
  var gid = parseInt(match[1]);
  if (gid == 0)
    return true;
*/

  b.addClass('working');
  b.css('opacity', 0.5);
  var c = $(".cart-display");
  if (!c.is(":visible")) {
    $(".cart-count").html('1 gift');
    c.show();
  }

  $.blockUI({
    message: null,
    overlayCSS: { opacity: 0 }
  });
  $.ajax({
    //type: 'GET',
    url: b.attr('href'),
    dataType: 'html',
    cache: false,
    beforeSend: ajax_html_call,
    error: ajax_error,
    success: function(resp) {
      $(document).unblock();
      $("#checkout").replaceWith(resp);

      var co = $("#checkout");

      var c = parseInt($("#cart_count").val());
      if (c < 1) c = 1;
      $(".cart-count").html(c + ' gift' + (c == 1 ? '' : 's'));
      b.removeClass('working');
      b.css('opacity', 1);

      $.colorbox({
        inline: true,
        href: co, //'#checkout',
        transition:'elastic',
        open: true,
        speed:100,
        rel: 'nofollow',
        initialWidth: 200,
        initialHeight: 20,
        opacity: 0.3,
        onLoad: function() {
          $("#cboxClose").hide();
        }
      });

    }
  });
  return false;
});

// CART functions

$(".remove-checkbox").live('click', function() {
  $(this).closest(".cart-item").fadeOut(1000);
  return true;
});




// COLORBOX (popup) functions

$("#colorbox .closebox").live('click', function(ev) {
  $.colorbox.close();
  ev.preventDefault();
});

$("#cboxContent .cart-item select").live('change', function() {
  var item = $(this).closest(".cart-item");
  var form = item.closest('form');

  form.block({
    message: 'Updating your cart...',
    overlayCSS: { opacity: 0 }
  });
  item.animate({ opacity: .5}, 300);
  $.ajax({
    type: 'POST',
    url: form.attr('action'),
    data: form.serialize(),
    dataType: 'html',
    cache: false,
    beforeSend: ajax_html_call,
    error: ajax_error,
    success: function(resp) {
      form.unblock();
      item.replaceWith($(resp));
    }
  });

  return true;
});

// CloudSponge

$(".invite-button, .inv-button").live('click', function(ev) {
  ev.preventDefault();
  window.currentInvite = this.href;
  $(this).colorbox({
    href: this.href, transition:'elastic', iframe: false,
    open: true, opacity: 0.4, speed:300, rel: 'nofollow',
    initialWidth: 50, initialHeight: 50, width: 690, height: 'auto', 
    onLoad: function() { $("#cboxClose").hide(); },
    onComplete: function() { if (clip_captions) clip_captions(); $("#cboxClose").fadeIn(); }
  });
  return false;
});
$("#invite-page #add-message").live('click', function() {
  $(this).fadeOut(300);
  $("#invite-message-container").fadeIn(300).removeClass('hidden');
  $.fn.colorbox.resize();
});

// Remove the ugly Facebook appended hash
// <https://github.com/jaredhanson/passport-facebook/issues/12>
if (window.location.hash && window.location.hash === "#_=_") {
  if (Modernizr && Modernizr.history) {
    window.history.pushState("", document.title, window.location.pathname + window.location.search);
  } else {
    // Prevent scrolling by storing the page's current scroll offset
    var scroll = {
      top: document.body.scrollTop,
      left: document.body.scrollLeft
    };
    window.location.hash = "";
    // Restore the scroll offset, should be flicker free
    document.body.scrollTop = scroll.top;
    document.body.scrollLeft = scroll.left;
  }
}

/*
$(".tab-strip .tab").click(function() {
  var tab = $(this);
  var tabs = tab.closest(".tab-strip");

  var t = $("#" + tabs.attr('id') + "-" + tab.attr('id'));

  tab.addClass("selected-tab").siblings().removeClass('selected-tab');
  t.addClass("current-panel").siblings().removeClass('current-panel');
});
*/

/*
HTML5 history to do fast page loads.
But until we have faster page loads it doesn't make much of a difference.

$(function() {
  var pushed = false;

  $(window).on('popstate pushstate', function(e) {
    if (!pushed)
      return;
    pushed = true;
    loadPage(location.pathname + location.search);
  });

  function loadPage(url) {
    var page = $("#container").addClass('loading');
    $.get(url, function(data) {
      var d = $(data);
      page.replaceWith(d.find("#container"));
      window.scrollTo(0, 0);
    });
  }
 
  $(".causes-widget a").live('click', function(e) {
    e.preventDefault();
    var href = $(this).attr('href');
    loadPage(href);
    history.pushState(null, null, href);
  });

});
*/

// This is used to bring up the invitation UI on fundraiser pages when
// the url of the fundraiser page includes "show_invite" as a query param
$(document).ready(function() {
    $(window).load(function() {
        $('.click-on-page-load').click();
    });
});
