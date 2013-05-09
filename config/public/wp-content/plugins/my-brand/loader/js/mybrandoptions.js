$(document).ready(function(){
	$(".krs").hover(function(){
	$(this).stop().fadeTo("slow", 0.6);
		},function(){
	$(this).stop().fadeTo("slow", 1.0);
		});
	});
$(document).ready(function(){
   setTimeout(function(){
  $(".updated").fadeOut("slow", function () {
  $(".updated").remove();
      });

}, 5000);
 $('#mybrand').fadeIn("slow", 0.0);
	$('#mybrand').fadeIn("slow", 0.1);
	 $('#mybrand').fadeIn("slow", 0.2);
	 $('#mybrand').fadeIn("slow", 0.3);
	  $('#mybrand').fadeIn("slow", 0.4);
	  $('#mybrand').fadeIn("slow", 0.5);
	  $('#mybrand').fadeIn("slow", 0.6);
	   $('#mybrand').fadeIn("slow", 0.7);
	   $('#mybrand').fadeIn("slow", 0.8);
	   $('#mybrand').fadeIn("slow", 0.9);
	   $('#mybrand').fadeIn("slow", 1.0);
 });
