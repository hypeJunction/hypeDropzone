<?php

namespace hypeJunction\Dropzone;

/**
 * @property-read \ElggPlugin                               $plugin
 * @property-read \hypeJunction\Dropzone\Config             $config
 */
final class Plugin extends \hypeJunction\Plugin {

	static $instance;

	protected function __construct(\ElggPlugin $plugin) {

		$this->setValue('plugin', $plugin);

		$this->setFactory('config', function (Plugin $p) {
			return new \hypeJunction\Dropzone\Config($p->plugin);
		});
	}

	public static function factory() {
		if (null === self::$instance) {
			$plugin = elgg_get_plugin_from_id('hypeDropzone');
			self::$instance = new self($plugin);
		}
		return self::$instance;
	}

	public function boot() {
		elgg_register_event_handler('init', 'system', array($this, 'init'));
	}

	public function init() {

		hypeApps()->actions->register('dropzone/upload', \hypeJunction\Dropzone\Actions\uploadAction::CLASSNAME);
		
		/**
		 * JS, CSS and Views
		 */
		elgg_extend_view('css/elgg', 'css/dropzone/stylesheet');

		if (\hypeJunction\Integration::isElggVersionAbove('1.9.0')) {
			elgg_define_js('dropzone/lib', array(
				'src' => '/mod/hypeDropzone/vendors/dropzone/dropzone-amd-module.min.js',
				'deps' => array('jquery'),
				'exports' => 'dropzone',
			));

		} else {
			elgg_register_js('dropzone.min.js', '/mod/hypeDropzone/vendors/dropzone/dropzone.min.js', 'footer');

			elgg_register_simplecache_view('js/dropzone/legacy/lib');
			elgg_register_js('dropzone', elgg_get_simplecache_url('js', 'dropzone/legacy/dropzone'));
		}
	}
}
