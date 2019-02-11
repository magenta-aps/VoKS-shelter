<div class="streams__item">
    <div class="streams__stream" ng-show="client" ng-class="{
    '-message-stream': client.state.chatOpen,
    '-current': client.state.inLargeView
    }">
        @include('shelter/streams/small-video')
        <div class="streams__chat">
            @include('shelter/chats/chat')
        </div>
        @include('shelter/streams/stream-buttons')
    </div>
    @include('shelter/streams/empty-stream')
</div>