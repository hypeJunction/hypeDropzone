Drag&Drop File Uploads for Elgg
===============================

Drag&Drop File Uploads for Elgg

[Buy me a vinyl!](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=P7QA9CFMENBKA)


## Features

* Cross-browser support for drag&drop file uploads
* Fallback to a normal file input
* Easy to integrate into existing plugins

## Acknowledgements / Credits

* Dropzone.js is a really cool library by Matias Meno
http://www.dropzonejs.com/

* As always, best in kind FontAwesome
http://fontawesome.io/


## Developer Notes

* To add a drag&drop input to your form, add the following:

```php
echo elgg_view('input/dropzone', array(
		'name' => 'upload_guids',
		'accept' => "image/*",
		'max' => 25,
		'multiple' => true,
	));
```

See the view and the JS file for more options.

In your action, you can retrieve uploaded files with ```get_input('upload_guids');```

You also need to implement a fallback solution for when the browser does not support
drag and drop.

You can use the UploadHandler class included with this plugin:

```php

namespace hypeJunction\Filestore;

$upload_guids = get_input('upload_guids', array()):

$handler = new UploadHandler;
$files = $handler->makeFiles('upload_guids', array
			'subtype' => 'file',
			'container_guid' => get_input('container_guid'),
			'access_id' => ACCESS_PRIVATE
		));

foreach ($files as $file) {
	$upload_guids[] = $file->guid;
}

```

## Screenshots

![alt text](https://raw.github.com/hypeJunction/elgg_dropzone/master/screenshots/dropzone.png "Dropzone")