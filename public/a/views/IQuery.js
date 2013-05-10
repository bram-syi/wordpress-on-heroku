define([
  "declare", "jquery", "can",
  "views/ICan"
], function(declare, $, can,
  ICan
){
  // Query interface = no server-side implementation, just a local
  // observable with query arguments.

  // This is intended as a mixin for dijit Controls

  return declare("IQuery", ICan, {
    getQuery: function() {
      if (this._query)
        return this._query;

      return this._query = new can.Observe(this.scope || {});
    },

    postCreate: function() {
      this.getQuery().bind('change', this.onChange);
    },

    // Helper function - weeds out NULLs
    getValues: function() {
      var v = {};
      var attrs = this.getQuery().attr();
      for (var prop in attrs) {
        if (attrs[prop] != null)
          v[prop] = attrs[prop];
      }

      return v;
    },

    getValue: function(name) {
      return this.getQuery().attr(name);
    },

    // TODO: not used yet
    onChange: function() { },

    updateModel: function(attrs, set/*optional*/) {
      this.getQuery().attr(attrs, set);
    },

    newModel: function() {
      // Always success
      return this.getQuery();
    },

    loadModel: function() {
      // Always success
      return this.getQuery();
    },

    saveModel: function() { 
      // Always success
      return this.getQuery();
    },

    deleteModel: function() { /* NOOP */ }

  });
});
