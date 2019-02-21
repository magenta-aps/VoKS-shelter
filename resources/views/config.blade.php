<script type="text/javascript">
    config = {};
    config['id'] = '{!! $config['peer-id'] !!}';
    config['num-id'] = '{!! $config['id'] !!}';
    config['mode'] = {!! $config['mode'] ? 'true' : 'false' !!};
    config['lang'] = {!! $config['translations'] !!};
    config['locale'] = '{!! $config['locale'] !!}';
    config['stream-block-limit'] = {!! $config['stream-block-limit'] !!};
    config['ice-servers'] = { iceServers: [{url: 'stun:stun.l.google.com:19302'}] };
    config['media-constrains'] = { optional: [ {internalSctpDataChannels: true} ] };
    config['order-options'] = {!! $config['order-options'] !!};
    config['websocket-server'] = '{{ $config['websocket-server'] }}';
    config['police'] = {!! $config['police'] !!};
    config['push-notification-limit'] = {!! $config['push-notification-limit'] !!};
    config['aruba-coords-enabled'] = {{ $config['aruba-coords-enabled'] ? 'true' : 'false' }};
    config['google-maps-enabled'] = {{ $config['google-maps-enabled'] ? 'true' : 'false' }};
    config['google-zoom-level'] = {!! $config['google-zoom-level'] !!};
    config['cisco-enabled'] = {{ $config['cisco-enabled'] ? 'true' : 'false' }};
    config['use-gps'] = {{ $config['use-gps'] ? 'true' : 'false' }};
    config['use-non-gps'] = {{ $config['use-non-gps'] ? 'true' : 'false' }};

    lang = function(key) {
        return config['lang'][key];
    }
</script>