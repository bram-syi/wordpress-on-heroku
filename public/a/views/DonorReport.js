define([
  "my/app",

  "views/InfoPane",
  "views/GridPane",
  "views/DonationReport",

  'models/donor'
], function(
  App,

  InfoPane,
  GridPane,
  Donations,

  Donor
){
  var Donors = {};

  Donors.List = App.Route("/donors", GridPane.For(Donor));

  Donors.Info = App.Route("/donor/:donor", InfoPane.For(Donor, {
    startup: function() {
      this.inherited(arguments);

      // TODO: this is temporary
      $(this.domNode).on('click', '.get-fullcontact', this.proxy(function(ev) {
        var model = this.getModel();
        model.getFullContact();
        $(ev.currentTarget).remove();
        setTimeout(this.proxy(this.reload), 7000);
      }));
      $(this.domNode).on('click', '.got-fullcontact', this.proxy(function(ev) {
        this.reload();
      }));
    }
  }), {
    "activity": Donations.Activity,
    "donations": Donations.List
  });

  return Donors;
});
