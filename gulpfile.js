// Disable notifier
process.env.DISABLE_NOTIFIER = true;

// Elixir and other requirements
var elixir = require('laravel-elixir');

// Public directories
var publicDir = 'public/',
    publicCssDir = publicDir + 'css/',
    publicJsDir = publicDir + 'js/',
    publicVendorDir = publicDir + 'vendor/';

// Assets directories
var assetsDir = 'resources/assets/',
    assetsCssDir = assetsDir + 'css/',
    assetsJsDir = assetsDir + 'js/',
    assetsVendorDir = assetsDir + 'vendor/';

// CSS - Shelter
var cssSrcShelterVendor = assetsVendorDir,
    cssDstShelterVendor = publicCssDir + 'shelter.css',
    cssListShelterVendor = [
        'select2/select2.css',
        'angular-ui-select/dist/select.css',
        'leaflet/dist/leaflet.css',
        'perfect-scrollbar/src/perfect-scrollbar.css',
        'bootstrap-tour/build/css/bootstrap-tour-standalone.css'
    ];

// CSS - Admin
var cssSrcAdminVendor = assetsVendorDir,
    cssDstAdminVendor = publicCssDir + 'admin.css',
    cssListAdminVendor = [
        'select2/select2.css',
        'angular-ui-select/dist/select.css',
        'angular-xeditable/dist/css/xeditable.css',
        'leaflet/dist/leaflet.css'
    ];

// CSS - System
var cssSrcSystemVendor = assetsVendorDir,
    cssDstSystemVendor = publicCssDir + 'system.css',
    cssListSystemVendor = [
        'select2/select2.css',
        'angular-ui-select/dist/select.css',
        'angular-xeditable/dist/css/xeditable.css',
        'leaflet/dist/leaflet.css'
    ];

// JS - Shelter
var jsSrcShelterVendor = assetsVendorDir,
    jsDstShelterVendor = publicJsDir + 'shelter.js',
    jsListShelterVendor = [
        'jquery/jquery.js',
        'angular/angular.js',
        'angular-perfect-scrollbar/src/angular-perfect-scrollbar.js',
        'angular-route/angular-route.js',
        'angular-scroll-glue/src/scrollglue.js',
        'angular-translate/angular-translate.js',
        'angular-ui-select/dist/select.js',
        'angular-websocket/angular-websocket.js',

        'leaflet/dist/leaflet.js',
        // leaflet-select

        'perfect-scrollbar/src/perfect-scrollbar.js',
        'bootstrap-tour/build/js/bootstrap-tour-standalone.js',
        'sprintf/src/sprintf.js'
    ];

var jsSrcShelterApp = assetsJsDir,
    jsDstShelterApp = publicJsDir + 'app.js',
    jsListShelterApp = [
        assetsJsDir + 'helpers/leaflet.select.js',
        assetsJsDir + 'helpers/adapter.js',
        assetsJsDir + 'helpers/toast.js',
        'shelter/**/*.js'
    ];

// JS - Admin
var jsSrcAdminVendor = assetsVendorDir,
    jsDstAdminVendor = publicJsDir + 'admin/admin.js',
    jsListAdminVendor = [
        'jquery/jquery.js',
        'angular/angular.js',
        'angular-translate/angular-translate.js',
        'angular-ui-select/dist/select.js',
        'angular-ui-tinymce/src/tinymce.js',
        'angular-xeditable/dist/js/xeditable.js',

        'leaflet/dist/leaflet.js',

        'select2/select2.js'
    ];

var jsSrcAdminApp = assetsJsDir,
    jsDstAdminApp = publicJsDir + 'admin/app.js',
    jsListAdminApp = [
        assetsJsDir + 'helpers/angular-xeditable.tinymce.js',
        assetsJsDir + 'helpers/toast.js',
        'admin/**/*.js'
    ];

