jQuery(function($) {
	$("div.Calendar div.body a.eventAction").livequery('click', function() {
		// split the link into it's date and link sections
		var parts = this.href.split('?');

		// Find the containing div
		var node = this;
		do {
			node = node.parentNode;
		} while(!($(node).is('div.Calendar')));
		var url = $(node).children('div.url')[0].innerHTML;

		// Construct the url
		url += '?' + parts[1];

		// Replace by ajax
		$(node).load(url);

		return false;
	});
});
