var dis = $(".cant-edit").find(".text-field, .check-field");
dis.addClass('disabled').find("input, textarea").attr("disabled", true);
//$("#edButtonPreview").click();

function clear_selections() {
  $('#donations').find(':checked').each(function() {
	 $(this).removeAttr('checked');
  });
  return false;
}


$(function() {
  //switchEditors.go('r_Body','tinymce');
  $("select.select-go").change(function() {
    $(this).closest('form').submit();
  }).siblings('.select-go[type=submit]').hide(); 
  $(".recipient.selected").live('click', function() {
    return false;
  });

  var last = '';
  var fnChange = $.debounce? $.debounce(500, function onChange(ev) {
    var now = $(this).val();
    if (now == last)
      return true;
    last = now;
    refresh_thumbs();
  }) : function() { };
  $("#search").bind("change keyup", fnChange).bind("keypress", function(ev) {
    if (ev.keyCode != 13) return true;
    //refresh_thumbs();
    return false;
  });
  
  $gifts = $(".r_Gifts input");
  if ($gifts.length <= 1) {
    $("select[name=gift]").hide(); // Hide the gift dropdown
    $(".new_recipient .r_Gifts input").attr('checked',true);
  }

  var thumbs_url = window.location + "";
  var ajax_req = null;
  function refresh_thumbs(url) {
    $(".thumbs .recipient:not(.new)").css('opacity',0.5);
    if (ajax_req != null)
      ajax_req.abort();
    ajax_req = $.ajax({
      url: url || thumbs_url,
      data: { update: 'recipients',
        search: $("#search").val() },
      success: function(data) {
        $(".thumbs").replaceWith(data);
        thumbs_url = url;
      }
    });
  }

  $(".thumbs a.arrow").live('click', function() {
    refresh_thumbs($(this).attr('href'));
    return false;
  });

  var photo = $('.r_Photo');

  if(window.plupload && (photo.length > 0)) {
    var loading = photo.find('.loading');
    var thePhoto = photo.find('.thumb-photo');

    var r_Photo = new plupload.Uploader({
      runtimes : 'html5,html4',
      browse_button : 'r_Photo',
      container : 'r_Photo_c',
      drop_element : 'photo-area',
      max_file_size : '5mb',
      //unique_names: true,
      //chunk_size: '5mb',
      url: $.param.querystring(window.location + "", {update: 'photo'}),
      flash_swf_url: '/wp-includes/js/plupload/plupload.flash.swf',
      silverlight_xap_url: '/wp-includes/js/plupload/plupload.silverlight.xap',
      resize : {width : 1024, height : 1024, quality : 95},
      filters : [
        {title : "Image files", extensions : "jpg,jpeg,gif,png"}
      ]
    });

    r_Photo.bind('Init', function(up, params){

      if (($.browser.msie && $.browser.version<8) || (!$.browser.msie && up.runtime == "html4"))//
        return;

      photo.find("input[type=file]").hide();
      $("#r_Photo").show();

      try {
      if(!!FileReader && !((up.runtime == "flash") || (up.runtime == "silverlight")))
        photo.addClass('draggable');
      }
      catch(err){}
    });
    r_Photo.init();
    r_Photo.bind('FilesAdded', function(up, files) {
//alert(up.runtime);
      if(up.runtime == 'html4') { up.settings.url=up.settings.url+'&html4' }
      loading.show();
      thePhoto.css('opacity', 0.3);
      $("input[type=submit]").addClass('hold');
      photo.find('.button label').html('Uploading...');

      up.start();
      up.refresh();
    });
    r_Photo.bind('UploadProgress', function(up, file) {
      var prog = up.total.percent;
      $(".photo-holder .progress").css('width', prog + '%');
    });
    r_Photo.bind('FileUploaded', function(up, file, ret) {
      $(".photo-holder .progress").css('width', 0);
      loading.hide();
      thePhoto.css('opacity', 1);
      $(".hold").removeClass("hold");

      var data = $.parseJSON(ret.response.replace("\n"," "));

      if (!data || (data.error != null)) {
		photo.find('.button label').html('<span style="color:red;">Upload failed!</span>');
        return;
      }

      photo.find('.button label').html('Upload a photo');
      photo.removeClass('no-photo').addClass('yes-photo');
      photo.find('input[name=r_ThumbnailID]').val(data.ID);
      photo.find('.instructions').remove();
//alert(data.html);
	  if (up.runtime == "html4") {
        data.html = $("<div/>").html(data.html).text();
	  }

      thePhoto.html(data.html);
      //refresh_thumbs();

      up.refresh();
    });
    r_Photo.bind('Error', function(up, err) {
      $(".photo-holder .progress").css('width', 0);
      loading.hide();
      thePhoto.css('opacity', 1);
      $(".hold").removeClass("hold");

      photo.find('.button label').html('<span style="color:red;">Upload failed</span>');
      up.refresh();
    });
  }

  $("#r_Gifts .quantity").click(function(ev) {
    if (!ev.altKey)
      return true;

    $(this).siblings('.other-gift').toggleClass('hidden');
    return false;
  });

  $("#r_Gifts .another-gift").live('click', function() {
    $("#r_Gifts .check-field").fadeIn();
    $(this).hide();
  });

  $(".hold").live('click', function() {
    return false;
  });

  $(".delete-link").live('click', function() {
    return (confirm("Delete this recipient: are you sure?") == true);
  });
  $("#cancel-link").live('click', function() {
    if (!confirm("Cancel your edits: are you sure?"))
      return false;
    $(this).closest('form').removeClass('changed');
    return true;
  });

  $("input:not(.ui), textarea").live('change', function() {
    var tm = 0; // No animation for now
    $("body form.can-edit").addClass('changed').find('#preview-link').hide(tm).end().find('#cancel-link').show(tm);
  });
  $("#story-form").submit(function() {
    $(this).removeClass("changed");

    $('.page').block({ message: 'Saving...', css: { padding: 10, top: 250 }, centerY: false, overlayCSS: {  background: '#666', opacity: 0.2 } });
  });

  if ($.browser.msie) {
      $('#r_Photo.button label').onselectstart = function() { return(false); };
      window.onbeforeunload = function() {
        if ($("form.changed").length == 0) {
          return;
        }
        return "You made some changes to the story.  Do you want to leave this page without saving?";
      }
  } else {
    $(window).bind('beforeunload', function() {
      if ($("form.changed").length == 0) {
        return;
      }
      return "You made some changes to the story.  Do you want to leave this page without saving?";
    });
  }

  function update_dear() {
    var d = $(".r_Dear");
    if (d.find(":checkbox").is(":checked"))
      d.find(".label").removeClass('strike');
    else
      d.find(".label").addClass('strike');
  }
  update_dear();
  $(".r_Dear :checkbox").live('change', update_dear);

  // Simple usage for mp3 links
  $('a.audio-notes').playable();
});
