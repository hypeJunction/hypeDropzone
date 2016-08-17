Drag&Drop File Uploads for Elgg
===============================
![Elgg 2.2](https://img.shields.io/badge/Elgg-2.2.x-orange.svg?style=flat-square)

Drag&Drop File Uploads for Elgg

## Features

* Cross-browser support for drag&drop file uploads
* Easy to integrate into existing forms

![Dropzone](https://raw.github.com/hypeJunction/hypeDropzone/master/screenshots/dropzone.png "Dropzone")

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
drag and drop. Check `hypeJunction\DropzoneService` for an example.

### Initializing and resetting dropzone

You can instantiate and clear dropzone by triggering jQuery events on the containing form:

```js
$('.elgg-form').trigger('initialize'); // will instantiate dropzone inputs contained within the form
$('.elgg-form').trigger('reset'); // will clear previews and hidden guid inputs
```

## Acknowledgements / Credits

* Dropzone.js is a really cool library by Matias Meno
http://www.dropzonejs.com/
