define([
  "my/app",

  "views/InfoPane",
  "views/GridPane",
  "views/DonationReport",

  'models/team'
], function(
  App,

  InfoPane,
  GridPane,
  Donations,

  Team
){
  var Teams = {};

  // Note that this can't work standalone yet, because teams are dependent on having
  // a parent campaign.

  Teams.List = App.Route("/teams", GridPane.For(Team));
  Teams.Create = Teams.List.Route("new", EditPane.ForNew(Team));

  Teams.Info = App.Route("/team/:team", InfoPane.For(Team));
  Teams.Info.Settings = Teams.Info.Route("settings", EditPane.For(Team));
  Teams.Info.Donations = Teams.Info.Route("donations", Donations.List);

  return Teams;
});
