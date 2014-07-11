<?php

namespace Elgg\Dropzone;

use hypeJunction\Filestore\UploadHandler;

$subtype = get_input('subtype');
if (empty($subtype)) {
	$subtype = 'file';
}

$uploads = UploadHandler::handle('dropzone', array(
			'subtype' => $subtype,
			'container_guid' => get_input('container_guid'),
			'access_id' => ACCESS_PRIVATE
		));

$output = array();

if (elgg_is_xhr()) {
	$name = get_input('input_name');
	foreach ($uploads as $upload) {

		$messages = array();
		$success = true;

		if ($upload->error) {
			$messages[] = $upload->error;
			$success = false;
			$guid = false;
		} else {
			$file = $upload->file;
			if (!elgg_instanceof($file)) {
				$messages[] = elgg_echo('dropzone:file_not_entity');
				$success = false;
			} else {
				$guid = $file->getGUID();
				$html = elgg_view('input/hidden', array(
					'name' => $name,
					'value' => $file->getGUID()
				));
			}
		}

		$file_output = array(
			'messages' => $messages,
			'success' => $success,
			'guid' => $guid,
			'html' => $html,
		);

		$output[] = elgg_trigger_plugin_hook('upload:after', 'dropzone', array(
			'upload' => $upload,
				), $file_output);
	}

	echo json_encode($output);
}

forward(REFERER);
