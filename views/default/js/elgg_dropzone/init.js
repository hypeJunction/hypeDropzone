define(function() {
	
	var $ = require('jquery');

	if ($('.elgg-dropzone').length) {
		require(['elgg_dropzone/lib'], function(dz) {
			dz.init();
		});
	}

	$(document).ajaxSuccess(function(data) {
		if ($(data).has('.elgg-dropzone')) {
			require(['elgg_dropzone/lib'], function(dz) {
				dz.init();
			});
		}
	});
});