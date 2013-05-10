define([ 
  'models/model'
], function(
  Model
) {
  return Model({
    findOne: '/a/api/donation.php',
    findAll: '/a/api/donation.php',
    create: '/a/api/donation.php',
    update: '/a/api/donation.php',
    destroy: '/a/api/donation.php',

    type_id: 'donation'
  }, {
    getTitle: function() { return this.post_title; }
    // 
  });
});
