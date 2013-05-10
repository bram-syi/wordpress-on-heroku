define([
  "my/app",
  "views/DesignEditor",
  "views/InfoPane",
  "views/GridPane",

  "views/DonationReport",
  "views/FundraiserReport",
  "views/TeamReport",

  "models/campaign",
  "models/fundraiser",
  "models/team",

  "dijit/layout/ContentPane"
], function(
  App,
  DesignEditor,
  InfoPane,
  GridPane,

  Donations,
  Fundraisers,
  Teams,

  Campaign,
  Fundraiser,
  Team,

  ContentPane
){
  // The campaign report itself
  var Campaigns = {};

  Campaigns.List = App.Route("/campaigns", GridPane.For(Campaign, {
    buildOptionsPane: function(pane) {
      pane.addFields({
        partner: {
          placeholder: 'Partner',
          type: 'partner'
        }
      });
    }
  }));

  Campaigns.Create = Campaigns.List.Route("new", EditPane.ForNew(Campaign, {
    newModel: function() {
      var m = this.inherited(arguments);

      return m.attr({
        start_date: window.moment().format("YYYY-MM-DD") // Today
      }, true);
    }
  }));

  var C = Campaigns.Info = App.Route("/campaign/:campaign", InfoPane.For(Campaign), {
    'progress': GridPane.For(null, {
      api: '/a/api/progress.php',
      hasDates: true
    }) 
  });
  C.Settings = C.Route("settings", EditPane.For(Campaign));
  C.Fundraisers = C.Route("fundraisers", Fundraisers.List);
  C.Fundraisers.Create = C.Fundraisers.Route("new", Fundraisers.Create);
  C.Dashboard = C.Route("dashboard", ContentPane);
  C.Donations = C.Route("donations", Donations.List);
  C.Messages = C.Route("messages", EditPane.For(Campaign));

  C.Teams = C.Route("teams", Teams.List);
  C.Teams.Create = C.Teams.Route("new", Teams.Create);

  C.Team = C.Route("team/:team", InfoPane.For(Team));
  C.Team.Settings = C.Team.Route("settings", EditPane.For(Team));
  C.Team.Fundraisers = C.Team.Route("fundraisers", Fundraisers.List);
  C.Team.Donations = C.Team.Route("donations", Donations.List);

  // Catch fundraisers and display them in subtabs
  C.Fundraiser = C.Route("fundraiser/:fundraiser", Fundraisers.Info);
  C.Fundraiser.Settings = C.Fundraiser.Route("settings", Fundraisers.Info.Settings);
  C.Fundraiser.Donations = C.Fundraiser.Route("donations", Fundraisers.Info.Donations);

  C.Design = C.Route("design", DesignEditor.For(Campaign, {
    designID: 'campaign_page',
    template: 'campaign-design',

    createFields: function() {
      return {

        banner: {
          source: 'gallery.campaign_banner',
          canImage: true,
          canText: false,
          optional: true
        },

        header: {
          source: 'gallery.campaign_header',
          canImage: true,
          canText: false,
          optional: true
        },

        progress: {
          placeholder: 'design-progress.jpg',
          optional: true,
          fields: {}
        },

        give: {
          label: 'Donate Box',
          optional: true,
          placeholder: 'design-givebox.jpg',
          type: 'give-box',
          fields: {}
        },

        note: {
          source: 'gallery.campaign_note',
          label: 'Campaign Note',
          optional: true,
          canImage: true
        },

        thankyou: {
          label: 'Thank You',
          placeholder: 'design-thankyou.jpg',
          optional: true,
          type: 'thanks-box',
          title: 'Thanks to...',

          fields: {
          }
        },

        appeal: {
          title: 'Campaign appeal',
          label: 'Campaign Appeal',
          source: 'gallery.campaign_appeal'
        },

        leaderboard: {
          placeholder: 'design-leaderboard.jpg',
          optional: true,
          type: 'leaderboard',
          title: '',

          fields: {
          }
        },

        champions: {
          label: 'Champ List',
          placeholder: 'design-teams.jpg',
          optional: true,
          type: 'champ-browser',
          title: 'Support a fundraiser',

          fields: {
          }
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
          optional: true,
          type: 'story-browser',
          title: 'Read stories of real lives changed',

          fields: {
          }
        },

        about: {
          source: 'gallery.campaign_about',
          title: '',
          canImage: true,
          optional: true
        }
     
      };
    }
  }));

  C.DesignFundraiser = C.Route("design/fundraiser", DesignEditor.For(Campaign, {
    designID: 'fundraisers',
    template: 'fundraiser-design',

    createFields: function() {
      return {

        banner: {
          source: 'gallery.campaign_banner',
          canText: false,
          canImage: true
        },

        progress: {
          optional: true,
          placeholder: 'design-progress.jpg',
          type: 'progress-bar',
          fields: {}

/*
<section class="stats stats2">
  <div class="stat2 left"><b>$11,600</b></div>
  <div class="stat2 left donors"><b>169</b><label>donors</label></div>
  <div class="stat2 meter2">
    <div class="meter" title="" style="width: 268px;"><span style="width: 100%">
      <span class="reached">100%</span></span>
    </div>
  </div>
<div class="stat2 right goal"><b>$10,000</b><label>goal</label></div>
</section>
*/



        },

        give: {
          label: 'Donate Box',
          placeholder: 'design-givebox.jpg',
          optional: true,
          type: 'give-box',
          fields: {}
        },

        note: {
          label: 'Campaign Note',
          optional: true,
          source: 'gallery.campaign_note'
        },

        thankyou: {
          label: 'Thank You',
          placeholder: 'design-thankyou.jpg',
          optional: true,
          type: 'thanks-box',
          title: 'Thanks to...',

          fields: {
          }
        },

        appeal: {
          fields: {
            post_title: {
              name: 'post_title',
              style: 'width: 600px; margin-bottom: 5px; font-size: 150%; color: #F47C20;',
              placeholder: 'default fundraiser headline'
            },
            post_content: {
              name: 'post_content',
              type: 'text',
              placeholder: 'default fundraiser body text'
            }
          }
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
          optional: true,
          title: 'Read stories of real lives changed',
          type: 'story-browser',

          fields: {
          }
        },

        about: {
          source: 'gallery.campaign_about',
          title: '',
          canImage: true,
          optional: true
        },

        comments: {
          optional: true,
          placeholder: 'design-comments.jpg',
          title: 'Tell [name] why you love this cause!',

          fields: {
          }
        }

      };
    }
  }));

  C.DesignSignup = C.Route("signup", EditPane.For(Campaign));

  return Campaigns;
});
