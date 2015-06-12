<?php

namespace hypeJunction\Dropzone\Actions;

/**
 * @property string        $subtype
 * @property string        $input_name
 * @property int           $container_guid
 * @property \ElggEntity[] $uploads
 */
class uploadAction extends \hypeJunction\Controllers\Action {

	const CLASSNAME = __CLASS__;

	public function setup() {
		parent::setup();
		if (empty($this->subtype)) {
			$this->subtype = hypeDropzone()->config->get('default_upload_subtype');
		}
	}

	public function validate() {
		return true;
	}

	public function execute() {

		$this->uploads = hypeApps()->uploader->handle('dropzone', array(
			'subtype' => $this->subtype,
			'container_guid' => $this->container_guid,
			'access_id' => ACCESS_PRIVATE
		));

		$this->output = array();

		if (!elgg_is_xhr()) {
			return;
		}

		foreach ($this->uploads as $upload) {

			$messages = array();
			$success = true;

			if ($upload->error) {
				$messages[] = $upload->error;
				$success = false;
				$guid = false;
			} else {
				$file = $upload->file;
				if (!$file instanceof \ElggEntity) {
					$messages[] = elgg_echo('dropzone:file_not_entity');
					$success = false;
				} else {
					$guid = $file->getGUID();
					$html = elgg_view('input/hidden', array(
						'name' => $this->input_name,
						'value' => $file->getGUID()
					));
				}
			}

			$file_output = array(
				'messages' => $messages,
				'success' => $success,
				'guid' => $guid,
				'html' => $html,
			);

			$output[] = elgg_trigger_plugin_hook('upload:after', 'dropzone', array(
				'upload' => $upload,
					), $file_output);
		}

		$this->result->output = json_encode($output);
	}

}
