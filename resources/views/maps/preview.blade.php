<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Map Preview</title>

    <link href="{{ elixir('css/system.css') }}" rel="stylesheet" />
    <link href="{{ elixir('css/app.css') }}" rel="stylesheet" />
    <link href="{{ elixir('css/override.css') }}" rel="stylesheet" />
</head>
<body ng-app="system">

    <div class="toasts"></div>

    <main class="wrapper">
        <section class="container">
            <div class="container__wrapper -help">
                <div class="settings text help-block">
                    <div class="help-block__description">
                        <h2 class="-title">Maps</h2>
                        <!--<p class="-text">Maps</p>-->
                    </div>

                    <div class="help-block__container">
                        <div class="custom">
                            <div id="map" class="map-preview"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script type="text/javascript">
        var floor = {!! $floor !!},
            buttons = {!! $buttons !!};
    </script>

    <script src="{{ elixir('js/preview/preview.js') }}"></script>
    <script src="{{ elixir('js/preview/app.js') }}"></script>

</body>
</html>
