<?php

namespace hypeJunction\Dropzone;

class Config extends \hypeJunction\Config {

	/**
	 * {@inheritdoc}
	 */
	public function getDefaults() {
		return array(
			'default_upload_subtype' => 'file',
		);
	}
}
