$(document).ready(function() {
		$('abbr').timeago();
		$('.zebra tr:odd').addClass('tblStripe');
		
		// tooltip
		$('a[title]').qtip({
			position: {
				my: 'top center',
				at: 'bottom center',
				viewport: $(window)
			},
			style: {
			  classes: 'ui-tooltip-tipsy ui-tooltip-shadow'
		    }
		});
		
		// Info value TD hover
		$('.issueInfoValue').hover(function() {
			$(this).find('span,a').css('color', '#fff');
		},
		function() {
			$(this).find('span,a').css('color', '#000');
		});
});