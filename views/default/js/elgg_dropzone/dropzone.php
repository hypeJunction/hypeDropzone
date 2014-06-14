<?php if (FALSE) : ?>
	<script type="text/javascript">
<?php endif; ?>

	elgg.provide('elgg.dropzone');

	/**
	 * Initialize dropzone on DOM ready
	 * @returns {void}
	 */
	elgg.dropzone.init = function() {

		// Binding a custom event, so that it's easier to initialize dropzone on ajax success
		$('.elgg-input-dropzone').live('initialize', elgg.dropzone.initDropzone);

		$('.elgg-input-dropzone').each(function() {
			$(this).trigger('initialize');
		});

	};

	/**
	 * Configuration parameters of the dropzone instance
	 * @param {String} hook
	 * @param {String} type
	 * @param {Object} params
	 * @param {Object} config
	 * @returns {Object}
	 */
	elgg.dropzone.config = function(hook, type, params, config) {

		var defaults = {
			url: elgg.security.addToken(elgg.get_site_url() + 'action/dropzone/upload'),
			method: 'POST',
			headers: {
				'X-Requested-With': 'XMLHttpRequest'
			},
			parallelUploads: 10,
			paramName: 'dropzone',
			uploadMultiple: true,
			createImageThumbnails: true,
			thumbnailWidth: 200,
			thumbnailHeight: 200,
			maxFiles: 10,
			addRemoveLinks: false,
			dictRemoveFile: "&times;",
			previewTemplate: params.dropzone.closest('.elgg-dropzone').find('[data-template]').html(),
			fallback: elgg.dropzone.fallback,
			//autoProcessQueue: false,
			init: function() {
				if (this.options.uploadMultiple) {
					this.on('successmultiple', elgg.dropzone.success);
				} else {
					this.on('success', elgg.dropzone.success);
				}
				this.on('removedfile', elgg.dropzone.removedfile);
			}
			//forceFallback: true
		};

		return $.extend(true, defaults, config);
	};

	/**
	 * Callback function for 'initialize' event
	 * @param {Object} e
	 * @returns {void}
	 */
	elgg.dropzone.initDropzone = function(e) {

		var $input = $(this);

		var params = elgg.trigger_hook('config', 'dropzone', {dropzone: $input}, $input.data());

		// These will be sent as a URL query and will be available in the action
		var queryData = {
			container_guid: $input.data('containerGuid'),
			input_name: $input.data('name'),
			subtype: $input.data('subtype')
		};

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
		$input.live('addedfile', function(e) {
			alert('hello');
		});
	};

	/**
	 * Display regular file input in case drag&drop is not supported
	 * @returns {void}
	 */
	elgg.dropzone.fallback = function() {
		$('.elgg-dropzone').hide();
		$('[id^="dropzone-fallback"]').removeClass('hidden');
	};

	/**
	 * Files have been successfully uploaded
	 * @param {Array} files
	 * @param {Object} data
	 * @returns {void}
	 */
	elgg.dropzone.success = function(files, data) {

		if (!$.isArray(files)) {
			files = [files];
		}
		$.each(files, function(index, file) {
			var preview = file.previewElement;
			if (data && data.output) {
				var filedata = data.output[index];
				if (filedata.success) {
					$(preview).addClass('elgg-dropzone-success').removeClass('elgg-dropzone-error');
				} else {
					$(preview).addClass('elgg-dropzone-error').removeClass('elgg-dropzone-success');
				}
				if (filedata.html) {
					$(preview).append($(filedata.html));
				}
				if (filedata.guid) {
					$(preview).attr('data-guid', filedata.guid);
				}
				if (filedata.messages.length) {
					if (data.output && data.output.success) {
						$(preview).find('.elgg-dropzone-messages').html(data.output.messages.join('<br />'));
					}
				}
			} else {
				$(preview).addClass('elgg-dropzone-error').removeClass('elgg-dropzone-success');
				$(preview).find('.elgg-dropzone-messages').html(elgg.echo('dropzone:server_side_error'));
			}
			elgg.trigger_hook('upload:success', 'dropzone', {file: file, data: data});
		});
	};

	/**
	 * Delete file entities if upload has completed
	 * @param {Object} file
	 * @returns {void}
	 */
	elgg.dropzone.removedfile = function(file) {

		var preview = file.previewElement;
		var guid = $(preview).data('guid');

		if (guid) {
			elgg.action('action/file/delete', {
				data: {
					guid : guid
				}
			});
		}
	};

	elgg.register_hook_handler('init', 'system', elgg.dropzone.init);
	elgg.register_hook_handler('config', 'dropzone', elgg.dropzone.config);


<?php if (FALSE) : ?></script><?php
endif;
?>
