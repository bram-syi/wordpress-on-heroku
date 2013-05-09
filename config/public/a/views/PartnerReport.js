define([
  "my/app",

  "models/partner",

  "views/InfoPane",
  "views/GridPane",
  "views/EditPane",
  "views/DesignEditor",

  "views/DonationReport",
  "views/CampaignReport",
  "views/FundraiserReport",
  "views/GiftReport",

  "controls/PartnerPicker",
  "dijit/layout/ContentPane"
], function(
  App,

  Partner,

  InfoPane,
  GridPane,
  EditPane,
  DesignEditor,

  Donations,
  Campaigns,
  Fundraisers,
  Gifts,

  PartnerPicker,
  ContentPane
){
  var Partners = {};

  Partners.List = App.Route("/partners", GridPane.For(Partner, {
    getRowClass: function(row) {
      return (row.private === 0 || row.private === false) ? 'live-yes' : 'live-no';
    }
  }));
  Partners.Create = Partners.List.Route("new", EditPane.ForNew(Partner, {
    onSaved: function(model) {
      PartnerPicker.clear_cache(); // TODO: invalidate this based on next line's trigger
      this.inherited(arguments);
    }
  }));

  var P = Partners.Info = App.Route("/partner/:partner", InfoPane.For(Partner), {
    "settings": EditPane.For(Partner),
    "dashboard": ContentPane,
    "donations": Donations.List,
    "stories": ContentPane
  });

  P.Gifts = P.Route("gifts", Gifts.List);
  P.CreateGift = P.Gifts.Route("new", Gifts.Create);

  P.Campaigns = P.Route("campaigns", Campaigns.List);
  P.CreateCampaign = P.Campaigns.Route("new", Campaigns.Create);

  P.Fundraisers = P.Route("fundraisers", Fundraisers.List);

  // Capture fundraiser panes and display them in subtabs
  P.Fundraiser = P.Route("fundraiser/:fundraiser", Fundraisers.Info, {
    'settings': Fundraisers.Info.Settings
  });
  P.Fundraiser.Settings = P.Fundraiser.Route("settings", Fundraisers.Info.Settings);
  P.Fundraiser.Donations = P.Fundraiser.Route("donations", Fundraisers.Info.Donations);

  // DESIGN TEMPLATES

  P.Design = P.Route("design", DesignEditor.For(Partner, {
    designID: 'partner_page',
    template: 'partner-design',

    createFields: function() {
      return {

        header: {
          source: 'gallery.header',
          canImage: true
        },

        quickfacts: {
          label: 'Quick Facts',
          source: 'gallery.quickfacts',
          optional: true
        },

        certifiedorg: {
          label: 'Certified Org',
          source: 'gallery.certifiedorg',
          optional: true
        },

        gifts: {
          label: 'Give a Gift',
          optional: true,
          title: 'Give a gift, get a story of the life you change!',

          fields: {
            tag: {
              name: 'tag',
              type: 'gift-tag'
            }
          }
        },

        stories: {
          placeholder: 'design-stories2.jpg',
          type: 'story-browser',
          optional: true,
          title: 'Read stories of real lives changed',

          fields: {}
        },

        activity: {
          placeholder: 'design-thankyou.jpg',
          type: 'activity-browser',
          optional: true,
          title: 'Latest activity',

          fields: {}
        },

        comments: {
          placeholder: 'design-comments.jpg',
          type: 'comments',
          optional: true,
          title: 'Tell [name] why you love this cause!',

          fields: {}
        }

      };
    }
  }));

  P.DesignAbout = P.Route("about", DesignEditor.For(Partner, {
    designID: 'about_page',
    template: 'partner-about-design',

    createFields: function() {
      return {

        about: {
          source: 'gallery.about',
          canImage: true
        }

      };
    }
  }));

  return Partners;
});
