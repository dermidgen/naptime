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
	
	$(".docBody").draggable({ handle: '.docBody .dragHandle', containment: 'parent', axis: 'y' });
	
	var textarea = document.getElementById('docBody');
	var converter = new Showdown.converter;
	var preview = function() { $('.docPreview').html(converter.makeHtml(this.value)); }
	window.onkeyup = textarea.onkeyup = textarea.onblur = preview;
});

function detachEditor() {
	if ($(".docBody").hasClass('detached')) {
		$(".docBody").removeClass('detached');
		$("#docBody").removeClass('detached');
		$(".dragHandle").hide();
		$(".detachButton").html('Detach Editor');
		$(".docBody").css('top','inherit');
	}
	else {
		$(".docBody").addClass('detached');
		$("#docBody").addClass('detached');
		$(".dragHandle").show();
		$(".detachButton").html('Dock Editor')
	}
}
