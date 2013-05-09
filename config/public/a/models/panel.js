define([ 
  'models/person'
], function(
  Person
) {
  return Person({
    findOne: '/a/api/promo.php',
    findAll: '/a/api/promo.php',
    create: '/a/api/promo.php',
    update: '/a/api/promo.php',
    destroy: '/a/api/promo.php',

    type_id: 'promo',
    id: "ref"
  }, {
    // 
  });
});
