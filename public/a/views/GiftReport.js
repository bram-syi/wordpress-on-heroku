define([
  "my/app",

  "views/InfoPane",
  "views/GridPane",
  "views/EditPane",
  "views/DesignEditor",

  'models/gift'
], function(
  App,

  InfoPane,
  GridPane,
  EditPane,
  DesignEditor,

  Gift
){
  var Gifts = {};

  Gifts.List = App.Route("/gifts", GridPane.For(Gift, {
    buildOptionsPane: function(pane) {
      pane.addFields({
        all: { type: 'check', label: 'Include inactive' },
        tags: { placeholder: 'Tags' }
      });
    },

    getModelParams: function(values) {
      delete values.active;
      if (!values.all)
        values.active = true;
      delete values.all;
      return values;
    },

    gridColumnsMeta: {
      whole: {
        formatter: GridPane.Gift
      },
      unitsWanted: {
        formatter: GridPane.Units
      }
    },

    getRowClass: function(row) {
      return row.available ? 'active-gift' : 'inactive-gift';
    }

  }));
  Gifts.Create = Gifts.List.Route("new", EditPane.ForNew(Gift));

  Gifts.Info = App.Route("/gift/:gift", InfoPane.For(Gift));
  Gifts.Info.Settings = Gifts.Info.Route("settings", EditPane.For(Gift));
  Gifts.Info.Design = Gifts.Info.Route("design", DesignEditor.For(Gift, {
    designID: 'gift_page',
    template: 'gift-design',

    createFields: function() {
      return {

        title: {
          type: 'text',
          name: 'title',
          height: '1em'
        },

        // Doesn't display in current gift template
        excerpt: {
          type: 'text',
          name: 'excerpt'
        },

        price: {
          placeholder: 'design-give-buttons.png',
          type: 'gift-price',

          fields: {}
        },

        description: {
          // optional: true,
          source: 'gallery.gift_description'
        },

        gallery: {
          source: 'gallery.gift_image',
          canImage: true,
          canText: false
        }

      };
    }
  }));

  return Gifts;
});
