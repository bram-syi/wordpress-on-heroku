define([ 
  'can'
], function(can) {
  var Model = can.Model({
    model: function(response) {
      if (response.data) {
        if (can.isArray(response.data))
          response.data = response.data[0];

        var model = can.Model.model.call(this, response.data);
        delete response.data;
        can.extend(model, response);
        return model;
      }
      return can.Model.model.call(this, response);
    },

    models: function(response, xhr) {
      var list;
      if (response.data) {
        list = can.Model.models.call(this, response.data);
        delete response.data;
        can.extend(list, response);
      } else {
        list = can.Model.models.call(this, response);
      }

      return list;
    },

    getWord: function() {
      return this.type_id || 'item';
    },
    getPlural: function() {
      return this.getWord() + 's';
    },

    type_id: null,
    id: 'id'

  },{
    // instance methods
    getTitle: function() { return this.id; },
    getIcon: function() { return null; },

    getRoute: function() {
      return this.constructor.type_id + '/' + this[this.constructor.id];
    }
  });

  return Model;
});
