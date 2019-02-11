@extends('system/base')

@section('content')
    <script type="text/javascript">
        var floor = {!! $floor !!},
            buttons = {!! $buttons !!};
    </script>
    <div class="container__wrapper -help" ng-controller="MapPreviewController">
        <div class="settings text help-block">
            <div class="help-block__description">
                <h2 class="-title">{{ Lang::get('system.contents.maps.title') }}</h2>
                {{--<p class="-text">{{ Lang::get('system.contents.maps.description') }}</p>--}}
            </div>

            <div class="help-block__container">
                <div class="custom">
                    <div id="map" class="map-preview"></div>
                </div>
            </div>
        </div>
    </div>
@endsection