
$.fn.refade = function(i, b) {
  this.hide().fadeIn(i);
  return this;
}

$.fn.createGiftBrowser = function() {
  if (this.length == 0)
    return this;

  var t = $('.crumbs-give .current');

  function set_gb_title(str, data) {
    if (timer != null)
      clearTimeout(timer);

    if (t == null || t.length == 0)
      return;

    var i = parseInt(str);
    if (str === 0 || i > 0) {
      if (i <= 0) {
        t.html('There are no matching organizations.');
        return;
      }

      var c = t.data('curVal');
      if (c == null)
        c = 0;
      else
        c = parseInt(c);
      var timer = null;

      var title_counter = function() {
        if (c < i) c++;
        else if (c > i) c--;

        cr = '<a href="/give/">Give Now</a>';

        switch (c) {
          case 0:
            break;
          case 1:
            cr = cr + ' &gt; <b>1</b> gift option'; break;
          default:
            cr = cr + ' &gt; <b>' + c + ' ways</b> to see your impact!'; break;
        }

        // Is this a subset selection?
        if (data['tags[]'] != null || data.min_amt > 0 || data.max_amt > 0)
          cr = cr + ' (<a href="/give/#"><u>see all</u></a>)';

        t.html(cr);
        t.data('curVal', c);

        var gap = 100 / (i - c);
        if (gap < 0) gap = -gap;

        if (c != i)
          timer = setTimeout(title_counter, gap);
      }

      title_counter();
      return;
    }

    t.data('curVal', 0);
    t.html(str);
  }

  this.each(function() {
    var gb = $(this);

    var giftList = gb.find('.gift-list').scrollable({
      easing: 'easeInOutSine',
      speed: 500,
      onBeforeSeek: function(ev, index) {
        var page = gb.find(".gift-page").eq(index).find('.gift');
        page.goLoad();
      },
      onSeek: function() {
        $.trackViews();
        var index= this.getIndex();
        var size = this.getSize();
        $("#gift-paging .page-num").eq(index).radioClass('selected');
        $("#gift-paging .prev-gifts").fade(index > 0);
        $("#gift-paging .next-gifts").fade(index < size-1);

        if (index > 0)
          $("#gift-paging .pages").fadeIn();
      },
      next: '.next-gifts',
      prev: '.prev-gifts'
    });
    var scrl = giftList;
    if (giftList.data)
      giftList = giftList.data('scrollable');
    else
      giftList = null;

    var current_gift_request = null;
    var current_state = "";

$.fn.serializeObject = function()
{
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

    var api = { 
      el: gb,
      
      load: function(state) {

        // Generate the tags
        var gift = state.gift || '';
        var loc = null;
        if (state.gift)
          loc = gift.replace(/\/[^/]+$/, '');
        gift = parseInt(gift.match(/[^/]+$/)); // # after last slash

        if (state.tags)
          syiTags = 'g/' + state.tags;
        else if (state.cost > 0)
          syiTags = 'g/c' + state.cost;
        else
          syiTags = loc || 'g/all';

        if (gift > 0) {
          gb.trigger('details', [gift, syiTags + '/i']);
          return;
        }

        var form = $("form#gift-browser-sidebar");
        var data = {
          gpg: syiTags,
          page: 0,
          limit: 75
        };
        if (form.length > 0) {
          data = form.serializeObject();
          data.gpg = syiTags;
        } else if (state.tags) {
          data['tags[]'] = state.tags.split(',');
        }

        switch (parseInt(state.cost || "0")) {
          case 1:
            data.min_amt = 0;
            data.max_amt = 33;
            break;
          case 2:
            data.min_amt = 21;
            data.max_amt = 63;
            break;
          case 3:
            data.min_amt = 45;
            data.max_amt = 133;
            break;
          case 4:
            data.min_amt = 90;
            data.max_amt = 283;
            break;
          case 5:
            data.min_amt = 240;
            break;
        }

        var s = $.param(data);

        //gb.find('#gift-paging .next-gifts').fade(false);
        if (gb.hasClass('preloaded')) {
          current_state = s;
          gb.trigger('browse');
          gb.removeClass('preloaded');
          api.loaded();
          return;
        }

        if (s == current_state) 
          return;
        current_state = s;

        // Remove any tracked buttons
        $(".gift-list .tracked").removeClass('tracked');

        gb.addClass('gb-loading');

        // Fetch new page of gifts
        if (current_gift_request != null)
          current_gift_request.abort();
        data.cmd = 'browse';
        current_gift_request = $.get( "/ajax-gifts.php", data, function(results) {
          gb.trigger('browse');
          gb.removeClass('gb-loading');
          gb.find(".gift-page").remove(); // TODO: adjust current gifts rather than restarting 

          var items = gb.find(".gift-list .items");
          results = results.replace(/img src/g,'img xsrc');
          items.html(results).wrapImages();//.evalScripts();
          set_gb_title(items.find('.gift').length, data);

          var navi = gb.find('.pages').html('').hide();
          var c = items.find('.item').length;
          for (var i = 0; i < c; i++) {
            $("<span class='ui page-num'>").appendTo(navi).html(i+1);
          }

          api.loaded();
        });

        return gb;
      },

      loaded: function() {
        var items = gb.find(".gift-list .items");
        items.refade(400);
        items.find('.page-title:visible')./*css('opacity',.4).*/fadeIn();

        var c = 0;
        items.find('#page_0 .gift').each(function(i,g) {
          var gift = $(g);
          gift.addClass('invisible');
          setTimeout(function() {
            gift.find('.pic').refade(500, true);
            gift.find('.desc').refade(1000, true);
            gift.removeClass('invisible');
          }, c * 150);
          c++;

        });

        // Reset to first page
        if (giftList)
          giftList.begin();
      }
    };

    gb.data('browser', api);

    gb.find('.gift .more').live('click', function(ev) {
      var tags = $.param.fragment($(this).attr('href'));
      $.bbq.pushState(tags, 2);
      ev.preventDefault();
    });

    gb.find('.gift-paging .page-num').live('click', function(ev) {
      var index = $(this).index();
      $(this).radioClass('selected');
      giftList.seekTo(index);
    });

  });

  return this;
};

$.fn.createGiftFilter = function(browser) {
  if (this.length == 0)
    return this;

  if (browser)
    browser = browser.data('browser');

  this.each(function() {
    var gf = $(this);
    var syiTags = 'g/all';

    var slider = gf.find(".range").rangeinput({
      onSlide: function(ev, value) {
        var text = 'about:';
        switch (value) {
          case 1: text = '<b>about $10 - $25</b>'; break;
          case 2: text = '<b>about $25 - $50</b>'; break;
          case 3: text = '<b>about $50 - $100</b>'; break;
          case 4: text = '<b>about $100 - $250</b>'; break;
          case 5: text = '<b>$250 or more</b>'; break;
        }
        $('#cost-label').html('for ' + text);
      },
      change: function(ev, value) {
        // STEVE: comment to allow multiselect
        gf.find('.gift-tag input').attr('checked', false);
        api.save();
      }
    }).data('rangeinput');

    var api = {
      el: gf,
      slider: slider,
      selectClass: 'green-button',

      save: function() {
        var tags = [];
        gf.find(".gift-tag :checked").each(function() {
          tags.push(this.id.replace(/^choose-/,'')); 
        });
        var cost = slider ? slider.getValue() : 0;

        var state = { };
        if (tags.length > 0)
          state.tags = tags.join(',');
        if (cost > 0)
          state.cost = cost;

        gf.trigger('changed', [state]);
      },

      load: function(state) {

        if (state.gift)
          return;

        // Update the gift tag controls
        gf.find(".gift-tag").removeClass('selected-gift-tag').removeClass(api.selectClass).find("input").attr('checked', false);
        if (state.tags != null) {
          $.each(state.tags.split(','), function() {
            gf.find("#choose-" + this).attr('checked',true).parent().addClass('selected-gift-tag ' + api.selectClass);
          });
        }

        // Update the slider
        var cost = parseInt(state.cost || '0');
        if (slider)
          slider.setValue(cost);

      }

    };

    gf.data('filter', api);   

    // Catch live clicks on the gift tags
    gf.find('.gift-tag').live('click', function(ev) {
      var check = $(this).find("input");
      var checked = check.attr('checked');
      // STEVE: comment to allow multiselect
      gf.find('.gift-tag :checked').attr('checked', false);
      check.attr('checked', !checked);

      if (slider)
        slider.setValue(0);

      api.save();

      ev.preventDefault();
    });

  });
 
  return this;
}

$(".gift-browser .story, .sample-story, .sample-stories .story-link").live('click', function(ev) {
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

// Create gift browser & tag filter
$(function() {
  var browser = $("#gifts.ajax").createGiftBrowser().bind({
    browse: function() {
      switch_panel( $("#gift-browser-sidebar") );
      switch_panel( browser );

      $(window).trigger('gift-browse');
    },
    details: function(ev, giftID, itemtag) {
      //switch_panel( $("#gift-details-sidebar") );
      switch_panel( $("#gift-details").addClass('gb-loading').html('') );
      $(window).trigger('gift-details');

      $.get( "/ajax.php?details", "gift=" + giftID, function(details) {
        //TODO: fun animation
        var panel = $("#gift-details").removeClass('gb-loading');

        details.itemtag = itemtag;
        if (details.title == '' || details.title == null) {
          var d = $("#funded_gift").render(details);
        } else if(details.varAmount>0) {//if aggregate
          var d = $("#draw_var_gift_details").render(details);
        } else if(details.towards_gift_id>0) {//if aggregate
          var d = $("#draw_agg_gift_details").render(details);
        } else {
          var d = $("#draw_gift_details").render(details);
        }

        var stories = d.find('.stories');
        $.each(details.stories, function(i, story) {
          var se = $("#story_template").render(story).appendTo(stories).data('story', story);
        });
        var x = d.appendTo(panel).refade(500).loadImages().trackViews().find('input.varAmount');
        x.focus().val(x.val());
        $("#gift-details").trigger('changed');

      }, 'json' );
    }
  });
  $('.gift-details .backlink').live('click', function(ev) {
    var api = filter.data('filter');
    if (api)
      api.save();
    else {
      var pos = $(window).scrollTop();
      $.bbq.pushState({}, 2);
      $(window).scrollTop(pos);
    }
    switch_panel( $("#gift-browser-sidebar") );
    switch_panel( browser );
    $(window).trigger('gift-browse');
    ev.preventDefault();
  });

  var filter = $(".gift-tags").createGiftFilter(browser).bind({
    changed: function(ev,state) {
      var pos = $(window).scrollTop();
      $.bbq.pushState(state, 2);
      $(window).scrollTop(pos);
    }
  });

  $(window).bind('hashchange', function(ev) {
    var state = $.bbq.getState();
    var api = filter.data('filter');
    if (api)
      api.load(state);

    api = browser.data('browser');
    if (api)
      api.load(state);
  });

  $("#gift-details").bind('changed', function() {
    var el = $("#gift-details .big-pic:not(.clipped)").addClass('bordered-photo');
    clip(el);
  });

  // Kick off the first load
  $(window).trigger('hashchange');
});





