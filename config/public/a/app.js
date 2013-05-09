define([
  'dojo/_base/declare',
  'require',
  'jquery',
  'dojo/on',

  'dijit/layout/ContentPane',
  'dijit/MenuBar',
  'dijit/PopupMenuBarItem',
  'dijit/MenuBarItem',
  'dijit/MenuItem',
  'dijit/PopupMenuItem',
  'dijit/DropDownMenu',

  'controls/PartnerPicker',

  'views/ViewPane',
  'views/InfoPane',

  'ready'
], function(
  declare,
  require,
  $,
  on,

  ContentPane,
  MenuBar,
  PopupMenuBarItem,
  MenuBarItem,
  MenuItem,
  PopupMenuItem,
  DropDownMenu,

  PartnerPicker,

  ViewPane,
  InfoPane
) {
  var App = InfoPane.For(null, {
    'class': 'admin-app root-window',
    id: 'admin-app',
    heading: false,
    route: '',
    paneDefaults: {
      heading: false,
      closable: true
    },

    addMenu: function(par, child) {
      if (can.isArray(child)) {
        can.each(child, function(c) { par.addChild(c); });
      } else {
        par.addChild(child);
      }
      return par;
    },

    createMenu: function (def, itemType) {
      if (can.isArray(def)) {
        var children = [];
        var _this = this;
        can.each(def, function(d) { children.push(_this.createMenu(d, itemType)); });
        return children;
      }

      var submenu = null;
      var onClick = null;

      if (def.type)
        itemType = def.type;

      if (def.items) {
        itemType = itemType || PopupMenuBarItem;
        var menuType = def.menuType || DropDownMenu;
        submenu = new menuType({ region: 'none' });
        this.addMenu(submenu, this.createMenu(def.items, MenuItem));
      } else if (def.ref) {
        itemType = itemType || MenuBarItem;
        onClick = this.proxy(function(ev) {
          this.switchTo(this.addPane(def.ref));
        });
      }

      return new itemType({
        label: def.label,
        onClick: onClick,
        popup: submenu
      });
    },

    createInfoPane: function() {
      this.menuBar = new MenuBar({ 
        region: 'top',
        class: 'primary inverse'
      });
      $(this.menuBar.domNode).prepend('<a class="brand open-tab">Administration</a>');
      return this.menuBar;
    },
    fillMenu: function(menuData) {
      if (menuData) {
        var brand = $(this.menuBar.domNode).find('.brand');

        brand.html(menuData.label);
        this.addMenu(this.menuBar, this.createMenu(menuData.items));

        if (menuData.actions) 
          this.home = menuData.actions['home'].ref;
        else
          this.home = '/';
        if (this.home)
          brand.attr('rel', this.home);

        if (window._open == '/') {
          history.replaceState(null, null, '/admin' + window._open);
          this.openTab(this.home);
          delete window._open;
        }
      } else {
        $(this.menuBar.domNode).hide();
      }
      this.resize();
    },

    init: function() {
      // Load the menu bar
      this.loadFrom('/a/api/admin.php?menu=admin')
        .done(this.proxy(this.fillMenu))
        .fail(this.proxy(this.aborted));
    },

    createPanes: function() {
      // Add initial content to the TabContainer
      this.addPane("home", { 
        title: 'Home',
        content: 'This is a summary page. <br><a class="open-tab" rel="/activity">Donation Report</a>',
        closable: false
      });
    },

    startup: function() {
      this.inherited(arguments);

      var my = this;
      this.el.on('click', 'a.open-tab', function openTabClicked(ev) {
        var rel = $(this).attr('rel');
        $(this).trigger('open-tab', rel);
        return false;
      });

      // Set initial URL state
      // TODO: this doesn't handle the IE case where we store location
      // in hash instead of URL
      var loc = window.location.pathname.split('/');
      if (window._open && (window._open != '/')) {
        history.replaceState(null, null, '/admin' + window._open);
        this.openTab(window._open);
        delete window._open;
      } else if (loc.length > 0 && loc[1] == 'a')
        history.replaceState(null, null, '/admin');

      // Change URL with tab changes
      this.container.watch("selectedChildWidget", function(name, oldC, newC){
        if (newC.route)
          history.replaceState(null, null, '/admin' + newC.route);
      });
    }
   
  });

  App.Declare = function App_declare(t) {
     return declare('_tt', t, {});
  };

  App.Route = function App_route(route, t, routes) {
    // newRoute PART1:  This resets "newRoute" for all grids
    // (overriding it at this subclass even if it's enabled on a parent Grid class)
    // until a later ViewPane turns it on by creating a "new" route
    if (route == 'new' && this.prototype.isGrid)
      this.prototype.newRoute = route;
    if (t.prototype.isGrid)
      t.prototype.newRoute = null;

    // Automatic loading of fields
    if (!t.prototype.loadFields)
      t.prototype.loadFields = route == 'new' ? this.route + '/' + route : route;

    // Set up routine
    t.Route = App.Route;
    t.Declared = true;
    t.route = route;
    if (this.route)
      t.route = this.route + '/' + t.route;
    can.route(t.route, { viewer: t });

    // Now do sub-routes
    if (routes)
      can.each(routes, function(value,index) {
        t.Route(index, value);
      });

    return t;
  };

  return App;
});
