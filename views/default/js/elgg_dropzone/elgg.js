require(['jquery', 'elgg'], function ($) {
	if ($('.elgg-dropzone').length) {
		require(['elgg_dropzone/lib'], function (dz) {
			dz.init();
		});
	}

	$(document).ajaxSuccess(function (event, response, settings) {
		var data = '';
		if (settings.dataType === 'json') {
			data = $.parseJSON(response.responseText);
		} else if (settings.dataType === 'html') {
			data = response.resopnseText;
		}
		if ($(data).has('.elgg-dropzone')) {
			require(['elgg_dropzone/lib'], function (dz) {
				dz.init();
			});
		}
	});
});