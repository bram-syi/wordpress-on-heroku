var selCss = { opacity: 1, top: -2, left: -2 };
var unselCss = { opacity: 0.5, top: 0, left: 0 };
var maskOpacity = 0.7;

function delete_media(btn) {
  $('.items').fadeOut(200);
  $.ajax({
	url: "/ajax-campaign.php?"+vars+"&d="+btn.id,
	context: document.body,
	success: function(){
	  $('#'+btn.id.replace('del-','thumb-')).remove();
	  load_full($('#sortable .item-thumb').first());
      $('#uploader-buttons').show();  
      $('#uploader-error').html('');
	  uploader.refresh();
	  
	  if($('#sortable .item-thumb').size()==0)
	    $('.no-media-msg').show();
	}
  });
  
  return false;
}

function update_media(btn) {
  $.ajax({
	url: "/ajax-campaign.php?"+vars+"&u",
	context: document.body,
	data: $('#media-updater').serialize(),
	success: function(data){
      $('#uploader-error').html('');
	  $('#media-updater-status').html('<b>Updated.</b>');
	}
  });

  return false;
}

function load_full(item) {
  $.ajax({
	url: '/ajax-campaign.php?admin&p=' + item.attr('id'),
	success: function(html) {
      if(html.toString().indexOf('Warning') >= 0) {
		  
	  } else {
		$('.items div').remove();
		$('.items').append(html);
		post_load($('.items'));
		$('.items').fadeIn(200);
		$('#uploader-error').html('');		  
	  }
	}
  });
}

function post_load(item) {
  item.css(selCss).find('.item-excerpt').hide().fadeIn(200);
  item.find('.youtube-video').each(function() {
	var player = new YT.Player($(this).attr('id'), {
	  height: $(this).height(),
	  width: $(this).width(),
	  videoId: $(this).attr('id').replace('youtube-',''),
	  autohide: 1,
	  egm: 1,
	  rel: 0,
	  showinfo: 0,
	  showsearch: 0
	});
	item.data('player', player);
  });
  item.find('.vimeo-video').each(function() {
	var player = $('<iframe>').attr('id', $(this).attr('id')).attr(
	  'src','http://player.vimeo.com/video/'+$(this).attr('id').replace('vimeo-','')
	  ).attr('width',$(this).width()).attr('height',$(this).height()).attr('frameborder',0).appendTo($(this));
	item.data('player', player);
  });
}

function check_wait() {
  var s1 = (uploader||{}).state;
  var s2 = (uploader_main||{}).state;
  if (s1 == 2 || s2 == 2) { // started
    return confirm('Do you want to wait for your upload to finish before saving?');
  } else {
    return true;  
  }

}

////////////////////////////////////////////////////////////////////////////////

var uploader, uploader_main;
var plu_runtimes = 'html5, html4';
var plu_flash = '/wp-includes/js/plupload/plupload.flash.swf';
var plu_sl = '/wp-includes/js/plupload/plupload.silverlight.xap';
var plu_url = '/ajax-campaign.php?';
var plu_max = '8mb';
var plu_chunk = '150kb';
var plu_resize = [{width : 540, height : 360, quality : 80}];
var plu_filters = [{title : "Image files", extensions : "jpg,gif,png,jpeg"}];

////////////////////////////////////////////////////////////////////////////////

function upload_start() {
  $('.no-media-msg').hide();
  //$('#sortable .dummy').fadeOut(300,function (){
  //$('#sortable .dummy').hide();
  //});
  $('#uploader-buttons').hide();  
  $('#uploader-error').html('');
  $('#sortable').append($('<div>').attr('class','item-thumb img-loading'));
  $('#sortable .item-thumb').last().attr('id','loading-thumb');	

}

