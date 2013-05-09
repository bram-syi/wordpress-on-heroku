window.innerShiv = (function() {
        var d, r;

        return function(h, u) {
                if (!d) {
                        d = document.createElement('div');
                        r = document.createDocumentFragment();
                        /*@cc_on d.style.display = 'none';@*/
                }

                var e = d.cloneNode(true);
                /*@cc_on document.body.appendChild(e);@*/
                e.innerHTML = h.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
                /*@cc_on document.body.removeChild(e);@*/

                if (u === false) return e.childNodes;

                var f = r.cloneNode(true), i = e.childNodes.length;
                while (i--) f.appendChild(e.firstChild);

                return f;
        }
}());

function position(elt, pos) {
  var t = $(elt);

  if (pos != null) {
    t.css('left', pos.left);
    t.css('top', pos.top);
    t.width(pos.width);
    t.height(pos.height);
    return t;
  }

  return {
    left: t.css('left'),
    top: t.css('top'),
    width: t.width(),
    height: t.height()
  };
}

$.fn.loadImages = function() {
  this.find('img').each(function() {
    var img = $(this);
    var d = $('<div class="img-loading"/>')
      .css('width', img.css('width'))
      .css('height', img.css('height'));
    $(this).load(function() {
      img.unwrap().show();
    }).error(function() {
      d.removeClass('img-loading');
      d.addClass('img-error');
    }).wrap(d).hide();
  });
  return this;
}

function popStory(el, gallery) {
  el = $(el);

  $.scrollTo(el, 500, {axis:'y'});
  var opts = {
    href: function(x) {
      return '/ajax-story.php?id=' + this.id.replace('story-','');
    },
    transition:'elastic',
    open: false,
    opacity: 0.3,
    speed:300,
    current: '',
    slideshowSpeed: 10000,
    maxWidth: 710,
    initialWidth: 50,
    initialHeight: 50,
    onLoad: function() {
      $("#bottom-panels .widget").fadeOut();
      $("#colorbox").addClass("inline").prependTo("#bottom-panels");
      $("#cboxClose").hide();
    },
    onComplete: function() {
      clip_captions();
      $("#cboxClose").fadeIn();
      $("#cboxContent article").hide().fadeIn(500);
    },
    onClosed: function() {
      $("#bottom-panels .widget").fadeIn();
    }
  };
  if (gallery) {
    opts.slideshow = true;
  }
  el.parent().children('.story').colorbox(opts);
  opts.open = true;
  el.colorbox(opts);
  return false;
}


function switch_panel(p) {
  p = $(p);
  if (p.length == 0)
    return false;

  if (p.hasClass('current-panel'))
    return false;

  var par = $(p).parent();
  var sib = $(p).siblings('.panel');

  par.animate({
    height: p.outerHeight(false)
  }, 500, function() {
    p.addClass('current-panel');
    par.height('auto');
  });

  p.fadeIn(500);
  sib.fadeOut(500).removeClass('current-panel');

  return true;
}

function isScrolledIntoView(elem)
{
    var docViewTop = $(window).scrollTop();
    var docViewBottom = docViewTop + $(window).height();

    var elemTop = $(elem).offset().top;
    var elemBottom = elemTop + $(elem).height();

    return ((elemBottom >= docViewTop) && (elemTop <= docViewBottom));
}

function clip(el) {
  el = $(el);
  var img = el.find('img').resize(400,1000);

  var rot = Math.random() * 6;
  rot = rot - 3;
  el.css('transform', 'rotate(' + rot + 'deg)').addClass('clipped');
  var clip = $('<div class="paperclip" />').appendTo(el);
  if (rot > 0) {
    clip.css('transform', 'rotate(' + (rot*8) + 'deg)');;
  } else {
    clip.css('right', '-13px');
  }

  var width = img.width();
  if (width == 0) width = img.attr('width');
  if (width != 0) 
    el.filter('.wp-caption').width(width + 10);
}
function clip_captions(el) {
  el = $(el || document);
  el.find(".wp-caption:not(.clipped)").each(function() { clip(this); });
}

$.fn.resize = function(maxWidth, maxHeight) {
  this.each(function() {
    var ratio = 0;  // Used for aspect ratio
    var width = $(this).width();    // Current image width
    var height = $(this).height();  // Current image height

    // Check if the current width is larger than the max
    if(width > maxWidth){
        ratio = maxWidth / width;   // get ratio for scaling image
        $(this).css("width", maxWidth); // Set new width
        $(this).css("height", height * ratio);  // Scale height based on ratio
        height = height * ratio;    // Reset height to match scaled image
        width = width * ratio;    // Reset width to match scaled image
    }

    // Check if current height is larger than max
    if(height > maxHeight){
        ratio = maxHeight / height; // get ratio for scaling image
        $(this).css("height", maxHeight);   // Set new height
        $(this).css("width", width * ratio);    // Scale width based on ratio
        width = width * ratio;    // Reset width to match scaled image
    }
  });
 
  return this;
}

