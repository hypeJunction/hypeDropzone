define(function (require) {

    var elgg = require('elgg');
    var Ajax = require('elgg/Ajax');
    var Vue = require('elgg/Vue');

    /* @todo Rewrite to not require jQuery */
    var $ = require('jquery');

    var template = require('text!elgg/input/dropzone.vue.html');
    var previewTemplate = require('text!dropzone/template.html');

    Vue.component('elgg-input-dropzone', {
        template: template,
        model: {
            prop: 'value',
            event: 'change'
        },
        props: {
            value: {
                type: Array,
                default: function () {
                    return [];
                }
            },
            name: {
                type: String
            },
            required: {
                type: Boolean,
                default: false
            },
            id: {
                type: String,
                default: function () {
                    return 'elgg-field-vue' + this._uid;
                }
            },
            url: {
                type: String,
            },
            accept: {
                type: String,
            },
            max: {
                type: Number
            },
            label: {
                type: String
            },
            help: {
                type: String
            },
            subtype: {
                type: String
            },
            containerGuid: {
                type: Number,
            }
        },
        data: function () {
            return {
                inputValue: this.value
            }
        },
        computed: {
            dropzoneProps: function () {
                return {
                    id: this.id,
                    paramName: 'dropzone',
                    url: this.buildUploadUrl(),
                    uploadMultiple: true,
                    maxNumberOfFiles: this.max,
                    parallelUploads: 10,
                    autoProcessQueue: true,
                    showRemoveLink: false,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    previewTemplate: function () {
                        return previewTemplate;
                    },
                    language: {
                        'dictDefaultMessage': this.echo('dropzone:default_message'),
                        'dictFallbackMessage': this.echo('dropzone:fallback_message'),
                        'dictFallbackText': this.echo('dropzone:fallback_text'),
                        'dictInvalidFiletype': this.echo('dropzone:invalid_filetype'),
                        'dictFileToobig': this.echo('dropzone:file_too_big'),
                        'dictResponseError': this.echo('dropzone:response_error'),
                        'dictCancelUpload': this.echo('dropzone:cancel_upload'),
                        'dictCancelUploadConfirmation': this.echo('dropzone:cancel_upload_confirmation'),
                        'dictRemoveFile': this.echo('dropzone:remove_file'),
                        'dictMaxFilesExceeded': this.echo('dropzone:max_files_exceeded'),
                    }
                };
            },
            fieldClass: function () {
                var selectors = [];
                if (this.required) {
                    selectors.push('elgg-field-required');
                    selectors.push('is-required');
                }
                return selectors;
            },
        },
        methods: {
            buildUploadUrl: function () {
                var url = this.url || 'action/dropzone/upload';

                var queryData = {
                    container_guid: this.containerGuid,
                    input_name: this.name,
                    subtype: this.subtype
                };

                var parts = elgg.parse_url(url),
                    args = {}, base = '';
                if (typeof parts['host'] === 'undefined') {
                    if (url.indexOf('?') === 0) {
                        base = '?';
                        args = elgg.parse_str(parts['query']);
                    }
                } else {
                    if (typeof parts['query'] !== 'undefined') {
                        args = elgg.parse_str(parts['query']);
                    }
                    var split = url.split('?');
                    base = split[0] + '?';
                }

                $.extend(true, args, queryData);
                url = base + $.param(args);

                return elgg.security.addToken(elgg.normalize_url(url));
            },
            onSuccess: function (files, data) {
                var self = this;

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

                        if (filedata.guid) {
                            $(preview).attr('data-guid', filedata.guid);
                            self.$store.dispatch('getEntity', {
                                guid: filedata.guid
                            }).then(function (entity) {
                                self.inputValue.push(entity);
                                self.$emit('change', self.inputValue);
                            });
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
            onRemovedFile: function (file, error, xhr) {
                var self = this;
                var preview = file.previewElement;
                var guid = $(preview).data('guid');

                if (guid) {
                    var ajax = new Ajax(false);

                    elgg.action('action/file/delete', {
                        data: {
                            guid: guid
                        }
                    }).then(function() {
                        var index = self.inputValue.findIndex(function(e) {
                            return e.guid === guid;
                        });

                        if (index >= 0) {
                            Vue.delete(self.inputValue, index);
                        }

                        self.$emit('change', self.inputValue);
                    });
                }
            },

        },
        watch: {
            value: function (value) {
                this.inputValue = value;
            }
        }
    });

});
