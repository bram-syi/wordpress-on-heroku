define([ 
  'models/person'
], function(Person) {
  return Person({
    findOne: '/a/api/fundraiser.php',
    findAll: '/a/api/fundraiser.php',
    create: '/a/api/fundraiser.php',
    update: '/a/api/fundraiser.php',
    destroy: '/a/api/fundraiser.php',

    type_id: 'fundraiser'
  }, {
    name: function() {
      return this.fundraiser_owner || this.fundraiser_name;
    }
  });
});