function bubbleup(elt, fade) {
  var t = $(elt);
  var szW = t.width() / 20;
  var szH = t.height() / 20;

  if (fade) {
    t.hide().css({
      visibility: 'visible',
      opacity: 0
    });
  }
  t.show().animate({
    left: '+='+szW+'px',
    top: '+='+szH+'px',
    height: '-='+2*szH+'px',
    width: '-='+2*szW+'px'
  }, 1).animate({
    top: '-='+szH+'px',
    left: '-='+szW+'px',
    height: '+='+2*szH+'px',
    width: '+='+2*szW+'px',
    opacity: [1, 'easeInSine']
  }, 500, 'easeOutBack');
  return false;
}
function rebubble(elt) {
  var t = $(elt);

  t.css({
    visibility: 'hidden'
  });

  setTimeout(function() {
    bubbleup(t);
  }, Math.floor(Math.random()*500) - 200);
}

function bubbleout(elt, callback) {
  var t = $(elt);
  var pos = position(t);
  var szW = t.width() / 4;
  var szH = t.height() / 4;

  t.find('.caption').fadeOut(100);
  t.show().animate({
    left: '+='+szW+'px',
    top: '+='+szH+'px',
    height: '-='+2*szH+'px',
    width: '-='+2*szW+'px',
    opacity: [0, 'easeOutSine']
  }, 500, 'easeOutQuint', function() {
    t.remove();
  });

  if (callback != null)
    setTimeout(callback, 500);
}

function bubbleat(elt, x,y) {
  var t = $(elt);

  x-= t.width()/2;
  y-= t.height()/2;

  t.css({
    position: 'absolute',
    zIndex: 1,
    left: x+'px',
    top: y+'px'
  });

  bubbleup(t);
}

function bubblehover(ev) {
  var el = $(this);
  if (el.hasClass('inert'))
    return;

  var pic = $(this).find('.pic');
  if (pic.length == 0)
    pic = el;
  bubbleover(pic, ev, 10);
}

function tilt(e, d) {
  if (d <= 0) d= 4;
  $(e).each(function() {
    var deg = Math.random()*d - (d/2);
    $(this).css('transform', 'rotate(' + deg + 'deg)');
  });
}

function bubbleover(el, ev, x) {
  if (x <= 0) x = 5;
  el = $(el);
  if (el.hasClass('inert'))
    return;

  if (ev.type == 'mouseover') {
    el.animate({
      left: '-='+x,
      top: '-='+x,
      width: '+='+(2*x),
      height: '+='+(2*x)
    }, 100).css('zIndex',5);
    return true;
  } else if (ev.type =='mouseout') {
    el.animate({
      left: '+='+x,
      top: '+='+x,
      width: '-='+(2*x),
      height: '-='+(2*x)
    }, 200).css('zIndex',3);
    return true;
  }
}
function bubbleoverthis(ev) {
  bubbleover(this, ev);
}


function randomin(list) {
   return list[Math.floor(Math.random() * list.length)];
}

function preload(src) {
  $("<img />").attr('src', src);
}

Array.prototype.where = function(col, value) {
  for (var i=0; i < this.length; i++) {
    if (this[i][col] == value)
      return this[i];
  }
  return null;
}

  function stripslashes(str) {
    return (str+'').replace(/\\(.?)/g, function (s, n1) {
      switch (n1) {
        case '\\': return '\\';
        case '0': return '\u0000';
        case '': return '';
        default: return n1;
      }
    });
  }

$.easing.bouncy = function (x, t, b, c, d) {
    var s = 1.70158;
    if ((t/=d/2) < 1) return c/2*(t*t*(((s*=(1.525))+1)*t - s)) + b;
    return c/2*((t-=2)*t*(((s*=(1.525))+1)*t + s) + 2) + b;
}

// create custom tooltip effect for jQuery Tooltip
$.tools.tooltip.addEffect("bouncy",

  // opening animation
  function(done) {
    this.getTip().animate({top: '+=15'}, 500, 'bouncy', done).show();
  },

  // closing animation
  function(done) {
    this.getTip().animate({top: '-=15'}, 500, 'bouncy', function()  {
      $(this).hide();
      done.call();
    });
  }
);

$.fn.fade = function(vis) {
  if (vis) {
    this.css({ visibility: 'visible' });
    this.stop().animate({ opacity: 1 }, 300);
  } else {
    this.css({
      opacity: 1
    }).stop().animate({ opacity: 0 }, 300, function() {
      $(this).css({ visibility: 'hidden' });
    });
  }
  return $(this);
}

$.fn.abs = function(abs) {
  $(this).css('position', abs==true ? 'absolute' :'relative');
  return $(this);
};

$.fn.makeAbsolute = function(base) {
  return this.each(function() {
    var el = $(this);
    var pos = el.offset();
    if (base != null) {
      base = $(base);
      el.remove().appendTo(base);
      var bpos = base.offset();

      pos.top -= bpos.top;
      pos.left -= bpos.left;
    }
    el.css({ 
      position: "absolute",
      marginLeft: 0, 
      marginTop: 0,
      top: pos.top, 
      left: pos.left 
    });
  });
};

