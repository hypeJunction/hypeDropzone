<?php if (FALSE) : ?>
	<script type="text/javascript">
<?php endif; ?>

	elgg.provide('elgg.dropzone');

	elgg.dropzone.init = function() {

		elgg.dropzone.config = {
			url: elgg.security.addToken('action/dropzone/upload'),
			method: 'POST',
			parallelUploads: 1,
			paramName: 'dropzone',
			uploadMultiple: false,
			addRemoveLinks: false,
			createImageThumbnails: true,
			thumbnailWidth: 200,
			thumbnailHeight: 200,
			maxFiles: 10,
			headers: {
				'X-Requested-With': 'XMLHttpRequest'
			}
			//forceFallback: true,
		};

		$('.elgg-input-dropzone').live('initialize', elgg.dropzone.initDropzone);

		$('.elgg-input-dropzone')
				.each(function() {
					$(this).trigger('initialize');
				});

	};


	elgg.dropzone.initDropzone = function(e) {

		var $input = $(this);

		var dropzoneData = $input.data();
		var queryData = {
			'container_guid': $input.data('containerGuid'),
			'input_name': $input.data('name')
		};

		var params = $.extend(true, {}, elgg.dropzone.config);
		$.extend(true, params, dropzoneData);

		params.success = elgg.dropzone.success;
		params.error = elgg.dropzone.error;
		params.fallback = elgg.dropzone.fallback;
		params.previewTemplate = elgg.dropzone.previewTemplate;


		var parts = elgg.parse_url(params.url),
				args = {},
				base = '';

		if (typeof parts['host'] === 'undefined') {
			if (params.url.indexOf('?') === 0) {
				base = '?';
				args = elgg.parse_str(parts['query']);
			}
		} else {
			if (typeof parts['query'] !== 'undefined') {
				args = elgg.parse_str(parts['query']);
			}
			var split = params.url.split('?');
			base = split[0] + '?';
		}

		$.extend(true, args, queryData);
		params.url = base + $.param(args);

		$input.dropzone(params);

	};

	elgg.dropzone.fallback = function() {
		$('.elgg-dropzone').hide();
		$('[id^="dropzone-fallback"]').removeClass('hidden');
	};

	elgg.dropzone.success = function(file, data) {
		var preview = file.previewElement;
		if (!data || data.status < 0) {
			$(preview).addClass('elgg-dropzone-error').removeClass('elgg-dropzone-success');
			if (data && data.output && data.output.error) {
				$(preview).find('.elgg-dropzone-error-message').html(data.output.error.join('<br />'));
			}
		} else {
			$(preview).addClass('elgg-dropzone-success').removeClass('elgg-dropzone-error');
			if (data.output.success) {
				$(preview).find('.elgg-dropzone-success-message').html(data.output.success.join('<br />'));
			}
			$(preview).append($(data.output.html));
		}
		elgg.trigger_hook('upload:success', 'dropzone', {file: file, data: data});
	};


	elgg.dropzone.error = function(file, error) {
		// check if other plugins have other plans first
		var preview = file.previewElement;
		$(preview).addClass('elgg-dropzone-error').removeClass('elgg-dropzone-success');
		$(preview).find('.elgg-dropzone-error-message').html(error);
		elgg.trigger_hook('upload:error', 'dropzone', {file: file, error: error});
	};

	elgg.dropzone.previewTemplate = '\
<div class="elgg-dropzone-preview">\n\
<div class="elgg-dropzone-size" data-dz-size></div>\n\
<div class="elgg-dropzone-thumbnail">\n\
<img data-dz-thumbnail />\n\
<div class="elgg-dropzone-success-icon"></div>\n\
<div class="elgg-dropzone-error-icon"></div>\n\
</div>\n\
<div class="elgg-dropzone-progress"><span class="elgg-dropzone-upload" data-dz-uploadprogress></span></div>\n\
<div class="elgg-dropzone-success-message"><span data-dz-successmessage></span></div>\n\
<div class="elgg-dropzone-error-message"><span data-dz-errormessage></span></div>\n\
<div class="elgg-dropzone-filename"><span data-dz-name></span></div>\n\
</div>';

	elgg.register_hook_handler('init', 'system', elgg.dropzone.init);


<?php if (FALSE) : ?></script><?php
endif;
?>
