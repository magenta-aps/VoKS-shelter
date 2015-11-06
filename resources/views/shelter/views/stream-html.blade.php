<div id="stream-view" class="streams">
    <div class="streams__large">
        <div class="streams__main" style="height: 500px;">
            <div class="-large-video" stream clients="clients" client="clients | filter:inLargeView() | limitTo: 1 | findFirst">
                <div class="streams__unit">
                    <div class="streams__item">
                        <div class="streams__stream">
                            <div class="streams__video">
                                <div class="chat__with">{{ Lang::get('app.stream.chat_with') }}</div>
                                <div class="name-block">
                                    <span class="name-block__called"><span class="-nr" ng-show="client.state.calledPolice">{{ Lang::get('app.stream.police_called') }}</span></span>
                                    <span class="name-block__title"><% client.profile.name %></span>
                                </div>
                                <div class="streams__video-box">
                                    <div class="streams__video-tag-block">
                                        <video class="streams__video-element" active="client.position.inLargeView" src="<% client.stream.url | trusted %>" listen="client.state.muted" autoplay></video>
                                    </div>
                                    <span class="-time" time-ago="client.timestamps.connected"></span>

                                    <div class="button -minimizeMaximize" ng-click="StreamCtrl.minimize(client.profile.id)">
                                        <div class="icons -minimize-maximize">
                                            <span class="-icon"></span>
                                        </div>
                                        <span class="button__text">{{ Lang::get('app.stream.button.minimize') }}</span>
                                    </div>
                                </div>
                                <div class="streams__video-overlay"></div>
                            </div>
                            <div class="icons -close-stream" ng-click="client.state.chatOpen = false; client.hideFromView();">
                                <span class="-text">{{ Lang::get('app.stream.minimize') }}</span>
                                <span class="-icon"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="-info-block">
                <div class="-name-block" stream clients="clients" client="clients | filter:inLargeView() | limitTo: 1 | findFirst">
                    <div class="name-block">
                        <span class="name-block__title"><% client.profile.name %></span>
                            <span class="name-block__called" ng-show="client.state.calledPolice">
                                <span class="-called">{{ Lang::get('app.police.switch.called') }}</span>
                                <span class="-nr">{{ Lang::get('app.stream.police_called') }}</span>
                            </span>
                    </div>
                    <a class="button icons" ng-class="{
                            '-active': client.state.calling,
                            '-inactive': !client.state.calling,
                            '-answer': !client.state.calling || !client.state.talking,
                            '-on-hold': client.state.calling && client.state.talking
                            }" ng-click="client.talk()">
                        <span class="-icon"></span>
                        <span class="button__text" ng-show="!client.state.calling || !client.state.talking">{{ Lang::get('app.stream.button.answer') }}</span>
                        <span class="button__text" ng-show="(client.state.calling && client.state.talking)">{{ Lang::get('app.stream.button.hold') }}</span>
                    </a>

                    <a href="javascript:void(0);" class="button icons -volume -mute" ng-show="!client.state.muted" ng-click="client.listen()">
                        <span class="-icon"></span>
                        <span class="button__text">{{ Lang::get('app.stream.button.mute') }}</span>
                    </a>
                    <a href="javascript:void(0);" class="button icons -volume -unmute" ng-show="client.state.muted" ng-click="client.listen()">
                        <span class="-icon"></span>
                        <span class="button__text">{{ Lang::get('app.stream.button.unmute') }}</span>
                    </a>
                </div>
                <div class="-inner-block">
                    <div class="-chat-wrapper" stream clients="clients" client="clients | filter:inLargeView() | limitTo: 1 | findFirst">
                        <div class="container__header"><span class="-title">{{ Lang::get('app.stream.conversation') }}</span></div>
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
                                    <input type="text" class="chat__input-field" placeholder="{{ Lang::get('app.stream.placeholder') }}" ng-model="content" ng-focus="client.markRead()" required />
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="-map-wrapper">
                        <div class="map">
                            <div class="map bullets">
                                <map id="stream"></map>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="streams__small">
            <div class="streams__small">
                <div class="-small-video-container">
                    <div class="-small-video">
                        <div class="streams__unit" stream position="1" clients="clients" client="clients | filter:position(1) | limitTo: 1 | findFirst">
                            <app-stream></app-stream>
                        </div>
                    </div>
                </div>
                <div class="-small-video-container">
                    <div class="-small-video">
                        <div class="streams__unit" stream position="2" clients="clients" client="clients | filter:position(2) | limitTo: 1 | findFirst">
                            <app-stream></app-stream>
                        </div>
                    </div>
                </div>
                <div class="-small-video-container">
                    <div class="-small-video">
                        <div class="streams__unit" stream position="3" clients="clients" client="clients | filter:position(3) | limitTo: 1 | findFirst">
                            <app-stream></app-stream>
                        </div>
                    </div>
                </div>
                <div class="-small-video-container">
                    <div class="-small-video">
                        <div class="streams__unit" stream position="4" clients="clients" client="clients | filter:position(4) | limitTo: 1 | findFirst">
                            <app-stream></app-stream>
                        </div>
                    </div>
                </div>
                <div class="-small-video-container">
                    <div class="-small-video">
                        <div class="streams__unit" stream position="5" clients="clients" client="clients | filter:position(5) | limitTo: 1 | findFirst">
                            <app-stream></app-stream>
                        </div>
                    </div>
                </div>
                <div class="-small-video-container">
                    <div class="-small-video">
                        <div class="streams__unit" stream position="6" clients="clients" client="clients | filter:position(6) | limitTo: 1 | findFirst">
                            <app-stream></app-stream>
                        </div>
                    </div>
                </div>
                <div class="-small-video-container">
                    <div class="-small-video">
                        <div class="streams__unit" stream position="7" clients="clients"
                             client="clients | filter:position(7) | limitTo: 1 | findFirst">
                            <app-stream></app-stream>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>