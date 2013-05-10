define([ 
  'models/person',
  'models/contact'
], function(
  Person,
  Contact
) {
  return Person({
    findOne: '/a/api/donor.php',
    findAll: '/a/api/donor.php',
    create: '/a/api/donor.php',
    update: '/a/api/donor.php',
    destroy: '/a/api/donor.php',

    type_id: 'donor'
  }, {
    // 
    getFullContact: function(fn) {
      Contact.findOne({
        id: this.attr('email'),
        refresh: true,
        queue: true
      }, fn || function(){});
    }
  });
});
