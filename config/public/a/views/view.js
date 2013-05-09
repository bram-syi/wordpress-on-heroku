define([ 
  'can', 'can/view/mustache'
], function(can, Mustache) {
  var ctlID = 0;

  can.Mustache.registerHelper('properties', function(options) {
    var ret = "";
    var obj = this.attr ? this.attr() : this;

    for (var prop in obj)
      if (obj.hasOwnProperty(prop)) {
        ret = ret + options.fn({property:prop, value:obj[prop]});
      }
    return ret;
  });

  return can.Control({
  },{
    defaults: { },

    init: function(el, options) {
      this.createModel();

      this.attrs = new can.Observe({ });
      this.bind('template', can.proxy(function(ev,newVal) {
        this.setTemplate(newVal); 
      }, this));

      // TODO: we don't actually need to do this, if we can just
      // catch some kind of trigger that says when CanJS auto-rerender
      // has happened.
      this.bind('model', can.proxy(this.render, this));

      // Now set the template (if available)
      this.attr('template', options.template || this.constructor.template );
    },

    // Observe pass-throughs
    bind: function(eventType, handler) {
      return this.attrs.bind(eventType, handler);
    },
    unbind: function(eventType, handler) {
      return this.attrs.unbind(eventType, handler);
    },
    attr: function(name, value) {
      return this.attrs.attr(name,value);
    },

    createModel: function() { },

    setTemplate: function(newTemplate) {
      if (!newTemplate) {
        this._renderer = null;
        return;
      }

      var req = [];
      req.push('text!t/' + newTemplate + '.hbs');

      var _this = this;
      require(req, function(template) { 
        _this._renderer = template ? can.Mustache({ text: template }) : null;
        _this.render();
      });
    },

    update: function(options) {
      this.attr(options); // merges
      this.on();
    },

    render: function() {
      // TODO: remove this when I can figure out why it gets called before element is set
      if (!this.element || this.element.length == 0)
        return;

      if (!this._renderer)
        return;

      // TODO: Don't render until model provided?
      var frag = this._renderer( this.attrs );
      this.element.html(can.view.frag(frag));
      this.element.trigger('render');

      return this;
    }

  });
});
