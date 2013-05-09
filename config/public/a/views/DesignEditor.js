define([
  "declare", "can", "jquery",
  "views/EditPane",

  "controls/Panel", 
  "dijit/layout/BorderContainer",
  "dijit/layout/ContentPane",
  "dijit/form/Form",
  "dijit/form/TextBox",
  "dijit/form/CheckBox",
  "dijit/form/Textarea",
  "dijit/form/CurrencyTextBox",
  "dijit/form/DateTextBox",
  "dijit/form/Button"
], function(declare, can, $,
  EditPane,

  Panel,
  BorderContainer,
  ContentPane,
  Form, 
  TextBox,
  CheckBox,
  Textarea,
  CurrencyTextBox,
  DateTextBox,
  Button
){

  // Helper functions for editing panes
  var Editor = declare("DesignEditor", [EditPane, IHaveModel], {
    designID: '',
    heading: false,

    addSection: function(def, region) {
      var section = $(this.form.domNode).find("section." + region + "-pane").addClass('available enabled');
      if (section.length == 0) 
        return; // No section in the template

      def.name = def.name || (this.designID + '.' + region);
      def.label = def.label || can.capitalize(region);
      def.type = def.type || 'panel';

      $(can.sub('<h4 class="section-label">{label}</h4>', def)).appendTo(section);
      $(can.sub('<div class="if-disabled"><b>{label}</b> turned off</div>', def)).appendTo(section);

      var fields = this.fields;
      var _this = this;

      var sectionContent = $('<div class="section-content if-enabled" />').appendTo(section);

      if (def.hasOwnProperty('title')) {
        var name = def.name + '.title';
        fields[name] = _this.createField({
          name: name,
          type: 'text',
          'class': 'custom-title',
          defaultValue: def.title,
          trim: true
        });
        fields[name].placeAt(sectionContent[0]);
      }
      
      if (def.fields) {
        can.each(def.fields, function(def2, name) {
          def2.name = def2.name || (def.name + '.' + name);
          var dij = _this.createField(def2);
          fields[dij.name] = dij;
          $(can.sub('<label for="{id}">{before}</label>', dij)).appendTo(sectionContent);
          dij.placeAt(sectionContent[0]);
          $(can.sub('<label for="{id}">{after}</label>', dij)).appendTo(sectionContent);
        });
 
        // Add a placeholder image
        if (can.isFunction(def.placeholder)) {
          var f = can.proxy(def.placeholder, this);
          f(sectionContent);
        } else if (def.placeholder) {
          $(can.sub('<img class="placeholder" src="/a/i/{placeholder}">', def)).appendTo(sectionContent);
        }

      } else if (def.type) {
        def.name = def.source || def.name;

        var dij = this.createField(def);
        fields[dij.name] = dij;
        dij.placeAt(sectionContent[0]);
      } 

      if (def.optional) {
        var dij = this.createField({
          type: 'check',
          value: true,
          name: this.designID + '.show.' + region,
          'class': 'show-hide',
          onChange: function(val) {
            _this.enableSection(section, val == true);
          }
        });
        fields[dij.name] = dij;
        dij.placeAt(section[0]);
        this.enableSection(section, dij.get('value'));
      }

    },

    createButtons: function() { },

    enableSection: function(section, enabled) {
      if (enabled)
        section.addClass('enabled').removeClass('disabled');
      else
        section.removeClass('enabled').addClass('disabled');
      if (section.hasClass('placeholder'))
        section.removeClass('enabled');
    },

    createView: function() {
      // Don't create a view
    },

    addFields: function(defs, adder) {
      if (!defs)
        return;

      if (adder) {
        can.each(defs, this.proxy(function(def, name) {
          var dij = this.createField(can.extend({ name: this.designID + '.' + name }, def));
          adder(dij);
        }));
        return;
      }

      $(this.domNode).addClass('design-editor design-' + this.designID);
      var f = $(this.form.domNode);

      var _this = this;
      require(["text!t/" + this.template + ".hbs"], function(template) {
        f.html(template);
        can.each(defs, _this.proxy(_this.addSection));
      });
    },

    rezoom: function() {
      var el = $(this.domNode);
      var w = el.outerWidth();
      if (w == 0)
        return;

      var z = 100;
      if (w < 1400)
        z = w / 15;

      el.find('.design').css('zoom', z+'%');
    },

    onShow: function() {
      this.inherited(arguments);

      setTimeout(can.proxy(function() {
        $(window).resize(can.proxy(this.rezoom,this));
        this.rezoom();
      },this), 100);

      // Turn on the inline editor for all editable sections
      var editors = $(this.domNode).find('div.editable');
      $.each(editors, function(i) {
        $(this).attr('contenteditable', 'true');
        CKEDITOR.inline(this, {
          // allowedContent: 'img p b i a(href text-align)'
        });
      });
    }
  });

  // Helper functions for editing panes
  var DesignEditor = declare("DesignEditor", [BorderContainer, ICan], {
    title: "Design",
    designID: '',
    heading: false,

    postCreate: function() {
      this.inherited(arguments);

      this.addChild(this.toolbar = new ContentPane({
        region: 'top',
        'class': 'design-editor-toolbar'
      }));
      var c = $(this.toolbar.domNode);

      this.addChild(this.editPane = new Editor({
        region: 'center',
        modelType: this.modelType,
        designID: this.designID,
        template: this.template,
        createFields: this.createFields,
        scope: this.scope
      }));

      // When fetching the model, skip me
      this.editPane.getParentPane = this.proxy(function() {
        return this.parentPane;
      });

      var save = new Button({
        label: 'Save',
        'class': 'primary',
        onClick: can.proxy(this.editPane.onSave, this.editPane),
      });
      save.placeAt(c[0]);

      this.form = this.editPane.form;
    }

  });

  DesignEditor.For = function(model, def) {
    return declare("_DesignEditor", [DesignEditor, IHaveModel], can.extend(true, {
      modelType: model
    }, def));
  };

  return DesignEditor;
});
