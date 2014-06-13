<?php

/**
 * Dropzone
 *
 * @package Elgg
 * @subpackage Dropzone
 *
 * @author Ismayil Khayredinov <ismayil.khayredinov@gmail.com>
 */

namespace Elgg\Dropzone;

const PLUGIN_ID = 'elgg_dropzone';

require_once __DIR__ . '/lib/functions.php';
require_once __DIR__ . '/lib/hooks.php';

elgg_register_event_handler('init', 'system', __NAMESPACE__ . '\\init');

function init() {

	/**
	 * JS, CSS and Views
	 */
	elgg_register_js('dropzone.js', '/mod/' . PLUGIN_ID . '/vendors/dropzone-3.10.2/dropzone.min.js', 'footer');

	elgg_register_simplecache_view('js/elgg_dropzone/dropzone');
	elgg_register_js('elgg.dropzone.js', elgg_get_simplecache_url('js', 'elgg_dropzone/dropzone'), 'footer');

	elgg_register_simplecache_view('css/elgg_dropzone/dropzone');
	elgg_register_css('elgg.dropzone.css', elgg_get_simplecache_url('css', 'elgg_dropzone/css'));

	/**
	 * Register actions
	 */
	$actions_path = __DIR__ . '/actions/';
	elgg_register_action('dropzone/upload', $actions_path . 'dropzone/upload.php');

	// Making things pretty
	elgg_extend_view('page/elements/head', 'framework/fonts/font-awesome');
	elgg_extend_view('page/elements/head', 'framework/fonts/open-sans');
	
}
