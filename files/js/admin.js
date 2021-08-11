$(function() {

	setTimeout(admin_hide_messages, 4000);


});

admin_hide_messages = function() {

	$("p.msg").each(function() {
		$(this).hide(400);
	});

};
