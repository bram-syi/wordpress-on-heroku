var popup;
var completed = false;
var timeout;


function open_inline_login_popup(input_service) {
  var user = $('#inline-login-username').val();
  var pass = $('#inline-login-password').val();
  if(user!='' && pass!='')
	return open_popup(input_service, false, user, pass);
  else 
	return false;
}

$(function() {

  $('#invite-thanks a').live('click',function(){
    $('#invite-thanks').hide();
    $('#invite-import-container').show();
    $('#invite-message-container').show();
  });
  $('#invite').live('click',function(e) {
//	alert('TEST');  
	e.preventDefault();
    $('#msg').html('');
    $('#msg').attr('class','');
    if($('#invites').val() == '') {
      $('#invites').focus();
      $('#msg').attr('class','error');
      $('#msg').html('Please specify one or more e-mail addresses to invite.');
      return false;
    }

    if($('#invite-sender').val() == '') {
      $('#invite-sender').focus();
	  $('#msg').attr('class','error');
      $('#msg').html('Please enter your name.');
      return false;
    }

    $.ajax({
	  type: "POST",
      url: window.currentInvite,
      data : $('#invite-form').serialize(),
	  timeout: 50000,
      beforeSend: function() {
        $('#inline-login').hide();
        $('#invite-form').hide();
        $('#msg').attr('class','');
        $('#msg').addClass('img-loading');
        $('#msg').html('<br/><br/><br/><div style="text-align:center; font-size:18px; margin:20px;">Processing</div>');
      },
	    error: function(xhr,ajaxOptions,err) {
        $('#invite-form').show();
        $('#msg').attr('class','error');
        $('#msg').html('Sorry, an error occurred and we were unable to send your invitations.');
      },
	    success: function(data) {
        $('#invite-form').show();
        if(data.toString().indexOf('Error') >= 0) { //error
          $('#msg').attr('class','error');
          $('#msg').html(data.toString());
        } else { //success
          $('#invites').val('');
          $('#msg').attr('class','');
          $('#msg').html('');
          $('#invite-form h1').html('Thanks!');
          $('#invite-thanks').show();
          $('#invite-actions').hide();
          $('#invite-import-container').hide();
          $('#invite-message-container').hide();
          $.fn.colorbox.resize();
        }
      }
    });
  });

  $('#importer-cancel').live('click',function(){ reset_importer(); });
  $('#importer-addlist').live('click',function(){
    $('.imported-invite').each(function(){
      if($(this).attr('checked')) {
        if($('#invites').val().search($(this).attr('id')) == -1) {
          $('#invites').val($('#invites').val().trim()+
            ($('#invites').val()==""?"":"\n")+$(this).val());
        }
      }
    });
    reset_importer();
  });

  $('#importer-selectall').live('click',function(){
    $('.imported-invite').each(function(){ $(this).attr('checked',true); });
  });
  $('#importer-unselectall').live('click',function(){
    $('.imported-invite').each(function(){ $(this).attr('checked',false); });
  });

});

function reset_importer() {
  clearTimeout(timeout);
  popup.close();
  $('#inline-login, #importer-container').hide();
  $('#invite-import-container, #invite-actions').show();
  $('#importer').html('');
  $('#msg').html('');
  $('#invite').show();
  $.fn.colorbox.resize();
}

function start_importer() {
  $('#inline-login').hide();
  $('#importer-container').show();
  $('#invite-import-container, #invite-actions').hide();
  $('#importer').addClass('img-loading');
  $('#importer').html('<br/><br/><div style="text-align:center; font-size:18px;">Importing Contacts</div>');
  $('#msg').html('');
  $('#invite').hide();
  $.fn.colorbox.resize();
}

function open_popup(service) {
  return cloudsponge.launch(service);
}
