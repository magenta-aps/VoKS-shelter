<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Shelter Admin</title>

    <!--<link href='//fonts.googleapis.com/css?family=Open+Sans:700,400&subset=latin,latin-ext' rel='stylesheet' type='text/css'/>-->
    <link href="/vendor/select2/select2.css" rel="stylesheet"/>
    <link href="/vendor/angular-xeditable/dist/css/xeditable.css" rel="stylesheet"/>
    <link href="/vendor/angular-ui-select/dist/select.min.css" rel="stylesheet"/>
    <link href="/css/app.css" rel="stylesheet"/>
    <link href="/vendor/leaflet/dist/leaflet.css" rel="stylesheet"/>
</head>

<body ng-app="admin">
<div class="toasts"></div>
<main class="wrapper">
    @include('partials.header')
    <section class="container">
        <div class="container__wrapper -help">
            @yield('content')
        </div>
    </section>
</main>
<script src="/vendor/angular/angular.min.js"></script>
<script src="/vendor/ng-file-upload/angular-file-upload.min.js"></script>
<script src="/vendor/leaflet/dist/leaflet.js"></script>
<script src="/vendor/jquery/dist/jquery.min.js"></script>
<script src="/vendor/angular-scroll-glue/src/scrollglue.js"></script>
<script src="/vendor/angular-ui-select/dist/select.min.js"></script>
<script src="/vendor/angular-xeditable/dist/js/xeditable.min.js"></script>

<script src="/frontend/scripts/select2.full.js"></script>
<script src="/js/helpers/functions.js"></script>
<script src="/js/helpers/toasts.js"></script>

<script src="/js/admin/app.js"></script>
<script src="/js/admin/ui.js"></script>
<script src="/js/admin/services/Shelter.js"></script>
<script src="/js/admin/controllers/HeaderController.js"></script>
<script src="/js/admin/controllers/ButtonController.js"></script>
<script src="/js/admin/controllers/PushNotificationController.js"></script>
<script src="/js/admin/controllers/LanguageController.js"></script>
<script src="/js/admin/controllers/SchoolsController.js"></script>
<script src="/js/admin/controllers/ApiController.js"></script>
<script src="/js/admin/controllers/SmsController.js"></script>
<script src="/js/admin/controllers/AdminHelpController.js"></script>
<script src="/js/admin/controllers/GeneralController.js"></script>
@yield('scripts')

</body>
</html>
