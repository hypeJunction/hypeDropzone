# `<elgg-input-dropzone>`

## Props

 * `name` - name of the hidden input that will contain guids of uploaded files
 * `id` - input ID
 * `url` - alternative upload action/URL
 * `label` - field label
 * `help` - field help text
 * `required` - is input required
 * `accept` - comma-separated list of access file types
 * `max` - max number of files
 * `subtype` - file entity subtype to be create on upload
 * `container-guid` - GUID of the entity to contain the file
  
## Usage

```html
<elgg-input-dropzone
    <!-- v-model will be updated with ElggFile object instances -->
    v-model="files"
    id="id"
    name="name"
    :label="echo('label')"
    :help="echo('help')"
    :placeholder="echo('placeholder')"
    required
    <!-- triggered whenever file is successfully uploaded or removed -->
    @change="onChangeMethod"
></elgg-input-dropzone>
```

