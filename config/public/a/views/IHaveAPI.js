define([
  "declare", "jquery", "can",
  "views/IQuery"
], function(declare, $, can,
  IQuery
){
  // This is intended as a mixin for dijit Controls
  return declare("IHaveAPI", IQuery, {

    getApiUrl: function() { 
      return this.api;
    },

    // Override this function
    loadModel: function() {
      // Get the JSON endpoint for this pane
      var query = this.getValues() || {};
      var url = this.getApiUrl();
      if (!url)
        return;
      url = can.sub(url, query, true);

      // Cancel the previous API call
      if (this.apiCall && this.apiCall.readyState != 4) {
        this.apiCall.abort();
        this.apiCall = null;
      }

      var my = this;
      var defer = new can.Deferred();
      this.apiCall = $.ajax({
        type: 'GET',
        url: url,
        data: query,
        dataType: 'json',
        accepts: 'application/json',
        headers: {
          'X-API': '0241c2xd'
        },
        // context: this,

        success: function ajaxSuccess(response) {
          my.el.removeClass('error');
          my.el.removeClass('loading-error');
          defer.resolve(response);
        },
        error: function ajaxError(xhr, type) {
          my.el.addClass('error loading-error');
          defer.reject();
        },
        beforeSend: function(xhr, type) {
          my.el.addClass('loading');
        },
        complete: function(xhr, type) {
          my.el.removeClass('loading');
        }
      });

      return defer;
    },

    loadModels: function() {
      var defer = new can.Deferred();

      can.when(this.loadModel())
        .done(function(model) {
          var list;

          // turn it into a list
          if (model.data) {
            list = new can.Model.List(model.data);
            delete model.data;
            can.extend(list, model);
          }
          else
            list = new can.Observe.List([model]);

          defer.resolve(list);
        })
        .fail(function(args) {
          defer.reject(args);         
        });

      return defer;
    }

  });
});
