$(document).ready(function () {
	$('ul.nav-tabs li').click(function() {
		var $this = $(this);
		var content = $this.data('content');
		
		$('ul.nav-tabs li').removeClass('active');
		$this.addClass('active');
		
		var $divs = $('ul.nav-tabs').next('div.tab-content').find('div.tab-pane'); 
		$divs.removeClass('active').removeClass('in');
		$divs.hide();
		
		var $div = $('div#' + content);
		$div.addClass('active').addClass('in');
		$div.show();
	});
});