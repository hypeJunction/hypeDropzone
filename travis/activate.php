<?php

require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/start.php');

$admin = get_user_by_username('admin');
login($admin);

$plugin = elgg_get_plugin_from_id('elgg_dropzone');
$plugin->activate();