function upload_finish(data) {	
  $('#links').val('');
  $('#loading-thumb').remove();

  if(data.toString().indexOf('Warning') >= 0) {

  } else if(data.toString().indexOf('Error') >= 0) {
    $('#uploader-error').html(data);
  } else if (data.toString().indexOf('<img') >= 0) {
    $('#sortable').append(data);
    load_full($('#sortable .item-thumb').last());	  
  } else if(data.toString() == 'File size error.') {
    $('#uploader-error').html('Sorry, that file is too big.');
  } else if(data.toString() != '') {
    alert(data);
    $('#uploader-error').html('Sorry, the upload failed. Please try again.');
  }
  if($('#sortable .item-thumb').size() < max_media_count) {
    $('#uploader-buttons').show();
  }
  


}

////////////////////////////////////////////////////////////////////////////////

$(function() {

////////////////////////////////////////////////////////////////////////////////

  $("#team").change(function() {
    var t = $(this);
    if (t.val() == 'other') {
      $("#other_team").fadeIn(300).attr('disabled', false).focus();
      return;
    }

    $("#other_team").attr('disabled', true);
    if (t.val() == '')
      return;

    // TODO: Update the coordinator select
  });




  post_load($('.items'));
  if($('#sortable .item-thumb').size() < max_media_count) {
    $('#uploader-buttons').show();
    if(uploader!=null)
	uploader.refresh();

  }
  var gallery = $(".gallery-widget");
  gallery.find('.item-thumb').live('click', function(ev) {
    $('.items').fadeOut(200);
    load_full($(this));
    ev.preventDefault();
  });
  gallery.find('.vertical .item').live('click', function(ev) {
    if ($(this).is('.selected')) return true;
    ev.preventDefault();
  });

////////////////////////////////////////////////////////////////////////////////

//alert('PING');
//alert(vars_main);

  if($('#uploader').length>0) {
	uploader = new plupload.Uploader({
	  runtimes : plu_runtimes,
	  browse_button : 'addfiles',
	  container : 'uploader',
	  max_file_size : plu_max,
	  unique_names: true,
//	  chunk_size: plu_chunk,
	  url: plu_url+vars,
	  flash_swf_url: plu_flash,
	  silverlight_xap_url: plu_sl,
	  resize : plu_resize,
	  filters : plu_filters
	});

    uploader.bind('Init', function(up, params) { 


	$('#filelist').html(''); });
	uploader.init();

	uploader.bind('FilesAdded', function(up, files) {
      upload_start();		
	  if(up.runtime == 'html4') { up.settings.url=up.settings.url+'&html4' }
	  $.each(files, function(i, file) {
		$('#filelist').append('<div id="' + file.id + '">Uploading ' + file.name + 
		  (uploader.runtime!='html4'?' (' + plupload.formatSize(file.size) + ') ':'') + 
		  ' <b>...</b>' + '</div>'); 
	  });
	  up.start();
	  up.refresh();		
	});

	uploader.bind('UploadProgress', function(up, file) {
      if(uploader.runtime!='html4')   
		$('#' + file.id + " b").html(file.percent + "%"); 
    });

	uploader.bind('FileUploaded', function(up, file, ret) {
      var data = ret.response;
	  if (up.runtime == "html4") {
        data = $("<div/>").html(data).text();
	  }
	  upload_finish(data);
	  $('#' + file.id + " b").html("100%"); 
	  $('#' + file.id).fadeOut(500);
	  $('#' + file.id).remove();
      up.refresh();
	});

	uploader.bind('Error', function(up, err) {
      upload_finish(err.message);
	  up.refresh();
	});

  }

////////////////////////////////////////////////////////////////////////////////

  $('#addlinks').live('click',function(e) {
    $('#linkmodal').css('display','block'); 
	$('#uploader-error').html('');
	e.preventDefault(); 
  });

  $('#closelinkmodal').live('click',function(e) {
    $('#linkmodal').css('display','none'); 
    $.ajax({
      url: "/ajax-campaign.php?"+vars,
      data : $('#links-uploader').serialize(),
      beforeSend: upload_start,
	  error: function() { upload_finish('Error'); },
	  success: upload_finish 
    });
	e.preventDefault(); 
  });

////////////////////////////////////////////////////////////////////////////////

//  $("#sortable").sortable();
 
});
