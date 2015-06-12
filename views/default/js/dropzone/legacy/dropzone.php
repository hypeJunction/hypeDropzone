<?php if (FALSE) : ?>
	<script type="text/javascript">
<?php endif; ?>
	
	elgg.provide('elgg.dropzone');

	elgg.dropzone = {
		/**
		 * Initialize dropzone on DOM ready
		 * @returns {void}
		 */
		init: function () {

			var init = 'initialize.dropzone init.dropzone ready.dropzone';
			var reset = 'reset.dropzone clear.dropzone';

			$('.elgg-input-dropzone').die('.dropzone');

			$('.elgg-input-dropzone').live(init, elgg.dropzone.initDropzone);
			$('.elgg-input-dropzone').live(reset, elgg.dropzone.resetDropzone);

			$('form:has(.elgg-input-dropzone)').live(init, elgg.dropzone.initDropzoneForm);
			$('form:has(.elgg-input-dropzone)').live(reset, elgg.dropzone.resetDropzoneForm);

			$('.elgg-input-dropzone').trigger('initialize');
		},
		/**
		 * Configuration parameters of the dropzone instance
		 * @param {String} hook
		 * @param {String} type
		 * @param {Object} params
		 * @param {Object} config
		 * @returns {Object}
		 */
		config: function (hook, type, params, config) {

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
				previewTemplate: params.dropzone.closest('.elgg-dropzone').find('[data-template]').children()[0].outerHTML,
				fallback: elgg.dropzone.fallback,
				//autoProcessQueue: false,
				init: function () {
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
		},
		/**
		 * Callback function for 'initialize', 'init', 'ready' event
		 * @param {Object} e
		 * @returns {void}
		 */
		initDropzone: function (e) {

			var $input = $(this);

			if ($input.data('elgg-dropzone')) {
				return;
			}

			var params = elgg.trigger_hook('config', 'dropzone', {dropzone: $input}, $input.data());

			//These will be sent as a URL query and will be available in the action
			var queryData = {
				container_guid: $input.data('containerGuid'),
				input_name: $input.data('name'),
				subtype: $input.data('subtype')
			};

			var parts = elgg.parse_url(params.url),
					args = {}, base = '';
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
			$input.data('elgg-dropzone', true);
		},
		/**
		 * Callback function for 'reset' event
		 * @param {Object} e
		 * @returns {void}
		 */
		resetDropzone: function (e) {
			$(this).find('.elgg-dropzone-preview').remove();
		},
		/**
		 * Callback to initialize dropzone on form 'initialize' and 'ready' events
		 * @param {Object} e
		 * @returns {void}
		 */
		initDropzoneForm: function (e) {
			if (!$(e.target).is('.elgg-input-dropzone')) {
				$(this).find('.elgg-input-dropzone').trigger('initialize');
			}
		},
		/**
		 * Callback to reset dropzone on form 'reset' and 'clear' events
		 * @param {Object} e
		 * @returns {void}
		 */
		resetDropzoneForm: function (e) {
			if (!$(e.target).is('.elgg-input-dropzone')) {
				$(this).find('.elgg-input-dropzone').trigger('reset');
			}
		},
		/**
		 * Display regular file input in case drag&drop is not supported
		 * @returns {void}
		 */
		fallback: function () {
			$('.elgg-dropzone').hide();
			$('[id^="dropzone-fallback"]').removeClass('hidden');
		},
		/**
		 * Files have been successfully uploaded
		 * @param {Array} files
		 * @param {Object} data
		 * @returns {void}
		 */
		success: function (files, data) {

			if (!$.isArray(files)) {
				files = [files];
			}
			$.each(files, function (index, file) {
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
		},
		/**
		 * Delete file entities if upload has completed
		 * @param {Object} file
		 * @returns {void}
		 */
		removedfile: function (file) {

			var preview = file.previewElement;
			var guid = $(preview).data('guid');

			if (guid) {
				elgg.action('action/file/delete', {
					data: {
						guid: guid
					}
				});
			}
		}
	};

	elgg.register_hook_handler('init', 'system', elgg.dropzone.init);
	elgg.register_hook_handler('config', 'dropzone', elgg.dropzone.config);
