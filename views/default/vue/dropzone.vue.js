define(function (require) {

    var Vue = require('elgg/Vue');

    Vue.component('vue-dropzone', function (resolve) {
        require(['vue/vendors/Dropzone'], resolve);
    });
});