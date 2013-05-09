define([ 
  'models/person'
], function(
  Person
) {
  return Person({
    findOne: '/a/api/contact.php',
    findAll: '/a/api/contact.php',
    create: '/a/api/contact.php',
    update: '/a/api/contact.php',
    destroy: '/a/api/contact.php',

    type_id: 'contact'
  }, {
    // 
  });
});
