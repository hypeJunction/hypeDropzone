Drag&Drop File Uploads for Elgg
===============================

Drag&Drop File Uploads for Elgg

## Features

* Cross-browser support for drag&drop file uploads
* Fallback to a normal file input
* Easy to integrate into existing plugins

![alt text](https://raw.github.com/hypeJunction/elgg_dropzone/master/screenshots/dropzone_updated.png "Dropzone")

## Versioning

* Master branch is compatible with Elgg 1.9
* Versions 3.0+ are for Elgg 1.9; anything under is for previous versions of Elgg

## Developer Notes

### Adding a drag&drop file input and processing uploads

To add a drag&drop input to your form, add the following:

```php
echo elgg_view('input/dropzone', array(
		'name' => 'upload_guids',
		'accept' => "image/*",
		'max' => 25,
		'multiple' => true,
		'container_guid' => $container_guid, // optional file container
		'subtype' => $subtype, // subtype of the files to be created
		// see the view for more options
	));
```

In your action, you can retrieve uploaded files with ```get_input('upload_guids');```

You also need to implement a fallback solution for when the browser does not support
drag and drop.

You can use the UploadHandler class included with hypeFilestore:

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

### Initializing and resetting dropzone

You can instantiate and clear dropzone by triggering jQuery events on the containing form:

```js
$('.elgg-form').trigger('initialize'); // will instantiate dropzone inputs contained within the form
$('.elgg-form').trigger('reset'); // will clear previews and hidden guid inputs
```

## Requirements

* hypeFilestore 3.0+ https://github.com/hypeJunction/hypeFilestore
* For icons to display as expected, install a plugin that provides FontAwesome support, or add FontAwesome to your theme


## Acknowledgements / Credits

* Dropzone.js is a really cool library by Matias Meno
http://www.dropzonejs.com/