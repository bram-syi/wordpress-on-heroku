define([
  "my/app",

  "views/InfoPane",
  "views/GridPane",

  "models/donation"
], function(
  App,

  InfoPane,
  GridPane,

  Donation
){
  // The campaign report itself
  var Donations = {};

  Donations.List = App.Route("/donations", GridPane.For(Donation, {
    hasDates: true
  }));

  Donations.Activity = App.Route("/activity", GridPane.For(null, {
    title: 'Activity',
    api: '/a/api/activity.php',

    emptyMessage: 'There are no donations yet.',
    noMatchMessage: 'No matching donations.',

    buildOptionsPane: function(pane) {
      pane.addFields({
        donor: { placeholder: 'Donor', type: 'donor' },
        type: { placeholder: 'Activity', type: 'choice', choices: [
          'Payment', 'Purchase', 'Gift Card', 'Allocations'
        ]},
        fundraiser: { placeholder: 'Fundraiser', name:'fr_id', type: 'fundraiser', facet: true },
        partner: { placeholder: 'Partner', type: 'partner', facet: true },
        account: { placeholder: 'Account', type: 'account', facet: true },
        from: { placeholder: 'From date', type: 'date' },
        to: { placeholder: 'To date', type: 'date' }
      });
    },

    gridColumnsMeta: {
      first: {
        formatter: GridPane.Donor
      }
    },

    getRowClass: function(row) {
      if (row.type == 'BUY GC' || row.type == 'TIP')
        return;
      if (row.type == 'ERROR')
        return "row-error";
      if (row.type == 'SPEND GC')
        return "row-payment";
      if (row.account_type > 0 && row.amount > 0)
        return "row-deposit";
      if (row.account_type > 0 && row.amount < 0)
        return "row-withdraw";
      if (row.provider > 0)
        return "row-payment";
    }

  }));

  Donations.Info = App.Route("/donation/:id", InfoPane.For(Donation));

  return Donations;
});
