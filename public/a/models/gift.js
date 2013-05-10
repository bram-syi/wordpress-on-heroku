define([
  "./model"
], function(
  Model
) {
  return Model({
    findOne: '/a/api/gift.php',
    findAll: '/a/api/gift.php',
    create: '/a/api/gift.php',
    update: '/a/api/gift.php',
    destroy: '/a/api/gift.php',

    type_id: 'gift',
    id: "gift_id"
  }, {
    getTitle: function() { return this.title || this.name || this.gift_name; }
  });
});
