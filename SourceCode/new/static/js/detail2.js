$(document).ready(function() {
	$("#comment_section").hide();
});

$("#comment_tap").click( function(){
	$("#detail_section").hide();
	$("#detail_tap").removeClass();
	$("#detail_tap").addClass("nav_unselected");
	$("#comment_section").show();
	$("#comment_tap").removeClass();
	$("#comment_tap").addClass("nav_selected");
});
		
$("#detail_tap").click( function(){
	$("#detail_section").show();
	$("#detail_tap").removeClass();
	$("#detail_tap").addClass("nav_selected");
	$("#comment_section").hide();
	$("#comment_tap").removeClass();
	$("#comment_tap").addClass("nav_unselected");	
});


