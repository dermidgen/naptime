$(document).ready(function(){
	
	// Intelligent hiding of the notifications bar	
	var hideNotices = function()
	{
		console.info('clearing notices');
		$('.notices .notice').each(function(index){
			$(this).slideUp();
		});
		
		if ($('.notices .alert, .notices .error').length <= 0) $('.notices').slideUp();
	};
	
	setTimeout(hideNotices, 3000);
	
});
