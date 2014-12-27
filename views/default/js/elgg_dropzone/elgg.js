require(['jquery', 'elgg'], function ($) {
	if ($('.elgg-dropzone').length) {
		require(['elgg_dropzone/lib'], function (dz) {
			dz.init();
		});
	}

	$(document).ajaxSuccess(function (event, response, settings) {
		if ($(data.responseText).has('.elgg-dropzone')) {
			require(['elgg_dropzone/lib'], function (dz) {
				dz.init();
			});
		}
	});
});