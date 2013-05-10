define([
  "declare", "jquery", "can",

  "views/view"
], function(declare, $, can,

  View
){
  // IHaveModel is a mixin for ViewPanel-inherited controls
  // it creates a view of type "modelType" and stores its model there
  // that model is a CanJS model and uses standard findOne, findAll
  var IHaveModel = declare("IHaveModel", null, {

    createView: function(dom) {
      this.view = new View(dom, {});
      this.view.model = this.modelType;
      this.view.attr('template', this.template);

      this.view.bind('model', this.proxy(function(ev, model, old) {
        if (!this.onModelChange)
          return;

        this.onModelChange(model);
/* TODO: this isn't working yet
        model.bind('change', can.proxy(this, function() {
          this.onModelChange(model);
        }));
*/
      }));
    },

    setModel: function(model) {
      this.view && this.view.attr({ model: model });
      return model;
    },
    getModelClass: function() {
      if (this.modelType)
        return this.modelType;

      return null;
    },

    newModel: function() {
      var modelClass = this.getModelClass();
      var m = new modelClass(this.scope || {});
      return this.setModel( m );
    },

    getModelParams: function(values) {
      return values;
    },

    getParentPane: function() {
      return this.parentPane;
    },

    loadModel: function() {
      // Kill off any existing load
      if (this._loading && this._loading.abort)
        this._loading.abort({ reason: 'Override' });

      var parent = this.getParentPane();
      // TODO: a better way to specify inheritence
      if (parent && parent.getModelClass && (parent.getModelClass() == this.getModelClass())) {
        var m = parent.getLoadedModel();
        if (m)
          return m;
      }

      var _this = this;
      var modelClass = this.getModelClass();
      return this._loading = modelClass.findOne(this.getModelParams(this.scope))
        .done(function(model) {
          _this.setModel(model);
        });
    },

    modifyParent: function(values) {
      return values;
    },

    loadModels: function() {
      // Kill off any existing load
      if (this._loading && this._loading.abort)
        this._loading.abort({ reason: 'Override' });

      var _this = this;
      var modelClass = this.getModelClass();
      return this._loading = modelClass.findAll(this.getModelParams(this.scope))
        .done(function(model) {
          _this.setModel(model);
        });
    },

    getLoadedModel: function() {
      if (this._loading)
        return this._loading;

      return this.getModel();
    },
    getModel: function() {
      var parent = this.getParentPane();
      if (parent && parent.getModelClass && (parent.getModelClass() == this.getModelClass())) {
        var m = parent.getModel();
        if (m)
          return m;
      }

      if (!this.view)
        return null;

      return this.view.attr('model');
    },

    // Helper function - weeds out NULLs
    getValues: function() {
      var v = this.inherited(arguments);
      var model = this.getModel();
      if (model == null)
        return this.scope || {};

      var attrs = model.attr();
      for (var prop in attrs) {
        if (attrs[prop] != null)
          v[prop] = attrs[prop];
      }

      return v;
    },
    getValue: function(name) {
      var v = this.inherited(arguments);
      var model = this.getModel();

      return model.attr(name) || v;
    },

    updateModel: function(attrs, set/*optional*/) {
      var model = this.getModel();
      model.attr(attrs, set);
    },

    saveModel: function() {
      var model = this.getModel();
      return model.save();
    },

    deleteModel: function(fn, fnErr) {
      // Not implemented
    }

  });

  IHaveModel.ICreateNew = declare("INewModel", IHaveModel, {
    loadModel: function() {
      return this.newModel();
    },

    onSaved: function(model) {
      this.inherited(arguments);

      if (this.inDialog) {
        this.el.trigger('created', model);
        this.close(model);
      }
    }

  });

  return IHaveModel;
});
