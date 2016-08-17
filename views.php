<?php

$plugin_root = __DIR__;
$root = dirname(dirname($plugin_root));
$alt_root = dirname(dirname(dirname($root)));

if (file_exists("$plugin_root/vendor/autoload.php")) {
	$path = $plugin_root;
} else if (file_exists("$root/vendor/autoload.php")) {
	$path = $root;
} else {
	$path = $alt_root;
}

return [
	'default' => [
		'dropzone/lib.js' => $path . '/vendor/bower-asset/dropzone/dist/min/dropzone-amd-module.min.js',
		'css/dropzone/stylesheet' => __DIR__ . '/views/default/dropzone/dropzone.css',
	],
];
