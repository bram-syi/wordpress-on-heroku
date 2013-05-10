define([
  "declare", "jquery",
  "views/ICan"
], function(declare, $,
  ICan
){
  // This is intended as a mixin for dijit Controls
  return declare("IRefresh", ICan, {
    // Prepares for this.start but DOES NOT call it unless in onShow (below)
    startup: function() {
      this.inherited(arguments);
      this.el.addClass('paused');
    },

    refreshRate: 0,

    isRefreshing: function() {
      return this.interval != undefined && this.interval != 0;
    },

    start: function() {
      this.inherited(arguments);

      // Already started?
      if (this.isRefreshing())
        return;

      this.el.removeClass('paused');

      // Auto-refresh?
      if (this.refreshRate > 0) {
        this.el.addClass('autorefresh');
        this.interval = setInterval(this.proxy(this.refresh), this.refreshRate);
      } 
    },

    refresh: function() {
      if (!this.isRefreshing())
        return;

      this.reload();
    },

    pause: function() {
      if (!this.isRefreshing())
        return;

      if (this.interval > 0)
        clearInterval(this.interval);
      this.interval = 0;

      this.el.addClass('paused');
    }

  });
});
