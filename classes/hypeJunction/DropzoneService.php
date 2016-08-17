<?php

namespace hypeJunction;

use ElggFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DropzoneService {

	/**
	 * dropzone/upload action handler
	 * @return array
	 */
	public function handleUploads() {
		
		$subtype = get_input('subtype');
		if (!$subtype) {
			$subtype = elgg_get_plugin_setting('default_upload_subtype', 'hypeDropzone', 'file');
		}

		$uploads = $this->saveUploadedFiles('dropzone', [
			'owner_guid' => elgg_get_logged_in_user_guid(),
			'container_guid' => get_input('container_guid') ? : ELGG_ENTITIES_ANY_VALUE,
			'subtype' => $subtype,
			'access_id' => ACCESS_PRIVATE,
			'origin' => get_input('origin', 'dropzone'),
		]);

		$output = array();

		foreach ($uploads as $upload) {

			$messages = array();
			$success = true;

			if ($upload->error) {
				$messages[] = $upload->error;
				$success = false;$
				$guid = false;
			} else {
				$file = $upload->file;
				$guid = $file->guid;
				$html = elgg_view('input/hidden', array(
					'name' => get_input('input_name', 'guids[]'),
					'value' => $file->guid,
				));
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

		return $output;
	}

	/**
	 * Returns an array of uploaded file objects regardless of upload status/errors
	 *
	 * @param string $input_name Form input name
	 * @return UploadedFile[]
	 */
	protected function getUploadedFiles($input_name) {
		$file_bag = _elgg_services()->request->files;
		if (!$file_bag->has($input_name)) {
			return false;
		}

		$files = $file_bag->get($input_name);
		if (!$files) {
			return [];
		}
		if (!is_array($files)) {
			$files = [$files];
		}
		return $files;
	}

	/**
	 * Save uploaded files
	 *
	 * @param string $input_name Form input name
	 * @param array  $attributes File attributes
	 * @return ElggFile[]
	 */
	protected function saveUploadedFiles($input_name, array $attributes = []) {

		$files = [];

		$uploaded_files = $this->getUploadedFiles($input_name);

		$subtype = elgg_extract('subtype', $attributes, 'file', false);
		unset($attributes['subtype']);

		$class = get_subtype_class('object', $subtype);
		if (!$class || !class_exists($class) || !is_subclass_of($class, ElggFile::class)) {
			$class = ElggFile::class;
		}

		foreach ($uploaded_files as $upload) {
			if (!$upload->isValid()) {
				$error = new \stdClass();
				$error->error = elgg_get_friendly_upload_error($upload->getError());
				$files[] = $error;
				continue;
			}

			$file = new $class();
			$file->subtype = $subtype;
			foreach ($attributes as $key => $value) {
				$file->$key = $value;
			}

			$old_filestorename = '';
			if ($file->exists()) {
				$old_filestorename = $file->getFilenameOnFilestore();
			}

			$originalfilename = $upload->getClientOriginalName();
			$file->originalfilename = $originalfilename;
			if (empty($file->title)) {
				$file->title = htmlspecialchars($file->originalfilename, ENT_QUOTES, 'UTF-8');
			}

			$file->upload_time = time();
			$prefix = $file->filestore_prefix ? : 'file';
			$prefix = trim($prefix, '/');
			$filename = elgg_strtolower("$prefix/{$file->upload_time}{$file->originalfilename}");
			$file->setFilename($filename);
			$file->filestore_prefix = $prefix;

			$hook_params = [
				'file' => $file,
				'upload' => $upload,
			];

			$uploaded = _elgg_services()->hooks->trigger('upload', 'file', $hook_params);
			if ($uploaded !== true && $uploaded !== false) {
				$filestorename = $file->getFilenameOnFilestore();
				try {
					$uploaded = $upload->move(pathinfo($filestorename, PATHINFO_DIRNAME), pathinfo($filestorename, PATHINFO_BASENAME));
				} catch (FileException $ex) {
					elgg_log($ex->getMessage(), 'ERROR');
					$uploaded = false;
				}
			}

			if (!$uploaded) {
				$error = new \stdClass();
				$error->error = elgg_echo('dropzone:file_not_entity');
				$files[] = $error;
				continue;
			}

			if ($old_filestorename && $old_filestorename != $file->getFilenameOnFilestore()) {
				// remove old file
				unlink($old_filestorename);
			}
			$mime_type = $file->detectMimeType(null, $upload->getClientMimeType());
			$file->setMimeType($mime_type);
			$file->simpletype = elgg_get_file_simple_type($mime_type);
			_elgg_services()->events->triggerAfter('upload', 'file', $file);

			if (!$file->save() || !$file->exists()) {
				$file->delete();
				$error = new \stdClass();
				$error->error = elgg_echo('dropzone:file_not_entity');
				$files[] = $error;
				continue;
			}

			if ($file->saveIconFromElggFile($file)) {
				$file->thumbnail = $file->getIcon('small')->getFilename();
				$file->smallthumb = $file->getIcon('medium')->getFilename();
				$file->largethumb = $file->getIcon('large')->getFilename();
			}

			$success = new \stdClass();
			$success->file = $file;
			$files[] = $success;
		}

		return $files;
	}

}
