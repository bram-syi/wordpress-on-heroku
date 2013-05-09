define([
  "my/app",

  "views/InfoPane",
  "views/GridPane",
  "views/DonationReport",

  'models/fundraiser'
], function(
  App,

  InfoPane,
  GridPane,
  Donations,

  Fundraiser
){
  var Fundraisers = {};

  Fundraisers.List = App.Route("/fundraisers", GridPane.For(Fundraiser));
  Fundraisers.Create = Fundraisers.List.Route("new", EditPane.ForNew(Fundraiser));

  Fundraisers.Info = App.Route("/fundraiser/:fundraiser", InfoPane.For(Fundraiser));
  Fundraisers.Info.Settings = Fundraisers.Info.Route("settings", EditPane.For(Fundraiser));
  Fundraisers.Info.Donations = Fundraisers.Info.Route("donations", Donations.List);

  return Fundraisers;
});