(function($){
  $.fn.shake=function(opt){
    opt=$.extend({times: 8,delay: 150,pixels: 20},opt||{});
    $(this).each(function(){
      var orig=parseInt($(this).css('top'));
      for (var i=0; i<opt.times; i++)
        $(this).animate({top:orig+(opt.pixels*(i%2==0?1:-1))},opt.delay);
      $(this).animate({top:orig},opt.delay);
    });
  }
})(jQuery);

(function ($) {
    // Monkey patch jQuery 1.3.1+ css() method to support CSS 'transform'
    // property uniformly across Webkit/Safari/Chrome and Firefox 3.5.
    // 2009 Zachary Johnson www.zachstronaut.com
    function getTransformProperty(element)
    {
        // Try transform first for forward compatibility
        var properties = ['transform', 'WebkitTransform', 'MozTransform'];
        var p;
        while (p = properties.shift())
        {
            if (typeof element.style[p] != 'undefined')
            {
                return p;
            }
        }
        
        // Default to transform also
        return 'transform';
    }
    
    var proxied = $.fn.css;
    $.fn.css = function (arg)
    {
        // Find the correct browser specific property and setup the mapping using
        // $.props which is used internally by jQuery.attr() when setting CSS
        // properties via either the css(name, value) or css(properties) method.
        // The problem with doing this once outside of css() method is that you
        // need a DOM node to find the right CSS property, and there is some risk
        // that somebody would call the css() method before body has loaded or any
        // DOM-is-ready events have fired.
        if
        (
            typeof $.props['transform'] == 'undefined'
            &&
            (
                arg == 'transform'
                ||
                (
                    typeof arg == 'object'
                    && typeof arg['transform'] != 'undefined'
                )
            )
        )
        {
            $.props['transform'] = getTransformProperty(this.get(0));
        }
        
        // We force the property mapping here because jQuery.attr() does
        // property mapping with jQuery.props when setting a CSS property,
        // but curCSS() does *not* do property mapping when *getting* a
        // CSS property.  (It probably should since it manually does it
        // for 'float' now anyway... but that'd require more testing.)
        if (arg == 'transform')
        {
            arg = $.props['transform'];
        }
        
        return proxied.apply(this, arguments);
    };
})(jQuery);

/*
 * jQuery throttle / debounce - v1.1 - 3/7/2010
 * http://benalman.com/projects/jquery-throttle-debounce-plugin/
 * 
 * Copyright (c) 2010 "Cowboy" Ben Alman
 * Dual licensed under the MIT and GPL licenses.
 * http://benalman.com/about/license/
 */
(function(b,c){var $=b.jQuery||b.Cowboy||(b.Cowboy={}),a;$.throttle=a=function(e,f,j,i){var h,d=0;if(typeof f!=="boolean"){i=j;j=f;f=c}function g(){var o=this,m=+new Date()-d,n=arguments;function l(){d=+new Date();j.apply(o,n)}function k(){h=c}if(i&&!h){l()}h&&clearTimeout(h);if(i===c&&m>e){l()}else{if(f!==true){h=setTimeout(i?k:l,i===c?e-m:e)}}}if($.guid){g.guid=j.guid=j.guid||$.guid++}return g};$.debounce=function(d,e,f){return f===c?a(d,e,false):a(d,f,e!==false)}})(this);

$.fn.trackViews = function() {
  $.toTrack = $(this).find('.tracked').add($(this).filter('.tracked')).add($.toTrack);
  
  $.trackViews();

  return this;
};
$.reportViews = $.debounce(1000, function() {
  if ($.trackedViews == null || $.trackedViews == "")
    return;

  $.post("/ajax-gifts.php", {
    cmd: 'v',
    'ids[]': $.trackedViews
  });

  $.trackedViews = [];
});

$.trackedViews = [];

$.fn.viewed = function() {
  $(this).filter('.tracked')
    //.css('border','10px solid red')
    .removeClass('tracked')//.addClass('viewed')
    .each(function() {
      this.tracked = true;
      $.trackedViews.push(this.id);
    });
}
$.trackViews = function() {
  if ($.toTrack == null || $.toTrack.length == 0)
    return;

  var scroff = {
    left: $(window).scrollLeft(),
    top: $(window).scrollTop()
  };
  scroff.right = scroff.left + $(window).width();
  scroff.bottom = scroff.top + $(window).height();

  var match = false;
  $.toTrack.each(function() {
    var el = $(this);

    var off = el.offset();
    off.right = off.left + el.width();
    off.bottom = off.top + el.height();
    if (off.top > scroff.bottom
      || off.left > scroff.right
      || off.right < scroff.left
      || off.bottom < scroff.top)
      return;

    var sc = el.closest('.scrollable');
    if (sc.length > 0) {
      var scoff = sc.offset(); 
      scoff.right = scoff.left + sc.width();
      scoff.bottom = scoff.top + sc.height();

      if (scoff.top > scroff.bottom 
        || scoff.left > scroff.right
        || off.top > scoff.bottom
        || off.left > scoff.right
        || off.right < scoff.left
        || off.bottom < scoff.top)
        return;
    }

    el.viewed();
    match = true;
  });

  if (match) {
    $.toTrack = $($.grep($.toTrack, function(e) { return !e.tracked; }));
    $.reportViews();
  }
};
