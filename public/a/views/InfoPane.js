define([
  "can","declare",

  "views/ViewPane",
  "views/IHaveModel",
  "dijit/layout/ContentPane",
  "dijit/layout/TabContainer",

  "views/IRefresh"
], function(
  can,declare,

  ViewPane,
  IHaveModel,
  ContentPane,
  TabContainer,

  IRefresh
){
  var InfoPane = declare("InfoPane", [ViewPane, IRefresh], {
    // Create info containers
    postCreate: function() {
      if (!this.template && this.modelType)
        this.template = this.modelType.type_id + '-info';

      // Create the tabs
      this.addChild(this.container = this.createTabContainer({
        region: 'center'
      }));

      // Create the info pane view
      this.addChild(this.infoPane = this.createInfoPane({
        region: 'top',
        parentPane: this
      }));

      // Infopane views should grab full data so their children can inherit
      if (this.scope)
        this.scope.view = 'gallery'; // model.find will pass back galleries

      // Must create the view BEFORE falling into the defaults
      this.inherited(arguments);
    },

    createInfoPane: function(params) {
      var pane = new ContentPane(params || {});
      // Set this pane as our CanJS view
      this.createView(pane.containerNode);
      return pane;
    },

    createTabContainer: function(params) {
      return new TabContainer( can.extend({
        tabPosition: 'top' 
      }, params));
    },

    onModelChange: function(model) {
      var name = model.getTitle();
/* too bad... no HTML allowed in titles
      var icon = model.getIcon();
      if (icon)
        name = '<i class="icon icon-' + icon + '"></i> ' + (name || '');
*/
      name && this.setTitle(name);
    }

  });

  InfoPane.For = function(model, def) {
    var bases = [InfoPane];
    if (model)
      bases.push(IHaveModel);

    return declare("_EditPane", bases, can.extend(true, {
      modelType: model
    }, def));
  };

  return InfoPane;
});
