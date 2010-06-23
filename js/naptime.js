$(document).ready(function(){
	
	// Intelligent hiding of the notifications bar	
	var hideNotices = function()
	{
		$('.notices .notice').each(function(index){
			$(this).slideUp();
		});
		
		if ($('.notices .alert, .notices .error').length <= 0) $('.notices').slideUp();
		
		clearTimeout(window.noticeTimeout);
	};
	
	window.noticeTimeout = setTimeout(hideNotices, 3000);
	
});
