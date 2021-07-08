jQuery(document).ready(function($) {
	$('a[data-rel^=lightcase]').lightcase({
		showTitle: true,
		transition: 'scrollHorizontal',
		showTitle:true,
		showCaption: true
	});
	 jQuery('a[title]').tooltip({placement: "auto"});
});
