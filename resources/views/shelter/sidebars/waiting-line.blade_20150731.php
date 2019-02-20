<div class="side-block waiting-list" ng-controller="WaitingLineController">
    <div class="side-block__title">
        <div class="side-block__text">Waiting line <span>[<% (clients | filter: {position: {inQueue: true} }).length %>]</span></div>
        <div class="side-block__select">
            <ui-select ng-model="dropdown.order">
                <ui-select-match placeholder="Select order"><% $select.selected.title %></ui-select-match>
                <ui-select-choices repeat="option in config['order-options']">
                    <div ng-bind-html="option.title | highlight: $select.search"></div>
                </ui-select-choices>
            </ui-select>
        </div>
    </div>

    <div class="side-block__container">


        <div class="waiting-list__lines" perfect-scrollbar>
            <a href="javascript:void(0)" class="button -answer -notification -top icons -answer" ng-show="showScrollTo(false)" ng-click="scrollToTop()">
                <span class="-icon"></span>
            </a>
            <div class="waiting-list__unit" ng-class="{
            '-active': client.state.chatOpen || client.messages.unread > 0 || !client.state.muted,
            '-chat-open': client.state.chatOpen
            }" ng-repeat="client in clients | filter: {position: {inQueue: true} } | orderBy: dropdown.order.by">
                <div class="waiting-list__name-wrapper">
                    <div class="waiting-list__name chat__name name-block">
                        <span class="name-block__called" ng-show="client.state.calledPolice"><span class="-nr">112</span></span>
                        <span class="name-block__title waiting-list__title"><% client.profile.name %></span>

                        <audio class="streams__video-element ng-hide" ng-src="<% client.stream.url | trusted %>" listen="client.state.muted" autoplay></audio>

                        <div class="popup -waiting-list">
                            <div class="popup__button icons -messages" ng-class="{
                            '-active': client.state.chatOpen,
                            '-hide': !(client.messages.unread > 0 || client.state.chatOpen)
                            }" ng-click="client.toggleChat()">
                                <span class="-icon">
                                    <span class="-count" ng-show="client.messages.unread > 0"><% client.messages.unread || 0 %></span>
                                </span>
                            </div>
                            <div class="popup__button icons -volume" ng-class="{
                            '-active': !client.state.muted,
                            '-hide': client.state.muted
                            }" ng-click="client.listen()">
                                <span class="-icon"></span>
                            </div>
                            <div class="popup__button icons -video2 -hide" ng-click="client.placeIntoView()">
                                <span class="-icon"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <a href="#" class="button icons -active" ng-show="client.state.calling" ng-class="
                    {
                        '-answer': client.state.calling && !client.state.talking,
                        '-on-hold': client.state.calling && client.state.talking
                    }" ng-click="client.talk()">
                    <span class="-icon"></span>
                    <span class="button__text" ng-show="client.state.calling && !client.state.talking">Answer</span>
                    <span class="button__text" ng-show="client.state.calling && client.state.talking">On hold</span>
                </a>

                @include('shelter/chats/chat')
            </div>
        </div>

        <a href="javascript:void(0)" class="button -answer -notification -bottom icons -answer" ng-show="showScrollTo(true)" ng-click="scrollToBottom()">
            <span class="-icon"></span>
        </a>
    </div>
</div>