<div id="school-view" class="streams -reset">
    <div class="streams__large">
        <div class="streams__main">
            @if ( config('aruba.ale.coordinatesEnabled') )
                <map id="stream"></map>
            @else
                <bc-map id="stream"></bc-map>
            @endif
            <div class="block-style -send-messages-block tabs" ng-controller="SchoolPlanController">
                <div class="container__header">
                    <span class="-title"> {{ Lang::get('school-plan.messages.label') }}</span>
                </div>

                <div class="block-style__tabs">
                    <div class="tabs__nav">
                        <a href="javascript:void(0)" id="message-push" class="tabs__item" ng-class="{'-active': currentMessageTab === 'push'}" ng-click="currentMessageTab = 'push'"> {{ Lang::get('school-plan.messages.tabs.push') }}</a>
                        <a href="javascript:void(0)" id="message-audio" class="tabs__item" ng-class="{'-active': currentMessageTab === 'audio'}" ng-click="currentMessageTab = 'audio'">{{ Lang::get('school-plan.messages.tabs.audio') }}</a>
                        <a href="javascript:void(0)" id="message-history" class="tabs__item" ng-class="{'-active': currentMessageTab === 'history'}" ng-click="currentMessageTab = 'history'">{{ Lang::get('school-plan.messages.tabs.history') }}</a>
                    </div>
                </div>

                <div class="block-style__content">
                    <div ng-show="currentMessageTab === 'push'">
                        <div class="tab_2 tabs__content -sms-push">
                            <div class="block-style__nav-block">
                                <div class="-title">{{ Lang::get('school-plan.messages.batch_select') }}</div>
                                <div class="block-style__buttons">
                                    <a href="javascript:void(0)" class="button -gray" ng-click="push.batchSelect('all')">
                                        <span class="-text">{{ Lang::get('school-plan.messages.button.all') }}</span>
                                    </a>
                                    <a href="javascript:void(0)" class="button -gray" ng-click="push.batchSelect('floor')">
                                        <span class="-text">{{ Lang::get('school-plan.messages.button.floor') }}</span>
                                    </a>
                                    <a href="javascript:void(0)" class="button -gray" ng-click="push.batchSelect('building')">
                                        <span class="-text">{{ Lang::get('school-plan.messages.button.building') }}</span>
                                    </a>
                                    <a href="javascript:void(0)" class="button -gray" ng-click="push.batchSelect('none')">
                                        <span class="-text">{{ Lang::get('school-plan.messages.button.none') }}</span>
                                    </a>
                                </div>
                            </div>

                            <div class="-sms-push__content">
                                <div class="-added-names" perfect-scrollbar>
                                    <a href="javascript:void(0)" class="-added-name icons" ng-class="{
                                    '-mobile': client.profile.device === 'android' || client.profile.device === 'ios',
                                    '-desktop': client.profile.device === 'desktop'
                                    }" ng-repeat="client in clients | filter: {position: {selected: true}}" update-scrollbar>
                                        <span class="-icon"></span>
                                        <span class="-added-name-content icons -close-stream">
                                            <span><% client.profile.name %></span>
                                            <span class="-icon" ng-click="client.position.selected = false"></span>
                                        </span>
                                    </a>
                                </div>

                                <div class="textarea-block">
                                    <div class="textarea-block__field">
                                        <textarea class="textarea-block__textarea" ng-model="push.content" ng-change="push.onContentChange()" ng-trim="false" placeholder="{{ Lang::get('school-plan.messages.placeholder.enter_message') }}"></textarea>
                                        <div class="textarea-block__symbols-left error__text">
                                            {{ Lang::get('school-plan.messages.placeholder.symbols') }} <span class="-count"><% config['push-notification-limit'] - push.content.length %></span>
                                        </div>
                                    </div>

                                    <div class="textarea-block__settings">
                                        <div class="textarea-block__select">
                                            <ui-select ng-model="push.template" on-select="push.onTemplateChange($item)">
                                                <ui-select-match placeholder="{{ Lang::get('school-plan.messages.placeholder.select_template') }}"><% $select.selected.label %></ui-select-match>
                                                <ui-select-choices repeat="template in push.templates">
                                                    <div ng-bind-html="template.label | highlight: $select.search"></div>
                                                </ui-select-choices>
                                            </ui-select>
                                        </div>
                                        <div class="textarea-block__submit button -submit" ng-click="push.send(push.content)">{{ Lang::get('school-plan.messages.send') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div ng-show="currentMessageTab === 'audio'">
                        <div class="-audio__content">
                            <div class="-audio__top" ng-controller="AudioPlayController as ctrl">
                            {{--<div class="-audio__top">--}}
                                <div class="-audio__select">
                                    <ui-select ng-model="ctrl.voiceId">
                                        <ui-select-match placeholder="{{ Lang::get('shelter/audio.play.field.voice.placeholder') }}"><% $select.selected.name %></ui-select-match>
                                        <ui-select-choices repeat="voice.id as voice in ctrl.voices">
                                            <div ng-bind-html="voice.name | highlight: $select.search"></div>
                                        </ui-select-choices>
                                    </ui-select>
                                </div>
                                <div class="-audio__select">
                                    <ui-select ng-model="ctrl.groupId">
                                        <ui-select-match placeholder="{{ Lang::get('shelter/audio.play.field.group.placeholder') }}"><% $select.selected.name %></ui-select-match>
                                        <ui-select-choices repeat="group.id as group in ctrl.groups | filter: $select.search">
                                            <div ng-bind-html="group.name | highlight: $select.search"></div>
                                        </ui-select-choices>
                                    </ui-select>
                                </div>
                                <a href="javascript:void(0);" class="button -submit" ng-click="ctrl.play()" ng-class="{'-inactive': ctrl.inactive}">{{ Lang::get('shelter/audio.play.field.submit') }}</a>
                            </div>
                            <div class="-audio__bottom" ng-controller="AudioBroadcastController as ctrl">
                                <h3>{{ Lang::get('shelter/audio.live.heading') }}</h3>
                                <div class="-audio__select">
                                    <ui-select ng-model="ctrl.groupId">
                                        <ui-select-match placeholder="{{ Lang::get('shelter/audio.live.field.group.placeholder') }}"><% $select.selected.name %></ui-select-match>
                                        <ui-select-choices repeat="group.id as group in ctrl.groups | filter: $select.search">
                                            <div ng-bind-html="group.name | highlight: $select.search"></div>
                                        </ui-select-choices>
                                    </ui-select>
                                </div>
                                <div class="-audio__input">
                                    <label for="input-number">{{ Lang::get('shelter/audio.live.field.number.label') }}</label>
                                    <input id="input-number" type="text" class="textarea-block__input-text" name="number" ng-model="ctrl.number" ng-init="ctrl.number='{{ $audio['number'] }}'" placeholder="{{ Lang::get('shelter/audio.live.field.number.placeholder') }}" />
                                </div>
                                <a href="javascript:void(0);" class="button -submit" ng-click="ctrl.play()" ng-class="{'-inactive': ctrl.inactive}">{{ Lang::get('shelter/audio.play.field.submit') }}</a>
                            </div>
                        </div>
                    </div>
                    <div ng-show="currentMessageTab === 'history'">
                        <div class="tab_4 tabs__content -history">
                            <div class="-history__content" perfect-scrollbar>
                                <table cellpadding="0" cellspacing="0" border="1" class="-history">
                                    <thead>
                                    <tr>
                                        <th>{{ Lang::get('school-plan.messages.history.time') }}</th>
                                        <th>{{ Lang::get('school-plan.messages.history.message') }}</th>
                                        <th>{{ Lang::get('school-plan.messages.history.type') }}</th>
                                        <th>{{ Lang::get('school-plan.messages.history.result') }}</th>
                                    </tr>
                                    <tr class="-border">
                                        <th colspan="4"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr ng-repeat="item in history">
                                        <td><% item.time %></td>
                                        <td class="message" ng-bind-html="item.message | html"></td>
                                        <td><% item.type %></td>
                                        <td ng-bind-html="item.result | html"></td>
                                    </tr>
                                    </tbody>
                                </table>
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