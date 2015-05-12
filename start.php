<?php

/**
 * Drag&Drop File Uploads
 *
 * @author Ismayil Khayredinov <ismayil.khayredinov@gmail.com>
 * @copyright Copyright (c) 2015, Ismayil Khayredinov
 */
try {
	require_once __DIR__ . '/lib/autoloader.php';
	hypeDropzone()->boot();
} catch (Exception $ex) {
	elgg_log($ex->getMessage(), 'ERROR');
	if (elgg_is_admin_logged_in()) {
		register_error($ex->getMessage());
	}
	hypeDropzone()->deactivate();
}

