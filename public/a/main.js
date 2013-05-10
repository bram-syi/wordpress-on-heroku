/*

requirejs.config({
  // baseUrl: '/a',
  // enforceDefine: true,
  inlineText: true,
  // urlArgs: "ver=" + (new Date()).getTime(),

  packages: [
    { name: "dojo", location: "v/dojo", main: 'there-is-no-main' },
    { name: "dojox", location: "v/dojox", main: 'there-is-no-main' },
    { name: "dijit", location: "v/dijit", main: 'there-is-no-main' }
  ],

  map : {
    '*' : {
      "can/util/library" : "can/util/zepto"
    }
  },

  paths: {
    "can" : "v/can",
    'text': 'v/text',

    // Sub in zepto for jquery
    "zepto" : ["http://cdnjs.cloudflare.com/ajax/libs/zepto/1.0rc1/zepto.min", "v/zepto"],
    "jquery" : ["http://cdnjs.cloudflare.com/ajax/libs/zepto/1.0rc1/zepto.min", "v/zepto"],
    // 'jquery': 'v/jquery-1.8.3',

    // Sub in lodash for underscore
    'lodash': 'v/lodash',
    'underscore': 'v/lodash'
  }

});

*/

// Required for jQuery to be included via AMD
define.amd.jQuery = true;

// Scripts not dependent on jquery
define(['v/accounting.min', 'jquery', 'v/ckeditor/ckeditor', 'v/history', 'v/dbootstrap/icon_support', 'v/moment.min'], function () {
  // jquery dependent scripts
  require(['v/jquery-migrate-1.2.0.min'], function() {
    // jquery + migrate dependent scripts
    require(['v/jquery.event.drag-2.2','v/jquery.ba-resize','v/jquery.lightbox_me','v/jquery.blockUI','pnotify'], function() {

      $.extend($.blockUI.defaults.overlayCSS, {
        'background-color': '#cde',
        '-moz-border-radius': '4px',
        '-webkit-border-radius': '4px',
        'border-radius': '4px',
        '-moz-box-sizing': 'border-box',
        '-webkit-box-sizing': 'border-box',
        '-ms-box-sizing': 'border-box',
        'box-sizing': 'border-box',
        'border': '4px solid white'
      });
      $.blockUI.defaults.message = '<div style="width:128px;height:128px;background:url(/a/i/loading.gif);margin-top:-10px;"></div>';
      $.blockUI.defaults.css = {};

      // Let us manually control what will be editable
      CKEDITOR.disableAutoInline = true;

      // final layer of dependencies
      require(['slick', 'behavior'], function() {
        // app itself
        require(['my/app',

          // TODO: need to automate these but there are no explicit code requirements
          'views/PartnerReport', 
          'views/CampaignReport',
          'views/DonationReport',
          'views/DonorReport',
          'views/GiftReport', 
          'views/TeamReport', 
          'views/FundraiserReport'
        ], function(AdminApp) {
          var app = new AdminApp({});
          app.placeAt('body');
          app.init();
          app.startup();
        });
      });
    });
  });
});
