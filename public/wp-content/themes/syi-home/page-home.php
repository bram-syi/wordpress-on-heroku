<?

global $GIFTS_LOC;
$GIFTS_LOC = "home";

add_action('full_width_top', 'full_width_slidedeck');
global $typekit;
$typekit = 'pfs2cqh';

global $IS_HOME_PAGE;
$IS_HOME_PAGE = TRUE;

get_header(); 

?><section id="bottom-panels" class="page-content" style="margin-top: 20px;"><?

draw_home_widget('home-gifts', 'gifts_widget', array(
  'tag' => 'featured',
  'has_text' => false,
  'limit' => 3,
  'show_all' => true,
  'order' => 'rand()'
));

draw_home_widget('home-stories', 'stories_widget', array(
  'featured_only' => true,
  'limit' => 3
));

?><style>
.gifts-v3 .gift .price { display: none; }
</style></section><?
get_footer(); 

function full_width_slidedeck() {
  $id = 12877;
  $id2 = 12911;

?>
<script type="text/javascript">
/*
 * JQuery CSS Rotate property using CSS3 Transformations
 * Copyright (c) 2011 Jakub Jankiewicz  <http://jcubic.pl>
 * licensed under the LGPL Version 3 license.
 * http://www.gnu.org/licenses/lgpl.html
 */
(function($) {
    function getTransformProperty(element) {
        var properties = ['transform', 'WebkitTransform',
                          'MozTransform', 'msTransform',
                          'OTransform'];
        var p;
        while (p = properties.shift()) {
            if (element.style[p] !== undefined) {
                return p;
            }
        }
        return false;
    }
    $.cssHooks['rotate'] = {
        get: function(elem, computed, extra){
            var property = getTransformProperty(elem);
            if (property) {
                return elem.style[property].replace(/.*rotate\((.*)deg\).*/, '$1');
            } else {
                return '';
            }
        },
        set: function(elem, value){
            var property = getTransformProperty(elem);
            if (property) {
                value = parseFloat(value);
                $(elem).data('rotatation', value);
                if (value == 0) {
                    elem.style[property] = '';
                } else {
                    elem.style[property] = 'rotate(' + value + 'deg)';
                }
            } else {
                return '';
            }
        }
    };
    $.fx.step['rotate'] = function(fx){
        $.cssHooks['rotate'].set(fx.elem, fx.now);
    };
})(jQuery);
$.fn.changeTo = function(i, example, duration) {
  this.html('<span class="going">' + this.text() + '</span><span class="coming"></span>');
  this.find('.going').fadeOut(duration / 2, function() {
    $(this).remove();
  });
  this.find('.coming').text(example.action).css({
    paddingLeft: 50,
    opacity: 0,
    color: '#ff0'
  }).animate({
    paddingLeft: 0,
    opacity: 1,
    color: '#fff'
  }, duration);

  if (example.img) {
    var r = Math.random() * 120 - 60;
    var rot = r / 10;
    var x = r / 2 + (Math.random() * 20);
    var y = Math.random() * 30;
    var images = this.closest('.slidedeck').find('.images');
    var img = images.find('#img' + i).remove();
    if (img.length == 0)
      img = $('<img class="photo">').attr('id', 'img' + i)
        .attr('src', 'http://res.cloudinary.com/seeyourimpact/image/fetch/w_250,h_300,g_faces,c_fill/http://' + example.img);
    img.appendTo(images)
      .css({
        rotate: 0,
        opacity: 0,
        top: 150,
        right: 70
      }).animate({
        rotate: rot,
        opacity: 1,
        top: 100 + y,
        right: 70 - x
      }, duration);
  }
};
$(function() {
  var examples = [
    { action: 'educate a girl in India.', img:'pratham.seeyourimpact.org/files/2012/10/Nikita.jpg' },
    { action: 'empower urban youth through sports.', img: 'caa.seeyourimpact.org/files/2012/01/Andy-Neilsen-UI.jpg' },
    { action: 'fight hunger in East Africa.', img:'wvhoa.seeyourimpact.org/files/2012/02/SCHOOL_CHILDREN_RECEIVE_FOOD1.jpg' },
    { action: 'shelter a homeless woman and her children.', img:'marysplace.seeyourimpact.org/files/2012/05/Missy.jpg' },
    { action: 'provide health care in rural Guatemala.', img:'seeyourimpact.org/wp-content/V1.32/gift-images/Gift_890.jpg' },
    { action: 'fund a scholarship for a deserving student.', img:'tss.seeyourimpact.org/files/2012/08/Allie-Dillon-1.jpg' },
    { action: 'give a girl in Rwanda a chance to go to school.', img:'rgi.seeyourimpact.org/files/2013/02/Nadia-685x1024.jpg' },
    { action: 'sponsor a safe place for after school learning.', img: 'bgckc.seeyourimpact.org/files/2012/10/BGCKC-Lemarion1.jpg' },
    { action: 'provide child care for a single mom.', img:'kidsco.seeyourimpact.org/files/2012/09/GH-Latrice1.jpg' },
    { action: 'keep a teen in high school.', img:'grub.seeyourimpact.org/files/2013/01/joe-small.jpg' },
    { action: 'rebuild a library for an urban school.', img:'hawthorne.seeyourimpact.org/files/2012/09/Isaiah-SYI-764x1024.jpg' }
  ];

  var quotes = [
    'files/2013/04/grub3.jpg',
    'files/2013/04/grub1.jpg',
    'files/2013/04/grub2.jpg'
  ];

  var i = 1; // Slide 1
  var opened = null; // slide 3

  var deck = $('#SlideDeck-<?=$id?>').slidedeck();
  deck.oldbefore = deck.options.before;
 
  function changer() {
    switch (deck.current) {
      case 1:
        $(".changing").changeTo(i,examples[i],1000);
        i = (i + 1) % examples.length;
        $("#preloader").attr('src', '//' + examples[i].img);
        break;

      case 3:
        if (opened === null)
          openQuote($("#quote0"));
/*
        $("#quote" + j).animate({
          boxShadowSpread: 10
        }, 200);
        j = (j + 1) % quotes.length;
        $("#quote" + j).animate({
          boxShadowSpread: 20
        }, 500);
*/
        break;
    }
  }
  setInterval(changer, 8000);

  var closeQuotes = function() {
    $(".quote-big").stop().animate({
      opacity: 0,
      rotate: 0,
      zIndex: 1
    }, 1000);
    $('.quote').css({
      borderColor: '#888',
      background: 'white'
    });
  };
  var openQuote = function(el) {
    if (opened == el)
      return;

    closeQuotes();
    opened = el;

    var r = Math.random() * 6 - 3;
    $('#' + el.attr('id') + "-big").stop().css({
      display: 'block',
      opacity: 0
    }).animate({
      opacity: 1,
      rotate: r,
      zIndex: 5
    }, 1000);
    el.css({
      borderColor: 'orange',
      background: 'orange',
      margin: -1,
      borderWidth: 2
    });
  };

  for (var i = 0 ; i < quotes.length; i++) {
    $('<img class="photo quote" src="http://res.cloudinary.com/seeyourimpact/image/fetch/w_100,h_70,g_north,c_fill/http://seeyourimpact.org/' + quotes[i] + '" id="quote' + i + '">')
      .appendTo('.deck4')
      .hover(function(e) { openQuote($(this)); }, closeQuotes);
    $('<img class="photo quote-big" src="http://res.cloudinary.com/seeyourimpact/image/fetch/w_300,h_300,g_faces,c_fill/http://seeyourimpact.org/' + quotes[i] + '" id="quote' + i + '-big">')
      .appendTo('.deck4');
  }

  deck.options.before = function(deck) {
    closeQuotes();
    switch (deck.current) {
      case 2:
        deck.deck.find('img.photo2')
          .css({ rotate: 0, left: 90 })
          .animate({ rotate: -3, left: 80 }, 1000);
        deck.deck.find('img.photo3')
          .css({ left: 300, rotate: -2 })
          .animate({ left: 340, rotate: 2 }, 1000);
        break;

      case 3:
        opened = null;
        openQuote($('#quote0'));
        for (var j = 0; j < quotes.length; j++) {
          $("#quote" + j)
            .css({
              left: 80 + j * 120
            })
            .animate({
              left: 100 + j * 140
            }, 1000)
        }
        break;

      case 4:
        deck.deck.find('img.photo6')
          .css({ rotate: 0, top: 65 })
          .animate({ rotate: -3, top: 50 }, 1000);
        deck.deck.find('img.photo7')
          .css({ rotate: 0 })
          .animate({ rotate: 2 }, 1000);
        break;

    }
    if (deck.oldbefore)
      deck.oldbefore(deck);
  };
});
</script>

<div class="box" style="width: 1000px; margin: -3px; position: relative; z-index: 100; border: 1px solid #888; padding: 4px; background: white; box-shadow: 0 5px 20px rgba(127,127,127,20); overflow: hidden;">
  <?= do_shortcode( "[SlideDeck2 id=$id]" ); ?>
  <?= do_shortcode('[contact-form-7 id="' . $id2 . '" title="Contact SeeYourImpact.org"]'); ?>
  <img id="preloader" src="" width="100" style="position: absolute; right: -200px; bottom: 200px;">
</div>
<?
}
