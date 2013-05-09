define([
  "declare", "can", "jquery",

  "dijit/Dialog",
  "dijit/layout/BorderContainer", 
  "dijit/layout/ContentPane",

  "views/IHaveAPI", "views/ICan"
], function(declare, can, $,

  Dialog,
  BorderContainer, 
  ContentPane,

  IHaveAPI, ICan
){
  var ViewPane = declare("ViewPane", [BorderContainer, IHaveAPI, ICan], {
    design: "headline",
    gutters: false,

    loaded: function(model) {
      // Set the title, if available
      model && model.getTitle && this.setTitle(model.getTitle());

      var _this = this;
      can.when(this.createPanes())
        .done(function(panes) {
          panes && panes.items && can.each(panes.items, function(pane) {
            var p = _this.addPane(pane.ref, { title: pane.label });
          });
        });
    },
    loadError: function() { },

    createView: function(dom) { },

    constructor: function() {
      this._ready = new can.Deferred();
    },

    startup: function() {
      this.el = $(this.domNode);
      this.inherited(arguments);

      var _this = this;

      this.el.on('open-tab', function onOpenTab(ev, route) {
        if (_this.openTab(route))
          ev.stopPropagation();
      });
      this.el.on('close-tab', function onCloseTab(ev, tab) {
        _this.closeTab(tab);
        ev.stopPropagation();
      });
      this.el.on('render', function onRender(ev, tab) {
        _this.resize();
      });

      this._ready.resolve(true);
    },

    setTitle: function(title) {
      this.set('title', title);
      $('.dijitTabPane[title]').removeAttr('title');
    },

    openTab: function(route) {
      // Try to open the pane
      var p = this.addPane(route, { closable: true } );
      if (!p)
        return false;
      this.switchTo(p);
      return true;
    },
    closeTab: function(tab) {
      var container = this.container || this;
      if (!tab.inDialog)
        container.removeChild(tab);
    },

    destroy: function() {
      this.pause();
      this.inherited(arguments);
    },

    // return panes OR a deferred promise
    createPanes: function() {
      if (this.loadPanes) 
        return this.loadFrom('/a/api/admin.php?menu=' + this.loadPanes);

      var panes = new can.Deferred();
      can.when(this.getLoadedModel())
        .done(function(model) {
           model && model.actions && panes.resolve(model.actions);
        });
      return panes;
    },

    // Find an existing pane, by route
    findPane: function(route) {
      if (route.indexOf("/") !== 0)
        route = this.route + "/" + route;

      var children = (this.container || this).getChildren();
      for (var i = 0; i < children.length; i++) {
        if (children[i].route == route)
          return children[i];
      }
    },

    setParentPane: function(pane) {
      this.parentPane = pane;
    },

    // Create a new pane, by route - but if that pane exists, recycle
    addPane: function(route, params) {
      if (route == '/')
        route = this.home || route;

      // first check existing
      var p = this.findPane(route);
      if (p)
        return p;

      var viewer = ContentPane;

      if (route != null) {
        if (route.indexOf("/") === 0) { 
          // enforce absolute paths
          if (route.indexOf(this.route) !== 0)
            return;
        } else {
          // try relative path
          route = this.route + "/" + route;
        }

        var scope = can.route.deparam(route);
        if (scope == null)
          return; // No match
        if (scope.viewer == null && this.route != "")
          return; // No match (anything matches at root - route "")

        viewer = scope.viewer || ContentPane;
        delete scope.viewer;
        delete scope.route;

        var legit = true;
        can.each(scope, function(val, attr) {
          // If any of the params have a / it means it didn't really match
          if (val.indexOf('/') !== -1)
            return legit = false;
        });
        if (!legit)
          return;

        if (params) {
          can.extend(scope, params.scope);
          delete params.scope;
        }
        
      }

      params = can.extend(true, { route: route, scope: scope }, this.paneDefaults, params);

      var container = this.container || this;
      var child = new viewer( params );
      child.parentPane = this;

      if (child.inDialog) {
        child.placeAt(document.body);

        var el = $(child.domNode).addClass('popup-dialog');
        var page = $("body").add("#admin-app").addClass('has-popup');

        el.lightbox_me({ 
          centered: true,
          zIndex: 900,
          closeEsc: !child.noClose,
          closeClick: !child.noClose,
          onLoad: function() {
            el.focusFirst();
          },
          onClose: function() {
            page.removeClass('has-popup');
            child.destroyRecursive();
          }
        });

        child.startup();
        el.on('close-tab', this.proxy(function closeTab(ev, tab) {
          if (tab == child)
            el.trigger('close');
        }));

        // TODO: move this to a better place.  Needs to be here right now because otherwise
        // events don't propagate out of the popup to our domNode
        el.on('open-tab', this.proxy(function openTab(ev, route) {
          this.el.trigger('open-tab', route);
          ev.stopPropagation();
        }));
        el.on('created', this.proxy(function openTab(ev, model) {
          this.el.trigger('created', model);
          ev.stopPropagation();
        }));

      } else {
        if (!child.title) {
          var title = child.getTitle ? child.getTitle() : null;
          child.set('title', title || 'loading...');
        }

        container.addChild(child);
      }

      return child;
    },

    addChild: function(child) {
      if (!child)
        return;

      this.inherited(arguments);
      // Store which tab opened me?  OR some kind of queue
    },

    removeChild: function() {
      this.inherited(arguments);

      // TODO: select the previously opened tab
      if (this._descendantsBeingDestroyed)
        return;

      var children = this.getChildren();
      if (children.length) {
        this.selectChild(children[length-1]);
      }
    },

    switchTo: function(child) {
      var container = this.container || this;

      if (!child.inDialog) {
        container.selectChild(child);
      }

      if (child.start)
        child.start();
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
    }
  });

  return ViewPane;
});
