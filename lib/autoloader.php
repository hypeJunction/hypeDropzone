<?php

if (!is_callable('hypeApps')) {
	throw new Exception("hypeDropzone requires hypeApps");
}

$path = dirname(dirname(dirname(dirname(__FILE__))));

if (!file_exists("{$path}/vendor/autoload.php")) {
	throw new Exception('hypeDropzone can not resolve composer dependencies. Run composer install');
}

require_once "{$path}/vendor/autoload.php";

/**
 * Plugin container
 * @return \hypeJunction\Dropzone\Plugin
 */
function hypeDropzone() {
	return \hypeJunction\Dropzone\Plugin::factory();
}