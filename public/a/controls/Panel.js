define([
  "declare", "can", "jquery",
  "dojo/dom-construct",
  "dijit/_WidgetBase",
  "views/IQuery",
  "views/IHaveModel",

  "models/panel"
], function(declare, can, $,
  domConstruct,
  _WidgetBase,
  IQuery,
  IHaveModel,

  PanelModel
){
  var uploader;

  var Panel = declare("Panel", [_WidgetBase, IQuery], {
    label: 'Content',
    ref: null,

    buildRendering: function() {
      this.domNode = domConstruct.create('div', {
        className: 'panel html-panel'
      });

      var ph = $('<div class="placeholder-content if-enabled">').appendTo(this.domNode);
      // var actions = $('<label class="actions if-enabled">').appendTo(this.domNode);
      // $('<u class="panel-action action-clear"><i class="icon icon-certificate"></i>new</u>').appendTo(actions);
      // $('<u class="panel-action action-edit"><i class="icon icon-edit"></i>edit text</u>').appendTo(actions);
      // $('<u class="panel-action action-revert"><i class="icon icon-undo"></i>revert</u>').appendTo(actions);

      var cl = this.canImage ? '' : 'editable';
      var d = $('<div class="panel-content if-enabled ' + cl + '" />').appendTo(this.domNode);
      if (this.canText !== false)
        d.attr('contenteditable','true');

      if (this.canImage)
        $('<div class="panel-action action-upload if-enabled"><i class="icon icon-picture"></i>upload an image</div>').appendTo(this.domNode);

    },

    _getValueAttr: function() {
      var html = $(this.domNode).find('.panel-content').html();
      this.updateModel({ html: html });
      return this.getValues();
    },

    _setValueAttr: function(value) {
      this.inherited(arguments);

      // TODO: if this is a string ref kick off an AJAX load
      this.updateModel(value, true);
      this.value = true;  // make sure it gets counted as a form descendant
    },

    onHtmlChange: function(ev, newVal, oldVal) {
      $(this.domNode).find('.panel-content').html(newVal);
      if (!newVal || (newVal == '')) {
        $(this.domNode).addClass('placeholder');
        if (this.canText !== false)
          $(this.domNode).find('.placeholder-content').html('click to edit');
        else if (this.canImage)
          $(this.domNode).find('.placeholder-content').html('<span class="action-upload">click to edit</span>');
        
      } else
        $(this.domNode).removeClass('placeholder');
    },

    postCreate: function() {
      var model = this.newModel();
      this.set('value', null);
      model.bind('html', can.proxy(this.onHtmlChange, this));
      this.hookUpload(this.domNode);

      var _this = this;

      // Lots of panels have <A HREF>'s, prevent those from actually being clickable
      $(this.domNode).on('click', '.panel-content', function(ev) {
        ev.preventDefault();
      });

      // Handle clicking on a placeholder to start
      $(this.domNode).on('click', '.placeholder-content', this.proxy(function(ev) {
        if (this.canText === false)
          return;

        this.updateModel({ html: ' ' });
        $(this.domNode).find('.panel-content').selectText();
        ev.preventDefault();
      }));

      // Handle the clear button
      $(this.domNode).on('click', '.action-clear', this.proxy(function(ev) {
        this.updateModel({ html: '' });
        ev.preventDefault();
      }));

      // Handle the upload button
      $(this.domNode).on('click', '.action-upload', this.proxy(function(ev) {
        if (!uploader)
          return;

        uploader.me = this;

        // NOT TESTED variation for older browers that can't "click" a file upload
        if (!uploader.features.triggerDialog) {
          var id = 'pluploader-' + Math.floor( Math.random()*99999 );
          $(this).attr('id', id);
          uploader.settings.browse_button = id;
          uploader.refresh();
          return;
        }

        // TESTED variation for newer browsers
        var input = document.getElementById(uploader.id + '_html5') || document.getElementById(uploader.settings.browse_button);
        if (input && !input.disabled) 
          input.click();
        ev.preventDefault();
      }));

      // Handle revert
      $(this.domNode).on('click', '.action-revert', this.proxy(function(ev) {
        var ref = this.getValue('ref');
        if (ref) {
          PanelModel.findOne({ ref: ref }, _this.proxy(function(m) {
            _this.updateModel(m.attr());
          }));
        }
      }));
    },

    onUploadStart: function(file) {
      $(this.domNode).addClass('uploading');
    },
    onUploadProgress: function(file) {
    },
    onUploadComplete: function(file, src) {
      $(this.domNode).removeClass('uploading');

      this.width = $(this.domNode).width()

      // Set the HTML of this panel to a simple image
      var style = "display: block;";
      if (this.width)
        style = style + "width:" + this.width + "px;";
      if (this.height)
        style = style + "height:" + this.height + "px;";
      var html = '<img src="' + src + '" style="' + style + '">';
      this.updateModel({
        html: html
      });
    },

    getUploadParams: function() {
      return { ref: this.getValue('ref') };
    },

    hookUpload: function(domNode) {

      // Create only one instance of pluploader
      if (uploader != null)
        return;

      var id = "pluploader";
      $('<div style="width:0;height:0;position:absolute;left:-9999px;" />').appendTo(document.body).attr('id', id);

      uploader = new window.plupload.Uploader({
        browse_button: id,
        // drop_element: 'content-' + this.id,
        runtimes: 'html5,flash,html4',
        url: '/a/api/upload.php',
        flash_swf_url: '/wp-includes/js/plupload/plupload.flash.swf',
        silverlight_xap: '/wp-includes/js/plupload/plupload.silverlight.xap',
        multipart: true,
        urlstream_upload: true,
        file_data_name: 'file', // TODO: params.data,
        multipart_params: null, // TODO: params,
        filters: [
          {title : "Image files", extensions : "jpg,jpeg,gif,png"}
        ]
      });

      uploader.init();
      uploader.bind('FilesAdded', function(up,files) {
        if (up.me) {
          up.settings.multipart_params = up.me.getUploadParams();
          up.me.onUploadStart(files[0]);
        }
        for (var i = 0; i < files.length; i++) {
          files[i].owner = up.me;
          var k = Math.round(files[i].size / 1000);
          if (k == 0)
            k = "<1";
          files[i].tag = files[i].name + '(' + k + 'k)';

          var icon = '<i class="icon icon-upload icon-3x pull-left"></i> ';
          files[i].notice = $.pnotify({
            icon: '',
            text: icon + files[i].tag + ':<div class="progress file-progress progress-striped active"><div class="bar" style="width: 0;"></div></div>',
            history: false,
            hide: false,
            closer: false,
            closer_hover: false,
            sticker: false
          });
        }
        up.start();
      });

      uploader.bind('UploadProgress', function(up,file) {
        if (file.owner)
          file.owner.onUploadProgress(file);
        if (file.notice) {
          var icon = '<i class="icon icon-upload icon-3x pull-left"></i> ';
          file.notice.pnotify({
            text: icon + file.tag + ':<div class="progress file-progress progress-striped active"><div class="bar" style="width: ' + file.percent + '%;"></div></div>'
          });         
        }
      });
      uploader.bind('FileUploaded', function(up,file, response) {
        if (file.owner)
          file.owner.onUploadComplete(file, response.response);
        if (file.notice) {
          var icon = '<i class="icon icon-ok icon-3x pull-left"></i> ';
          file.notice.pnotify({
            type: 'success',
            hide: true, delay: 2000,
            closer: true,
            text: icon + file.tag + ':<br>Upload complete!'
          });         
        }
      });
      uploader.bind('Error', function(up,file) {
        if (file.owner)
          file.owner.onUploadError(file);
        if (file.notice) {
          var icon = '<i class="icon icon-warning icon-3x pull-left"></i> ';
          file.notice.pnotify({
            type: 'error',
            hide: false,
            closer: true,
            text: icon + file.tag + ':<br> Failed!'
          });
          file.notice.effect('bounce');      
        }
      });

    }

  });

  return Panel;
});
