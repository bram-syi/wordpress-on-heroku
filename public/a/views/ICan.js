define([
  "declare", "jquery", "can"
], function(declare, $, can
){
  // This is intended as a mixin for dijit Controls
  return declare("ICan", null, {

    proxy: function(f) {
      return can.proxy(f, this);
    },

    // Load from an URL and pull out the .data part as the result.
    // returns a deferred
    loadFrom: function(url) {
      var defer = new can.Deferred();
      can.ajax({
        url: url,
        success: function(result) {
          var a = result.data;
          can.each( result, function(val, i) {
            if (val === null || i == 'data')
              return;

            a[i] = val;
          });
          defer.resolve(a);
        },
        error: defer.reject
      });

      return defer;
    }

  });
});
