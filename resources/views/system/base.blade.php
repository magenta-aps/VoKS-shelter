<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>System Admin</title>

    <link href="{{ elixir('css/system.css') }}" rel="stylesheet" />
    <link href="{{ elixir('css/app.css') }}" rel="stylesheet" />
    <link href="{{ elixir('css/override.css') }}" rel="stylesheet" />
</head>
<body ng-app="system" class="ng-cloak">

    <div class="toasts">
    </div>

    <main class="wrapper">
        @include('system.header')
        <section class="container">
            @yield('content')
        </section>
    </main>

    <script type="text/javascript">
        config = {};
        config['lang'] = {!! $config['translations'] !!};
        config['locale'] = '{!! $config['locale'] !!}';

        lang = function(key) {
            return config['lang'][key];
        };

        @if(Session::get('status'))
            var initialToast = '{{ Session::get('status') }}';
        @endif
    </script>

    <script src="{{ elixir('js/system/system.js') }}"></script>
    <script src="/vendor/tinymce/tinymce.min.js"></script>
    <script src="{{ elixir('js/system/app.js') }}"></script>
</body>
</html>
