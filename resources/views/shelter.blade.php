<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">

    <title>Shelter</title>

    <link href="{{ elixir('css/shelter.css') }}" rel="stylesheet" />
    <link href="{{ elixir('css/app.css') }}" rel="stylesheet" />
    <link href="{{ elixir('css/override.css') }}" rel="stylesheet" />
</head>

<body ng-app="app" ng-cloak ng-strict-di>

    <div class="toasts"></div>
    <div class="tourbootstrap-overlay"></div>

    <main class="wrapper" ng-controller="MainController as MainCtrl">
        @include('shelter/header')
        <section class="container">
            @include('shelter/sidebars/message-feed')
            <div class="container__wrapper" recalculate ng-view ng-class="{'-help': isHelp()}"></div>
            @include('shelter/sidebars/waiting-line')
        </section>
    </main>

    @include('config')

    @if ( config('google.maps.enabled') )
        <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ config('google.maps.key') }}" type="text/javascript"></script>
    @endif

    <script src="{{ elixir('js/shelter.js') }}"></script>
    <script src="{{ elixir('js/app.js') }}"></script>
</body>
</html>
