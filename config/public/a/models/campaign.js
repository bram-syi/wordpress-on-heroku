define([
  "./model"
], function(
  Model
) {
  return Model({
    type_id: 'campaign',

    findOne: '/a/api/campaign.php',
    findAll: '/a/api/campaign.php',
    create: '/a/api/campaign.php',
    update: '/a/api/campaign.php',
    destroy: '/a/api/campaign.php'
  }, {
    getTitle: function() { return this.title || this.name; }
  });
});
