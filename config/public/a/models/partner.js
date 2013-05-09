define([ 
  './model'
], function(
  Model
) {
  return Model({
    findOne: '/a/api/partner.php?domain={partner}',
    findAll: '/a/api/partner.php',
    create: '/a/api/partner.php',
    update: '/a/api/partner.php',
    destroy: '/a/api/partner.php',

    type_id: 'partner',
    id: "domain"
  }, {
    getTitle: function() { return this.name }
  });
});
