// FAQ clicks to expand/collapse
// $("dl.faq dt, dt.faq").first().next('dd').andSelf().addClass('expanded');
$(document).on('click', "dt.faq, dl.faq dt", function faq_click(ev) {
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

// Equal heights:    http://stackoverflow.com/questions/6041654/achieving-equal-height-columns-in-a-responsive-flexible-layout
$.fn.eqHeights = function() {
  if ($(this).length < 2) return;
  var heights = $(this).height('auto').map(function() {
    return $(this).outerHeight();
  }).get(); // box model -> outerHeight
  var tallest = Math.max.apply(Math, heights);
  $(this).height( tallest );
  // $(this).css( $.browser.msie && $.browser.version == 6.0 ? { height : tallest } : { minHeight : tallest });
};

$.fn.balance = function() {
  if ($(this).length == 0) return;
  var row = [];
  var y;
  $(this).height('auto').each(function(i,el) {
    var top = $(el).position().top;
    if (top !== y) {
      $(row).eqHeights();
      row = [];
    }
    y = top;
    row.push(el);
  });
  $(row).eqHeights();
};

// 

// http://stackoverflow.com/questions/3805852/select-all-text-in-contenteditable-div-when-it-focus-click
$.fn.selectText = function () {
  var el = this[0];

  window.setTimeout(function() {
    var sel, range;
    if (window.getSelection && document.createRange) {
      range = document.createRange();
      range.selectNodeContents(el);
      sel = window.getSelection();
      sel.removeAllRanges();
      sel.addRange(range);
    } else if (document.body.createTextRange) {
      range = document.body.createTextRange();
      range.moveToElementText(el);
      range.select();
    }
  }, 1);
};

$.fn.focusFirst = function() {
  this.find("input:visible, textarea")
    .filter(function() { return $(this).val() == ''; }).first().focus();
};

// If using zepto, patch it to include .hover()
if ($.fn && !$.fn.hover)
  $.fn.hover = function() { };

if (!String.prototype.trim)
  String.prototype.trim = function() { return this.replace(/^\s+|\s+$/g, ''); };

if (!String.prototype.capitalize)
  String.prototype.capitalize = function() { return this.charAt(0).toUpperCase() + this.slice(1); }
