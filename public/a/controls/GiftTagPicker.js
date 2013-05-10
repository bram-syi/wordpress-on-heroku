define([
  "declare", "can", "jquery",
  "dojo/dom-construct",
  "dijit/_WidgetBase",

  "models/gift",
  "dijit/form/TextBox"
], function(declare, can, $,
  domConstruct,
  _WidgetBase,

  Gift,
  TextBox
){
  var GiftTagPicker = declare("GiftTagPicker", _WidgetBase, {

    _getValueAttr: function() {
      return this.value;
    },
    _setValueAttr: function(tag) {
      if (this.tag == tag)
        return;

      this.value = tag;
      this.loadGifts();
    },

    buildRendering: function() {
      this.domNode = domConstruct.create('div', {
        className: 'gift-list cf'
      });
    },

    postCreate: function() {
      this.inherited(arguments);

      // Create the gift tag text box (TODO: add typeahead later)
    },

    startup: function() {
      this.inherited(arguments);

/*
      var _this = this;
      this.view.bind('model', function(ev) {

        // this is now _this.view
        var tag = this.model.attr('tag');
        _this.set('value', tag);

      });
*/

    },

    loadGifts: function() {
      var giftEl = $(this.domNode);

      // TODO: convert to a live bind template sometime?

      if (this.value == null || this.value == '') {
        giftEl.html('Please choose a gift tag.');
        return;
      }

      Gift.findAll({
        tag: this.value,
        active: true,
        order: 'price'
      }, function(gifts) {
        if (gifts.length == 0) {
          giftEl.html(can.esc('No gifts match the tag "' + this.value + '"'));
        } else {
          giftEl.html('');
          can.each(gifts, function(gift) {
            var g = $(can.sub('<div class="gift"><img src="{image}" width="100" height="100"><span class="name">{title}</span><span class="price">${price}</span></div>', gift)).appendTo(giftEl);
            if (!gift.available)
              g.addClass('unavailable-gift');
          });
        }
      });
    }

  });

  return GiftTagPicker;
});
