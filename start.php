<?php
/**
 * Dropzone
 *
 * @author Ismayil Khayredinov <ismayil.khayredinov@gmail.com>
 */

elgg_register_event_handler('init', 'system', 'elgg_dropzone_init');

/**
 * Initialize the plugin on system init
 * @return void
 */
function elgg_dropzone_init() {

	/**
	 * JS, CSS and Views
	 */
	elgg_define_js('dropzone', array(
		'src' => '/mod/elgg_dropzone/vendors/dropzone/dropzone-amd-module.min.js',
		'deps' => array('jquery'),
		'exports' => 'dropzone',
	));

	elgg_extend_view('js/elgg', 'js/elgg_dropzone/elgg.js');
	elgg_extend_view('css/elgg', 'css/elgg_dropzone/stylesheet.css');

	/**
	 * Register actions
	 */
	$actions_path = __DIR__ . '/actions/';
	elgg_register_action('dropzone/upload', $actions_path . 'dropzone/upload.php');
}
