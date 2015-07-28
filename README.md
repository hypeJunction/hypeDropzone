Drag&Drop File Uploads for Elgg
===============================
![Elgg 1.8](https://img.shields.io/badge/Elgg-1.8.x-orange.svg?style=flat-square)
![Elgg 1.9](https://img.shields.io/badge/Elgg-1.9.x-orange.svg?style=flat-square)
![Elgg 1.10](https://img.shields.io/badge/Elgg-1.10.x-orange.svg?style=flat-square)
![Elgg 1.11](https://img.shields.io/badge/Elgg-1.11.x-orange.svg?style=flat-square)
![Elgg 1.12](https://img.shields.io/badge/Elgg-1.11.x-orange.svg?style=flat-square)

Drag&Drop File Uploads for Elgg

## Features

* Cross-browser support for drag&drop file uploads
* Easy to integrate into existing forms

![Dropzone](https://raw.github.com/hypeJunction/hypeDropzone/master/screenshots/dropzone_updated.png "Dropzone")

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
		'subtype' => $subtype, // subtype of the file entities to be created
		// see the view for more options
	));
```

In your action, you can retrieve uploaded files with ```get_input('upload_guids');```

You also need to implement a fallback solution for when the browser does not support
drag and drop.

You can use the hypeApps()->uploader:

```php

$upload_guids = get_input('upload_guids', array()):

$uploads = hypeApps()->uploader->handle('upload_guids', array
			'subtype' => 'file',
			'container_guid' => get_input('container_guid'),
			'access_id' => ACCESS_PRIVATE
		));

if (!empty($uploads)) {
	foreach ($uploads as $upload) {
		$entity = $upload->file;
		if ($entity instanceof \ElggEntity) {
			$upload_guids[] = $entity->guid;
		}
	}
}
```

### Initializing and resetting dropzone

You can instantiate and clear dropzone by triggering jQuery events on the containing form:

```js
$('.elgg-form').trigger('initialize'); // will instantiate dropzone inputs contained within the form
$('.elgg-form').trigger('reset'); // will clear previews and hidden guid inputs
```

## Requirements

* For icons to display as expected, install a plugin that provides FontAwesome support, or add FontAwesome to your theme


## Acknowledgements / Credits

* Dropzone.js is a really cool library by Matias Meno
http://www.dropzonejs.com/
