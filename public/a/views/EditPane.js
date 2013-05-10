define([
  "declare", "can", "jquery",
  "dojo/on","dojo/_base/lang","dojo/_base/array","dojo/keys",

  "controls/Panel", 
  "controls/GiftTagPicker", 
  "controls/PartnerPicker",

  "dijit/layout/ContentPane",
  "dijit/form/Form",
  "dijit/form/TextBox",
  "dijit/form/CheckBox",
  "dijit/form/Textarea",
  "dijit/form/CurrencyTextBox",
  "dijit/form/NumberTextBox",
  "dijit/form/DateTextBox",
  "dijit/form/Button",

  "dijit/Editor",

  "plupload",
  "can/observe/backup"
], function(declare, can, $,
  on,lang,array,keys,

  Panel,
  GiftTagPicker,
  PartnerPicker,

  ContentPane,
  Form, 
  TextBox,
  CheckBox,
  Textarea,
  CurrencyTextBox,
  NumberTextBox,
  DateTextBox,
  Button,

  Editor
){

  // Load plupload dependencies
  require(["plupload/plupload.html5", "plupload/plupload.flash", "plupload/plupload.silverlight", "plupload/plupload.html4"], function() {});

  // Helper functions for editing panes
  var EditPane = declare("EditPane", [Form, IHaveAPI, ICan], {
    region: 'center',

    constructor: function(params, dom) {
      this.form = this;
      this.fields = {};
      this._initValues = new can.Deferred();
      this._ready = new can.Deferred();
    },

    postCreate: function() {
      this.inherited(arguments);

      // Set this pane as our CanJS view
      if (this.createView)
        this.createView(this.containerNode);

      // Set the initial value of all fields after loading both the
      // field schema and the field values.
      var _this = this;
      can.when(this.createFields())
        .done(function(fields) {

          if (_this.inDialog) {
            $(_this.containerNode).prepend('<h2 class="title">' + can.esc(fields.label) + '</h2>');
          }

          _this.addFields(fields);
          _this.createButtons();

          _this._initValues
            .done(function(values) {
              _this.updateFields(values);
              // Focus on first empty field
              $(_this.domNode).focusFirst();
            });
        });
    },

    // Return fields template OR a deferred promise
    createFields: function() { 
      // Async load the field definition?
      if (this.loadFields)
        return this._initFields = new can.Deferred();
    },

    loaded: function(model) {
      this.inherited(arguments);
      if (!model)
        return;

      var values = model.attr();
      var init = this._initFields;
      if (init) {
        var fields;

        // Load in the form templates (stored as metadata on the model)
        if (this.loadFields && model.forms) {
          fields = model.forms[this.loadFields];
        }

        if (fields) 
          init.resolve(fields || {});
        else {
          this.loadFrom('/a/api/admin.php?fields=' + this.loadFields)
            .done(function(fields) {
              init.resolve(fields);
            })
            .fail(function() {
              init.resolve({});
            });
        }
      }
      this._initValues.resolve(values);
      this.updateFields(values);
    },
    loadError: function() {
    },

    // TODO: This should probably be some kind of mixin, it's the base for ViewPane too
    startup: function() {
      this.el = $(this.domNode);
      this.inherited(arguments);

      this._ready.resolve(true);
    },

    // Automatically start or pause when inside a tab container
    onHide: function() {
      this.pause();
      this.inherited(arguments);
    },
    onShow: function() {
      this.inherited(arguments);
      this.start();
    },

    start: function() {
      var _this = this;
      this._ready && this._ready.done(function() {
        delete(_this._ready);
        _this.reload();
      });
    },
    pause: function() { }, // Nothing to do

    reload: function() {
      can.when(this.loadModel())
        .done(this.proxy(this.loaded))
        .fail(this.proxy(this.loadError));
      return this;
    },

    createField: function(params) {
      if (params.fields || params.items) {
        var a = [];
        this.addFields(params, function(dij) {
          if (dij)
            a.push(dij);
        });
       
        if (a.length == 0) {
          return { 
            name: params.name, 
            label: params.label,
            fields: []
          };
        }

        return {
          name: params.name,
          id: a[0].id,
          label: params.label || a[0].label,
          fields: a
        };
      }

      params.form = this.form;

      switch (params.type) {
        case 'bool':
        case 'check':
          return new CheckBox(can.extend({
            trim: true,
            value: true,
            after: params.label
          }, params));

        case 'title':
          return new TextBox(can.extend({
            style: 'width: 100%;',
            trim: true
          }, params));
   
        case 'text':
          return new Textarea(can.extend({
            style: 'width: 100%;',
            trim: true
          }, params));

        case 'partner':
          return new PartnerPicker(params);

        case 'gift-tag':
          return new GiftTagPicker(params);

        case 'html':
          return new Editor(can.extend({
            plugins: ['bold','italic','underline','strikethrough', '|', 'justifyLeft','justifyCenter','justifyRight', '|', 'insertOrderedList','insertUnorderedList'],
            styleSheets: '/a/i/editor-styles.css'
          }, params));

        case 'number':
          return new NumberTextBox(params);

        case 'money':
          return new CurrencyTextBox(can.extend({
            trim: true,
            currency: 'USD'
          }, params));

        case 'date':
          return new DateTextBox(can.extend({
            trim: true
          }, params));

/*
        case 'image':
        case 'file':
          delete params.type;
          var field = new Button(can.extend({
            label: '<i class="icon-upload-alt"></i> Upload...',
            originalLabel: params.label,
          }, params));
          this.makeUploader(field, this.form);
          return field;
*/

        case 'panel':
          var panel = new Panel(can.extend({
            // ?
          }, params));
          return panel;

        case 'search':
          var s = new TextBox(can.extend({
            onKeyUp: this.proxy(function(ev) {
              if (ev.keyCode == keys.ENTER)
                this.onSave();
            })
          },params));
          var b = new Button({
            label: '<i class="icon-search"></i>',
            'class': 'primary',
            onClick: this.proxy(this.onSave)
          });
          return [s,b];

        case 'string':
        default:
          return new TextBox(can.extend({
            trim: true
          }, params));
      }
    },

    addRow: function(dij) {
      if (dij == null)
        return;

      var f = $(this.form.domNode);
      var cl = '';
      if (dij.fields)
        cl = 'group-row';
      var row = $('<div class="input-row ' + cl + ' cf" />').appendTo(f);
      if (can.isArray(dij))
        dij = { fields: dij };

      if (!(dij.type == 'check')) {
        if (dij.label)
          dij.rowLabel = dij.label;
        else if (!dij.placeholder)
          dij.rowLabel = can.capitalize(dij.name || '');
      }

      if (dij.rowLabel != '') {
        var s = can.sub('<label for="{id}">{rowLabel}</label>', dij);
        $(s).appendTo(row);
      }

      var field = $('<div class="input-field input-field-' + dij.type + '" />').appendTo(row);
      var fields = this.fields;

      if (dij.fields) {
        can.each(dij.fields, function(dij) {
          fields[dij.name] = dij;
          $(can.sub('<label for="{id}" class="before">{before}</label>', dij)).appendTo(field);
          dij.placeAt(field[0]);
          $(can.sub('<label for="{id}" class="after">{after}</label>', dij)).appendTo(field);
        });
      } else {
        fields[dij.name] = dij;
        $(can.sub('<label for="{id}" class="beforeX">{before}</label>', dij)).appendTo(field);
        dij.placeAt(field[0]);
        $(can.sub('<label for="{id}" class="afterX">{after}</label>', dij)).appendTo(field);
      }

      return dij;
    },

    addFields: function(defs, adder) {
      if (!defs)
        return;

      if (!adder)
        adder = this.proxy(this.addRow);
      if (defs.items || defs.fields)
        defs = defs.items || defs.fields;

      can.each(defs, this.proxy(function(def, name) {
        def = can.extend({ name: name }, def);
        if (this.scope.hasOwnProperty(def.name))
          return;

        var dij = this.createField(def);
        adder(dij);
      }));
    },

    encType: 'multipart/form-data',
    'class': 'input-form',
    action: '',
    method: '',
    onSubmit: function() {
      if (this.validate())
        this.onSave();
      return false;
    },


        // SteveE: This is a hack to form.get('value') to overwrite Dojo's awful handling of form
        // values.  It actually emulates HTML's bizarre checkbox submit behaviors, but even worse, it 
        // automatically puts the results of even a single checkbox into an array!  
        // This undoes that madness.
        _getValueAttr: function(){
          var obj = { };
          array.forEach(this._getDescendantFormWidgets(), function(widget){
            var name = widget.name;
            if(!name || widget.disabled){ return; }

            // Single value widget (checkbox, radio, or plain <input> type widget)
            var value = widget.get('value');
            if (typeof(value) == 'number' && isNaN(value)) 
              value = null;

            // STEVEE MIXIN:
            // Date values -> convert them
            if (value && value.getTime) {
              value = window.moment(value).format('YYYY-MM-DD');
            }

            // STEVEE MIXIN:
            // Store widget's value(s) as a scalar, except for widgets with MULTIPLE attr
            if(widget.multiple){
              if(/Radio/.test(widget.declaredClass)){
                // radio button
                if(value !== false){
                  lang.setObject(name, value, obj);
                }else{
                  // give radio widgets a default of null
                  value = lang.getObject(name, false, obj);
                  if(value === undefined){
                    lang.setObject(name, null, obj);
                  }
                }
              }else{
                // checkbox/toggle button
                var ary=lang.getObject(name, false, obj);
                if(!ary){
                  ary=[];
                  lang.setObject(name, ary, obj);
                }
                if(value !== false){
                  ary.push(value);
                }
              }
            }else{
              var prev=lang.getObject(name, false, obj);
              if(typeof prev != "undefined"){
                if(lang.isArray(prev)){
                  prev.push(value);
                }else{
                  lang.setObject(name, [prev, value], obj);
                }
              }else{
                // unique name
                lang.setObject(name, value, obj);
              }
            }
          });

          return obj;
        },

    saveLabel: 'Save',
    cancelLabel: 'Cancel',

    createButtons: function() {
      var row = [
        new Button({
          label: this.saveLabel,
          'class': 'primary',
          onClick: this.proxy(this.onSave)
        })
      ];

      if (this.cancelLabel)
        row.push(new Button({
          label: this.cancelLabel,
          onClick: this.proxy(this.onCancel)
        }));

      this.addRow(row);
    },

    updateFields: function(values) {
      can.each(this.fields, function updateFields(f) {
        if (f.isReadonly)
          return;
        var val = can.getObject(f.name, values);

        // Fix DateTextBox
        if (f.dateModule) {
          var d = moment(val);
          if (!d || !d.isValid())
            val = null;
          else
            val = d.format("YYYY-MM-DD");
        } else if (val == null)
          val = f.defaultValue || '';

        if (typeof f.checked == 'boolean')
          f.set('checked', val == 'true' || val == true);
        else
          f.set('value', val);
      });
    },
    
    onSave: function() {
      var values = can.extend({}, this.scope, this.form.get('value'));
      this.updateModel(values); // TODO:, true);

      var dom = this.domNode;
      $(dom).block();
      can.when(this.saveModel())
        .then(this.proxy(this.onSaved), this.proxy(this.onSaveFailed))
        .always(function() {
          $(dom).unblock();
        });
    },

    onSaved: function(model) { 
      // Load returned values back into the model
      // (these may not be any different)
      this.loaded(model);
      on.emit(this, 'saved');
    },

    onSaveFailed: function(xhr) { 
      var error = 'Save failure';
      try {
        var json = $.parseJSON(xhr.responseText);
        if (json && json.error)
          error = json.error;
      } catch (e) { }
      alert(error);
    },

    onCancel: function() {
      if (this.closable)
        this.close();
    },

    close: function(route) {
      if (route) {
        // pass in a generator?
        if (route.getRoute)
          route = route.getRoute();
        this.el.trigger('open-tab', route);
      }
      this.el.trigger('close-tab', this);
    }
  });

  EditPane.For = function(model, def) {
    return declare("_EditPane", [EditPane, IHaveModel], can.extend(true, {
      modelType: model
    }, def));
  };
  EditPane.ForNew = function(model, def) {
    return declare("_EditPane", [EditPane, IHaveModel.ICreateNew], can.extend(true, {
      modelType: model,
      inDialog: true
    }, def));
  };

  return EditPane;
});
