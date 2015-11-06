<div class="block-style -send-messages-block tabs">
    <div class="container__header">
        <span class="-title">Send messages</span>
    </div>

    <div class="block-style__tabs">
        <div class="tabs__nav">
            <a href="#" id="message-push" class="tabs__item" ng-class="{'-active': currentMessageTab === 'push'}" ng-click="currentMessageTab = 'push'">Push</a>
            <a href="#" id="message-audio" class="tabs__item" ng-class="{'-active': currentMessageTab === 'audio'}" ng-click="currentMessageTab = 'audio'">Audio</a>
            <a href="#" id="message-history" class="tabs__item" ng-class="{'-active': currentMessageTab === 'history'}" ng-click="currentMessageTab = 'history'">History</a>
        </div>
    </div>

    <div class="block-style__content">
        <div ng-show="currentMessageTab === 'push'">
            @include('shelter/school-plan/message-tabs/push')
        </div>
        <div ng-show="currentMessageTab === 'audio'">
            @include('shelter/school-plan/message-tabs/audio')
        </div>
        <div ng-show="currentMessageTab === 'history'">
            @include('shelter/school-plan/message-tabs/history')
        </div>
    </div>
</div>