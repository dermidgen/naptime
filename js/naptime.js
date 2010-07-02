$(document).ready(function(){
	
	var docEditor = $(".docEditor");
	var docBody = $(".docBody");
	var docText = $("#docBody");
	var docPreview = $(".docPreview");

	var bounds = {
			top:null,
			bottom:null,
			span:null
		};
	
	// Intelligent hiding of the notifications bar	
	var hideNotices = function()
	{
		$('.notices .notice').each(function(index){
			$(this).slideUp();
		});
		
		if ($('.notices .alert, .notices .error').length <= 0) $('.notices').slideUp();
		
		clearTimeout(window.noticeTimeout);
	};
	
	var dragStart = function()
	{
		if (bounds.top == null) {
			bounds.top = docBody.position().top;
			bounds.span = docPreview.innerHeight(); 
			bounds.bottom = bounds.top + bounds.span;
		}
	};
	
	var drag = function()
	{
		var cPos = docBody.position().top;
		var cScroll = docText.scrollTop();
		
		var cPerc = (((cPos - bounds.top)/bounds.span));
		var tScroll = Math.floor(cPerc * document.getElementById('docBody').scrollHeight);
		
		docText.scrollTop(tScroll);

		//console.info(tScroll);	
		//console.info(cPerc);
	};
	
	window.noticeTimeout = setTimeout(hideNotices, 3000);
	
	$(".docBody").draggable({ handle: '.docBody .dragHandle', containment: 'parent', axis: 'y', drag: drag, start: dragStart });
	
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
