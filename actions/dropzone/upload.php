<?php

use hypeJunction\DropzoneService;

$svc = new DropzoneService();
$result = $svc->handleUploads();

if (elgg_is_xhr()) {
	echo json_encode($result);
}
