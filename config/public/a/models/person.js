define([ 
  './model'
], function(Model) {
  return Model({
  },{
    name: function() {
      var first = this.attr('first') || this.attr('user_first');
      var last = this.attr('last') || this.attr('user_last');
      if (!first)
        return last;
      if (!last) 
        return first;
      return first + ' ' + last;
    },

    getTitle: function() { return this.name(); },
    getIcon: function() { return 'person'; }
  });
});
