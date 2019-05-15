<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Shelter Admin</title>

    <link href="{{ elixir('css/admin.css') }}" rel="stylesheet" />
    <link href="{{ elixir('css/app.css') }}" rel="stylesheet" />
    <link href="{{ elixir('css/override.css') }}" rel="stylesheet" />
</head>
<body ng-app="admin" class="ng-cloak">

    <div class="toasts"></div>

    <main class="wrapper">
        @include('shelter.header')
        <section class="container">
            <div class="container__wrapper -help">
                @yield('content')
            </div>
        </section>
    </main>

    <script type="text/javascript">
        config = {};
        config['id'] = '{!! $config['peer-id'] !!}';
        config['num-id'] = '{!! $config['id'] !!}';
        config['lang'] = {!! $config['translations'] !!};
        config['locale'] = '{!! $config['locale'] !!}';
        config['stream-block-limit'] = {!! $config['stream-block-limit'] !!};
        config['police'] = {!! $config['police'] !!};
        config['video-do-recording'] = {!! $config['video-do-recording'] ? 'true' : 'false' !!};
        config['video-endpoint-start'] = '{!! $config['video-base-url'] !!}' + '{!! $config['video-endpoint-start'] !!}';
        config['video-endpoint-stop'] = '{!! $config['video-base-url'] !!}' + '{!! $config['video-endpoint-stop'] !!}';
	config['video-base-url'] = '{!! $config['video-base-url'] !!}';

        lang = function(key) {
            return config['lang'][key];
        };

        @if(Session::get('status'))
        var initialToast = '{{ Session::get('status') }}';
        @endif
    </script>

    <script src="{{ elixir('js/admin/admin.js') }}"></script>
    <script src="/vendor/tinymce/tinymce.min.js"></script>
    <script src="{{ elixir('js/admin/app.js') }}"></script>
</body>
</html>