// JS - System
var jsSrcSystemVendor = assetsVendorDir,
    jsDstSystemVendor = publicJsDir + 'system/system.js',
    jsListSystemVendor = [
        'jquery/jquery.js',
        'angular/angular.js',
        'angular-translate/angular-translate.js',
        'angular-ui-select/dist/select.js',
        'angular-ui-tinymce/src/tinymce.js',
        'angular-xeditable/dist/js/xeditable.js',

        'leaflet/dist/leaflet.js',

        'select2/select2.js'
    ];

var jsSrcSystemApp = assetsJsDir,
    jsDstSystemApp = publicJsDir + 'system/app.js',
    jsListSystemApp = [
        assetsJsDir + 'helpers/angular-xeditable.tinymce.js',
        assetsJsDir + 'helpers/toast.js',
        'system/**/*.js'
    ];

// JS - Preview
var jsSrcPreviewVendor = assetsVendorDir,
    jsDstPreviewVendor = publicJsDir + 'preview/preview.js',
    jsListPreviewVendor = [
        'leaflet/dist/leaflet.js'
    ];

var jsSrcPreviewApp = assetsJsDir,
    jsDstPreviewApp = publicJsDir + 'preview/app.js',
    jsListPreviewApp = [
        'preview/**/*.js'
    ];

// TinyMCE
var copySrcTinyMCE = assetsVendorDir + 'tinymce/',
    copyDstTinyMCE = publicVendorDir + 'tinymce/';

// Initialize
elixir(function(mix) {
    mix
        // Copy Select2 images
        .copy(assetsVendorDir + 'select2/select2.png', publicDir + 'build/css/select2.png')
        .copy(assetsVendorDir + 'select2/select2-spinner.gif', publicDir + 'build/css/select2-spinner.gif')
        .copy(assetsVendorDir + 'select2/select2x2.png', publicDir + 'build/css/select2x2.png')

        // Copy TinyMCE
        .copy(copySrcTinyMCE + 'tinymce.min.js', copyDstTinyMCE)
        .copy(copySrcTinyMCE + 'themes/**/*.min.js', copyDstTinyMCE + 'themes')
        .copy(copySrcTinyMCE + 'skins/**/*', copyDstTinyMCE + 'skins')

        // CSS
        .sass('app.scss')
        .copy(assetsCssDir + 'override.css', publicCssDir + 'override.css')

        // Shelter
        .styles(cssListShelterVendor, cssDstShelterVendor, cssSrcShelterVendor)
        .scripts(jsListShelterVendor, jsDstShelterVendor, jsSrcShelterVendor)
        .scripts(jsListShelterApp, jsDstShelterApp, jsSrcShelterApp)

        // Admin
        .styles(cssListAdminVendor, cssDstAdminVendor, cssSrcAdminVendor)
        .scripts(jsListAdminVendor, jsDstAdminVendor, jsSrcAdminVendor)
        .scripts(jsListAdminApp, jsDstAdminApp, jsSrcAdminApp)

        // System
        .styles(cssListSystemVendor, cssDstSystemVendor, cssSrcSystemVendor)
        .scripts(jsListSystemVendor, jsDstSystemVendor, jsSrcSystemVendor)
        .scripts(jsListSystemApp, jsDstSystemApp, jsSrcSystemApp)

        // Preview
        .scripts(jsListPreviewVendor, jsDstPreviewVendor, jsSrcPreviewVendor)
        .scripts(jsListPreviewApp, jsDstPreviewApp, jsSrcPreviewApp)

        // File versioning (all *Dst* files)
        .version([
            // CSS
            cssDstShelterVendor,
            cssDstAdminVendor,
            cssDstSystemVendor,
            publicCssDir + 'app.css',
            publicCssDir + 'override.css',

            // JS
            jsDstShelterVendor,
            jsDstShelterApp,
            jsDstShelterApp,
            jsDstAdminVendor,
            jsDstAdminApp,
            jsDstSystemVendor,
            jsDstSystemApp,
            jsDstPreviewVendor,
            jsDstPreviewApp
        ]);
});
