<?php

namespace Elgg\Dropzone;

use hypeJunction\Filestore\UploadHandler;

$uploads = UploadHandler::handle('dropzone', array(
			'subtype' => 'file',
			'container_guid' => get_input('container_guid'),
			'access_id' => ACCESS_PRIVATE
		));

if (elgg_is_xhr()) {
	$errors = $success = array();
	
	$name = get_input('input_name');
	foreach ($uploads as $upload) {
		if ($upload->error) {
			register_error($upload->error);
			$errors[] = $upload->error;
			continue;
		} else {
			$success[] = elgg_echo('upload:success');
		}
		
		$file = $upload->file;
		if (!elgg_instanceof($file)) {
			continue;
		}
		$guids[] = $file->getGUID();
		$html .= elgg_view('input/hidden', array(
			'name' => $name,
			'value' => $file->getGUID()
		));
	}

	echo json_encode(array(
		'guids' => $guids,
		'html' => $html,
		'error' => $errors,
		'success' => $success
	));
}

forward(REFERER);
