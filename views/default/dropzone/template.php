<div class="elgg-dropzone-preview">
	<div class="elgg-dropzone-item-props">
		 <div class="elgg-dropzone-thumbnail">
			<img data-dz-thumbnail />
		</div>
		<div class="elgg-dropzone-filename">
			<span class="elgg-dropzone-filename-str" data-dz-name></span>
			<span class="elgg-dropzone-success-icon"><?= elgg_view_icon('check') ?></span>
			<span class="elgg-dropzone-error-icon"><?= elgg_view_icon('exclamation') ?></span>
		</div>
		<div class="elgg-dropzone-size" data-dz-size></div>
		<div class="elgg-dropzone-controls">
			<a class="elgg-dropzone-remove-icon" title="<?= elgg_echo('dropzone:remove_file') ?>" data-dz-remove><?= elgg_view_icon('trash') ?></a>
		</div>
	</div>
	<div class="elgg-dropzone-messages">
		<div data-dz-successmessage></div>
		<div data-dz-errormessage></div>
	</div>
	<div class="elgg-dropzone-progress">
		<div class="elgg-dropzone-upload" data-dz-uploadprogress></div>
	</div>
</div>