define([
  "dojo/aspect", "can", "jquery", "dojo/on", "declare",

  "views/ViewPane",
  "views/EditPane",
  "dijit/layout/ContentPane",
  "dijit/form/Button",

  "v/accounting.min",

  "slick/slick.grid",
  "slick/slick.dataview",
  "slick/controls/slick.columnpicker",
  "slick/controls/slick.pager",
  "slick/slick.groupitemmetadataprovider",
  "slick/plugins/slick.cellrangedecorator",
  "slick/plugins/slick.cellrangeselector",
  "slick/plugins/slick.cellselectionmodel",
  "slick/plugins/slick.rowselectionmodel"
], function(
  aspect, can, $, on, declare,

  ViewPane,
  EditPane,
  ContentPane,
  Button,

  accounting
){
  var GridPane = declare("GridPane", [ViewPane], {
    design: "sidebar",
    isGrid: true, // for easy class detection

    gridOptions: {
      enableCellNavigation: true,
      enableCellRangeSelection: true,
      enableColumnReorder: false
    },

    getTitle: function() {
      return this.modelType ? this.modelType.getPlural().capitalize() : null;
    },

    createInfoPane: function() {
    },

    createGrid: function() {
      var _this = this;
      var word = this.modelType ? this.modelType.getWord() : 'item';

      // Create the left options pane
      this.addChild(this.optionsPane = new EditPane({
        region: "top",
        'class': "gridpane_sidebar",
        splitter: true,
        // minSize: 240,
        // style: 'width: 240px;',

        saveLabel: 'Filter',
        cancelLabel: null,
        scope: this.scope,

        createFields: function() {
          $(this.domNode).addClass('grid-options');

          if (_this.hasSearch)
            this.addFields({
              search: {
                type: 'search',
                placeholder: word ? word + ' search' : 'search'
                // ?
              }
            });

          var b,actions = $('<div class="grid-actions" />').appendTo(this.form.domNode);
          if (_this.newRoute) {
            b = new Button({
              label: '<i class="icon-asterisk"></i> ' + (word ? 'New ' + word : 'Create new'),
              onClick: function() {
                _this.el.trigger('open-tab', _this.newRoute);
              }
            });
            b.placeAt(actions[0]);
          }
          b = new Button({
            label: '<i class="icon-download-alt"></i> save',
            onClick: _this.proxy(_this.download)
          });
          b.placeAt(actions[0]);
          b = new Button({
            label: '<i class="icon-refresh"></i> reload',
            onClick: _this.proxy(_this.reload)
          });
          b.placeAt(actions[0]);

          if (_this.buildOptionsPane) {
            _this.buildOptionsPane(this);
            return true;
          }

          var buttons = false;
          if (_this.hasDates) {
            this.addFields({
              from: {
                placeholder: 'From date',
                type: 'date'
              },
              to: {
                placeholder: 'To date',
                type: 'date'
              }
            });
            buttons = true;
          }
          return buttons;
        },

        onSave: function() {
          var values = this.form.get('value');
          can.each(values, function(value,index) {
            if (value && value.getTime) {
              var d = value.getFullYear() + '-' + (value.getMonth()+1) + '-' + value.getDate();
              values[index] = d;
            }
          });
          this.updateModel(values);
          can.extend(_this.scope, values);
          _this.reload();
        }

      }));

      // Create the central grid pane
      this.addChild(this.gridPane = new ContentPane({
        region: "center",
        "class": "slickgrid"
      }));
    },

    getValues: function() {
      return can.extend({}, this.scope, this.optionsPane.getValues());
    },

    reload: function() {
      var el = $(this.gridPane.domNode);

      el.block();
      can.when(this.loadModels()) // Grids load modelS not model
        .done(this.proxy(this.loaded))
        .fail(this.proxy(this.loadError));
      return this;
    },

    download: function() {
      alert('csv');
    },

    loaded: function(list) {
      $(this.gridPane.domNode).unblock();

      if (list.error) {
        this.showGridError(list.error);
      }

      // Columns come in a funny format
      if (list.columns) {
        var columns = GridPane.MakeColumns(list.columns);
        this.idProperty = GridPane.FindIdField(columns);
        columns = this.modifyColumns(columns);

        // Merge in place because other objects hold a reference to this
        // particular array.
        Array.prototype.splice.apply(this.gridColumns, [0,this.gridColumns.length].concat(columns));

        this.grid.setColumns( this.gridColumns );

        // Create metadata array, because 
        var meta = {};
        for (var i = 0; i < columns.length; i++) {
          meta[columns[i].field] = columns[i];
        }

        if (meta) {
          var _this = this;
          this.data.getItemMetadata = this.dataView.getItemMetadata = function(index) {
            var cssClasses;
            var row;

            if (_this.getRowClass) {
              var row = _this.dataView.getItemByIdx(index);
              cssClasses = _this.getRowClass(row);
            }

            return {
              cssClasses: cssClasses,
              columns: meta
            };
          }
        }

      }

      // provide functions that GridPanes need
      list.sort = [].sort;
      list.reverse = [].reverse;
      // TODO: concat?

      $(this.domNode).find('.slick-viewport').unblock();
      $(this.domNode).removeClass('grid-error');
      try {
        this.dataView.beginUpdate();
        this.dataView.setItems(this.data = list, this.idProperty);
        this.dataView.endUpdate();
        this.grid.setData(this.dataView);
      } catch (e) {
        this.showGridError('Unable to load the grid (' + (e.message || e) + ')');
      }

      if ((list.length == 0) && this.emptyMessage && !list.error) {
        var msg = this.emptyMessage;
        if (this.newRoute)
          msg += '  Want to <a class="open-tab" rel="' + this.newRoute + '">create one now</a>?';
        this.showGridMessage(msg);
      }

      this.grid.render();
    },

    loadError: function(xhr) {
      $(this.gridPane.domNode).unblock();

      var js = (xhr && xhr.responseText) ? $.parseJSON(xhr.responseText) : {}
      this.showGridError(js.error || 'An error occurred while loading the grid.');
    },

    showGridMessage: function(msg) {
      $(this.gridPane.domNode).block({
        overlayCSS: { cursor: 'auto' },
        message: '<span class="grid-message">' + msg + '</span>'
      });
    },

    showGridError: function(msg) {
      $(this.gridPane.domNode).block({
        message: '<span class="grid-error"><i class="icon-warning-sign"></i> ' + msg + '</span>'
      });
    },

    getRowClass: function(data) { },

    modifyColumns: function(columns) {
      columns = can.map(columns, this.mapColumn || GridPane.MapColumn);
      var meta = can.extend(true, {
         id: {hide: true }, // Is this a good idea?
        campaign: { formatter: GridPane.Campaign },
        fundraiser: { formatter: GridPane.Fundraiser },
        team: { formatter: GridPane.Team },
        partner: { formatter: GridPane.Partner },
        donor: { width: 450, formatter: GridPane.Donor },
        gift: { formatter: GridPane.Gift },
        account: { formatter: GridPane.Account },
        address: { width: 450, formatter: GridPane.Address },
        user: { formatter: GridPane.FirstLastEmail }
      }, this.gridColumnsMeta);

      var i = 0;
      while (i < columns.length) {
         var column = columns[i];
         can.extend(column, meta[column.field]);
         if (column.owns)
           can.extend(column, meta[column.owns]);

         if (this.scope.hasOwnProperty(column.group))
           column.hide = true;

         // Hide columns by removing them from the list
         if (column.hide) {
           // column.width = 5;
           var spanned = column.colspan || 1;
           columns.splice(i, spanned);
           if (column.group) {
             for (var j = i-1; j >= 0 && columns[j].group == column.group; j--) {
               if (columns[j].colspan > 1) {
                 columns[j].colspan -= spanned;
                 break;
               }
             }
           }
           continue;
         }

         // Shrink columns that are spanned
         for (var j = 1; j < column.colspan && columns[i+j]; j++) {
           columns[i+j].width = 80;
           column.width -= 80;
         }

         i++;
      }
      return columns;
    },

    clearGroups: function() {
      this.dataView.groupBy(null);
    },

    groupBy: function(args) {
      this.dataView.groupBy( args.column, args.format, args.compare );
    },

    collapseAllGroups: function() {
      this.dataView.beginUpdate();
      for (var i = 0; i < this.dataView.getGroups().length; i++) {
        this.dataView.collapseGroup(this.dataView.getGroups()[i].value);
      }
      this.dataView.endUpdate();
    },

    expandAllGroups: function() {
      this.dataView.beginUpdate();
      for (var i = 0; i < dataView.getGroups().length; i++) {
        this.dataView.expandGroup(this.dataView.getGroups()[i].value);
      }
      this.dataView.endUpdate();
    },

    start: function() {
      this.inherited(arguments);

      $(this.optionsPane.domNode).focusFirst();
    },

    startup: function() {
      this.inherited(arguments);

      this.createGrid();

      var groupItemMetadataProvider = new Slick.Data.GroupItemMetadataProvider();
      this.dataView = new Slick.Data.DataView({
        // groupItemMetadataProvider: groupItemMetadataProvider,
        // inlineFilters: true
      });
      this.data = [];

      var meta = this.gridColumnsMeta;
      if (meta) {
        this.data.getItemMetadata = this.dataView.getItemMetadata = function(row) {
          return { columns: meta };
        }
      }

      this.gridColumns = [];
      if (this.columns) {
        this.idProperty = GridPane.FindIdField(this.columns);
        this.gridColumns = this.modifyColumns(this.columns);
      }

      this.el.height(500);
      this.gridEl = $(this.gridPane.domNode).css('overflow','hidden').height(500); 
      var grid = this.grid = new Slick.Grid(this.gridEl, this.data, this.gridColumns, this.gridOptions);

      // register the group item metadata provider to add expand/collapse group handlers
      grid.registerPlugin(groupItemMetadataProvider);
      grid.setSelectionModel(new Slick.RowSelectionModel());

      this.pager = new Slick.Controls.Pager(this.dataView, grid, $("#pager"));
      this.columnpicker = new Slick.Controls.ColumnPicker(this.gridColumns, grid, this.gridOptions);

      grid.onSort.subscribe(this.proxy(function (e, args) {
        var col = args.sortCol;
        var sorter = col.sorter || GridPane.Sorter;

        this.dataView.sort(sorter(col.field), args.sortAsc);
      }));

      aspect.after(this.gridPane, 'resize', function(o,n) {
        // var c = myCP.closest('.dijitContainer');
        // $("#myGrid").width(myCP.width() - 22).height(c.height() - 22);
        grid.resizeCanvas();
      }, true);

      // wire up model events to drive the grid
      this.dataView.onRowCountChanged.subscribe(function (e, args) {
        grid.updateRowCount();
        grid.render();
      });

      this.dataView.onRowsChanged.subscribe(function (e, args) {
        grid.invalidateRows(args.rows);
        grid.render();
      });

      var _this = this;
      this.el.on('created', function refresh_grid(e) {
        _this.reload();
      });
    }

  });

  GridPane.For = function(model, def) {
    var word = model ? model.getPlural() : 'items';
    var bases = [GridPane];
    if (model)
      bases.push(IHaveModel);

    return declare("_GridPane", bases, can.extend(true, { 
      modelType: model, 
      hasSearch: true,
      emptyMessage: 'There are no ' + word + ' yet.',
      noMatchMessage: 'No matching ' + word + '.'
    }, def));
  };

  // Parse a column:
  //  Use the basic definition (field, name, id) to create a grid column
  //  If the name is "group:X" then begin a colspan'd container, and remove
  //  the prefix X_ from the name of all contained columns.
  GridPane.ParseColumn = function(name, def, cols) {
    if (def == true) {
      def = { 'type': 'string' };
    } else if (typeof(def) == 'string') {
      def = { 'type': def, grouper: function(data) { return data; } };
    } else if (name.indexOf('group:') === 0) {
      var span = null;
      name = name.substring(6);

      can.each(def, function(c, n) {
        var d = GridPane.ParseColumn(n, c, cols);

        var sname = d.field.replace(name+'_', '');
        d.group = name;
        if (!d.name)
          d.name = sname;

        if (span == null) {
          span = d;
          span.name = name;
          span.owns = name;
          span.colspan = 1;
          span.grouper = function(data) {
            var g = {};
            g[sname] = data[d.field];
            return g;
          };
        } else {
          span.colspan++;
          var f = span.grouper;
          span.grouper = function(data) {
            var g = f(data);
            g[sname] = data[d.field];
            return g;
          };
        }

      });

      return span;
    }

    def.field = def.field || name;
    def.id = def.field;

    cols.push(def);
    return def;
  };

  GridPane.FindIdField = function(cols) {
    for (var i = 0; i < cols.length; i++) {
      if (cols[i].type == 'id')
        return cols[i].field;
    }

    return cols[0].field;
  };

  GridPane.MakeColumns = function(cols) {
    var c = [];

    for (var p in cols) {
      GridPane.ParseColumn(p, cols[p], c);
    }

    return c;
  };

  GridPane.MapColumn = function(col) {
    col.id = col.field;
    col.name = (col.name || col.field || '').replace('_',' ');

    switch (col.type) {
      case 'custom':
        col.formatter = GridPane.Custom;
        col.width = col.width || 100;
        break;

      case 'date':
        col.formatter = GridPane.Date;
        col.width = col.width || 150;
        col.sortable = true;
        col.sorter = col.sorter || GridPane.DateSorter;
        break;

      case 'money':
        col.formatter = GridPane.Currency;
        col.width = col.width || 100;
        col.sortable = true;
        col.name = '$ ' + col.name;
        col.sorter = col.sorter || GridPane.NumberSorter;
        break;

      case 'int':
        col.width = col.width || 50;
        col.sortable = true;
        col.sorter = col.sorter || GridPane.NumberSorter;
        break;

      case 'id':
        col.width = col.width || 50;
        col.sortable = true;
        col.sorter = col.sorter || GridPane.NumberSorter;
        break;

      case 'count':
        col.width = col.width || 80;
        col.sortable = true;
        col.name = '# ' + col.name;
        col.sorter = col.sorter || GridPane.NumberSorter;
        break;

      case 'percent':
        col.width = col.width || 50;
        col.formatter = GridPane.Percentage;
        col.sortable = true;
        col.name = '% ' + col.name;
        col.sorter = col.sorter || GridPane.NumberSorter;
        break;

      case 'url':
        col.formatter = GridPane.Url;
        col.width = col.width || 300;
        col.sorter = col.sorter || GridPane.StringSorter;
        col.sortable = true;
        break;

      case 'bool':
      case 'check':
        col.formatter = GridPane.YesNo;
        col.width = col.width || 80;
        col.sorter = col.sorter || GridPane.NumberSorter
        col.name = col.name + '?';
        col.sortable = true;
        break;

      case 'title':
        col.width = col.width || 300;
        col.sorter = col.sorter || GridPane.StringSorter;
        col.sortable = true;
        break;

      case 'address':
        col.width = col.width || 400;
        col.formatter = GridPane.Address;
        col.sorter = col.sorter || GridPane.StringSorter;
        col.sortable = true;
        break;

      case 'text':
        col.width = col.width || 450;
        col.sorter = col.sorter || GridPane.StringSorter;
        col.sortable = true;
        break;

      case 'email':
        col.width = col.width || 150;
        col.sorter = col.sorter || GridPane.StringSorter;
        col.sortable = true;
        break;

      case 'tags':
        col.width = col.width || 150;
        col.formatter = GridPane.Tags;
        break;

      case 'partner':
        col.formatter = GridPane.Partner;
        col.width = col.width || 180;
        col.sorter = col.sorter || GridPane.StringSorter;
        col.sortable = true;
        break;

      default:
        col.width = col.width || 150;
        col.sorter = col.sorter || GridPane.StringSorter;
        col.sortable = true;
        break;
    }

    col.name = col.name.replace('custom.', '');
    col.name = can.capitalize(col.name);
    col.name = (' ' + col.name + ' ').replace(/ id /gi,' ID ').trim();
    col.width += 60 * (col.colspan || 0);

    return col;
  };

  GridPane.Date = function(row, cell, value, columnDef, dataContext) {
    var d = new Date(value);
    if (d.getTime() === d.getTime())
      val = d.toDateString();
    else
      val = "";
    
    return '<span value="' + value + '">' + val + '</span>';
  };

  GridPane.Percentage = function(row, cell, value, columnDef, dataContext) {
    var pct = (Math.round(Number(value) * 10000) / 100).toFixed(2);
    if (pct == 0)
      return '';
    return '<span value="' + value + '">' + pct + '%</span>';
  };

  GridPane.Custom = function(row, cell, value, columnDef, dataContext) {
    return can.getObject(columnDef.field, dataContext);
  };

  GridPane.YesNo = function(row, cell, value, columnDef, dataContext) {
    if (value == true || value == 1)
      return '<span class="yes">Yes</span>';
    return '<span class="no">No</span>';
  };

  GridPane.Currency = function(row, cell, value, columnDef, dataContext) {
    var val = Number(value).toFixed(2);
    if (value < 0)
      return "-" + accounting.formatMoney(-val);
    else if (value > 0)
      return accounting.formatMoney(val);
    else
      return "";
  };

  GridPane.FirstLastEmail = function(row, cell, value, columnDef, dataContext) {
    var data = columnDef.grouper(dataContext);

    var name = (data.first || data.last) ? (data.first + ' ' + data.last) : '';
    var s = can.esc(name);
    
/*
    if (data.id > 0) 
      s = '<a class="open-tab" rel="user/' + data.id + '">' + s + '</a>';
*/

    if ((data.email || '') != '')
      s += ' &lt;' + data.email + '&gt;';

    if (data.id > 0) 
      s = s + '<span class="field-id"> #' + data.id + '</span>';

    return s;
  }

  GridPane.Address = function(row, cell, value, columnDef, dataContext) {
    var data = columnDef.grouper(dataContext);

    var val = 
      data.address + ' ' +
      data.address2 + ' ' +
      data.city + ' ' +
      data.state + ' ' +
      data.zip;
    if (val == 'undefined')
      val = '';

    return val;
  }


  GridPane.Sorter = function(col) {
    // using native sort with comparer
    // preferred method but can be very slow in IE with huge datasets

    return function(a, b) {
      var x = a[col], y = b[col];

      return (x == y ? 0 : (x > y ? 1 : -1));
    };
  };

  GridPane.NumberSorter = function(col) {
    return function(a, b) {
      var x = Number(a[col]), y = Number(b[col]);

      return (x == y ? 0 : (x > y ? 1 : -1));
    }
  };

  GridPane.StringSorter = function(col) {
    return function(a, b) {
      var x = String(a[col]||'').toLowerCase(), y = String(b[col]||'').toLowerCase();

      return (x == y ? 0 : (x > y ? 1 : -1));
    }
  };

  GridPane.DateSorter = function(col) {
    return function(a, b) {
      var x = new Date(a[col]), y = new Date(b[col]);

      return (x == y ? 0 : (x > y ? 1 : -1));
    }
  };


  // Todo: start using pre-compiled mustache templates here to avoid escaping, etc.

  GridPane.User = function(row, cell, value, columnDef, dataContext) {
    var data = columnDef.grouper(dataContext);

    var s = can.esc(data.login);
    if (data.id > 0) {
      s = '<a class="open-tab" rel="user/' + data.id + '">' + s + '</a>';
      s = s + '<span class="field-id"> #' + data.id + '</span>';
    }

    if (data.fb_id > 0) {
      s = s + '<span class="field-id"> FB#' + data.fb_id + '</span>';
    }

    return s;
  };

  GridPane.Donor = function(row, cell, value, columnDef, dataContext) {
    var data = columnDef.grouper(dataContext);

    var s = can.esc(data.first + ' ' + data.last);
    if (s == ' ')
      s = '(no name)';
    if (data.id > 0) 
      s = '<a class="open-tab" rel="donor/' + data.id + '">' + s + '</a>';

    if ((data.email || '') != '')
      s += ' &lt;' + data.email + '&gt;';

    if (data.id > 0)
      s = s + '<span class="field-id"> #' + data.id + '</span>';
    return s;
  };

  GridPane.Partner = function(row, cell, value, columnDef, dataContext) {
    var data = columnDef.grouper(dataContext);

    var domain = data.domain || data.partner;
    var id = data.id || data.blog_id;

    var s = data.name;
    if (domain) {
      if (!s)
        s = domain;

      s = '<a class="open-tab" rel="partner/' + domain + '">' + can.esc(s) + '</a>';

      s = s + ' <span class="field-id">' + can.esc(domain) + '</span>';
    }

    if (dataContext.private || (dataContext.live === false || dataContext.live === 0))
      s = '<i class="icon-lock"></i> ' + s;
      
    if (id > 1) 
      s = (s || '(unknown)') + '<span class="field-id"> #' + id + '</span>';
    return s;
  };

  GridPane.Campaign = function(row, cell, value, columnDef, dataContext) {
    var data = columnDef.grouper(dataContext);

    var name = data.post_title || data.title;
    var id = data.id || data.name;

    var s = name;
    if (id) {
      if (!s)
        s = id;

      s = '<a class="open-tab" rel="campaign/' + id + '">' + can.esc(s) + '</a>';

      s = s + ' <span class="field-id">' + can.esc(id) + '</span>';
    }

    return s;
  };

  GridPane.Team = function(row, cell, value, columnDef, dataContext) {
    var data = columnDef.grouper(dataContext);

    var s = data.title;

    var id = data.name;
    if (id) {
      if (!s)
        s = id;

      s = '<a class="open-tab" rel="team/' + id + '">' + can.esc(s) + '</a>';

      s = s + ' <span class="field-id">' + can.esc(id) + '</span>';
    }

    return s;
  };

  GridPane.Units = function(row, cell, value, columnDef, dataContext) {
    if (!value)
      return;
    if (value <= 0)
      return '<span class="out-of-stock">out</span>';
    return value;
  };

  GridPane.Fundraiser = function(row, cell, value, columnDef, dataContext) {
    var data = columnDef.grouper(dataContext);

    var id = data.fr_id || data.id;

    var name = data.owner || data.display_name;
    if (data.owner_id <= 1)
      name = data.name;

    var s = can.esc(name);

    if (id > 0)
      s = '<a class="open-tab" rel="fundraiser/' + escape(id) + '">' + s + '</a>';

    if (data.team && (data.team != ''))
      s += ' (' + data.team + ')';

    if (id > 0)
      s += '<span class="field-id"> #' + id + '</span>';

    if (data.url) {
      var url = data.url.replace(/^http:\/\/.*?\//, '');
      var url = url.replace(/(.*)\/$/, '$1');
      s += '<a href="' + can.esc(data.url) + '"  target="_fundraiser" class="field-id">' + url + '</a>';
    }

    return s;
  };

  GridPane.Gift = function(row, cell, value, columnDef, dataContext) {
    var data = columnDef.grouper(dataContext);

    var s = can.esc(value);
    if (data.id > 0) {
      s = '<a class="open-tab" rel="gift/' + data.id + '">' + can.esc(s) + '</a>';

      s = s + '<span class="field-id"> #' + data.id + '</span>';
    }
    return s;
  };

  GridPane.Account = function(row, cell, value, columnDef, dataContext) {
    var data = columnDef.grouper(dataContext);

    var s = can.esc(value);
    if (data.id > 0)
      s = s + '<span class="field-id"> #' + data.id + '</span>';
    return s;
  };

  GridPane.Url = function(row, cell, value, columnDef, dataContext) {
    var url = can.esc(value);
    return '<a target="_url" href="' + url + '"><i class="icon-external-link"></i> ' + url + '</a>';
  };

  GridPane.Tags = function(row, cell, value, columnDef, dataContext) {
    if (!value)
      return '';

    var s = '';
    var tags = value.split(',');
    for (var i = 0; i < tags.length; i++) {
      if (tags[i].trim() == '')
        continue;
      s = s + ' <a class="tag">' + can.esc(tags[i]) + '</a>';
    }
    return s;
  };

  return GridPane;
});
