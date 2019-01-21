<div class="streams__item" ng-mouseenter="hover=true" ng-mouseleave="hover=false" ng-init="hover=false">
    <div class="streams__stream" ng-if="client" ng-class="{
    '-message-stream': client.state.chatOpen,
    '-current': client.position.inLargeView
    }">
        <div class="streams__video">
            <div class="chat__with">{{ Lang::get('app.stream.chat_with') }}</div>
            <div class="name-block">
                <span class="name-block__called" ng-show="client.state.calledPolice"><span class="-nr">{{ Lang::get('app.stream.police_called') }}</span></span>
                <span class="name-block__title"><% client.profile.name %></span>
            </div>
            <div class="streams__video-box">
                <div class="streams__video-tag-block">
                    <video id="streams_video_element-<% client.profile.id %>"
                           class="streams__video-element" 
                           ng-src="<% client.stream.url | trusted %>" 
                           listen="client.state.muted" 
                           autoplay></video>
                </div>

                <span class="-time">
                    <span ng-show="client.state.battery !== 0 && (client.profile.device === 'ios' || client.profile.device === 'android')" >
                        {{ Lang::get('app.stream.mobile.battery') }} <% client.state.battery %><br /><br />
                    </span>

                    <span ng-show="client.state.battery == 0 && (client.profile.device === 'ios' || client.profile.device === 'android')">
                        {{ Lang::get('app.stream.mobile.battery_loading') }}<br /><br />
                    </span>

                    <span time-ago="client.timestamps.connected"></span>
                </span>

                <div ng-if="!client.position.inLargeView" class="icons -minimize-maximize" ng-click="StreamCtrl.maximize(client.profile.id)">
                    <span class="-icon"></span>
                </div>
                <div ng-if="client.position.inLargeView" class="icons -minimize-maximize" ng-click="StreamCtrl.minimize(client.profile.id)">
                    <span class="-icon"></span>
                </div>
            </div>
            <div class="streams__video-overlay"></div>
        </div>
        <div class="streams__chat">
            <div class="chat" ng-class="{'-active': client.state.chatOpen}">
                <div class="chat__wrapper" scroll-glue perfect-scrollbar>
                    <div class="chat__conversation">
                        <div class="chat__board">
                            <div class="chat__item" ng-class="{
                            '-user -triangle-tr': message.fromShelter,
                            '-main -triangle-tl': !message.fromShelter
                            }" ng-repeat="message in client.messages.list | orderBy: 'timestamp'">
                                <p class="chat__message"><% message.message %></p>
                                <div class="chat__time"><% message.timestamp | date:"h:mma" %></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="chat__input-block">
                    <form ng-submit="content = client.sendChatMessage(content)" novalidate>
                        <input type="text" class="chat__input-field" placeholder="{{ Lang::get('app.stream.placeholder') }}" ng-model="content" ng-focus="client.markRead()" ng-blur="client.blur()" focus-if="client.state.chatOpen" required />
                    </form>
                </div>
            </div>
        </div>
        <div class="icons -close-stream" ng-click="client.hideFromView()">
            <span class="-text" ng-show="!client.state.chatOpen">{{ Lang::get('app.stream.minimize') }}</span>
            <span class="-text" ng-show="client.state.chatOpen">{{ Lang::get('app.stream.close_chat') }}</span>
            <span class="-icon"></span>
        </div>
        <div class="streams__buttons">
            <div class="streams__button icons -location" ng-class="{'-active': StreamCtrl.isActive(client.profile.id)}" ng-click="StreamCtrl.setActive(client.profile.id);">
                <span class="-icon"></span>
            </div>
            <div class="streams__button icons -volume" ng-class="{'-active': !client.state.muted}" ng-click="client.listen()">
                <span class="-icon"></span>
            </div>
            <div class="streams__button icons -messages" ng-class="{'-active': client.state.chatOpen}" ng-click="client.toggleChat()">
                <span class="-icon"><span class='-count' ng-show="client.messages.unread > 0"><% client.messages.unread %></span></span>
                <span class="-text">{{ Lang::get('app.stream.button.messages') }}</span>
            </div>
            <div class="streams__button icons -answer" ng-class="{
                        '-active': client.state.calling,
                        '-inactive': !client.state.calling,
                        '-on-hold': client.state.calling && client.state.talking
                        }" ng-click="client.talk()">
                <span class="-icon"></span>
                <span class="-text" ng-show="!client.state.calling || !client.state.talking">{{ Lang::get('app.stream.button.answer') }}</span>
                <span class="-text" ng-show="(client.state.calling && client.state.talking)">{{ Lang::get('app.stream.button.hold') }}</span>
            </div>
        </div>
    </div>
    <div class="streams__empty" ng-if="!client">
        <div class="empty-block">
            <div class="empty-block__content">
                <div class="empty-block__title">{{ Lang::get('app.stream.empty.title') }}</div>
                <div class="empty-block__text">{{ Lang::get('app.stream.empty.select') }}</div>
                <ui-select ng-if="hover" ng-model="selected.client" on-select="$item.placeIntoView(position); selected.client = null;">
                    <ui-select-match placeholder="{{ Lang::get('app.stream.empty.placeholder') }}"><% $select.selected.profile.name %></ui-select-match>
                    <ui-select-choices repeat="item in ::clients | filter: {profile: {name: $select.search} } | filter: {position: {inQueue: true} }">
                        <div ng-bind-html="item.profile.name | highlight: $select.search"></div>
                    </ui-select-choices>
                </ui-select>
                <div ng-if="!hover" class="ui-select-container select2 select2-container">
                    <a class="select2-choice ui-select-match select2-default">
                        <span class="select2-chosen ng-binding">{{ Lang::get('app.stream.empty.placeholder') }}</span>
                        <span class="select2-arrow ui-select-toggle"><b></b></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
