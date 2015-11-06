<div class="tab_2 tabs__content -sms-push">
    <div class="block-style__nav-block">
        <div class="-title">Batch select users:</div>
        <div class="block-style__buttons">
            <a href="javascript:void(0)" class="button -gray" ng-click="push.batchSelect('all')">
                <span class="-text">All</span>
            </a>
            <a href="javascript:void(0)" class="button -gray" ng-click="push.batchSelect('floor')">
                <span class="-text">Floor</span>
            </a>
            <a href="javascript:void(0)" class="button -gray" ng-click="push.batchSelect('building')">
                <span class="-text">Building</span>
            </a>
            <a href="javascript:void(0)" class="button -gray" ng-click="push.batchSelect('none')">
                <span class="-text">None</span>
            </a>
        </div>
    </div>

    <div class="-sms-push__content">
        <div class="-added-names" perfect-scrollbar>
            <a href="javascript:void(0)" class="-added-name icons" ng-class="{
            '-mobile': client.profile.device === 'android' || client.profile.device === 'ios',
            '-desktop': client.profile.device === 'desktop'
            }" ng-repeat="client in clients | filter: {position: {selected: true}}">
                <span class="-icon"></span>
                <span class="-added-name-content icons -close-stream">
                    <span><% client.profile.name %></span>
                    <span class="-icon" ng-click="client.position.selected = false"></span>
                </span>
            </a>
        </div>

        <div class="textarea-block">
            <div class="textarea-block__field">
                <textarea class="textarea-block__textarea" ng-model="push.content" ng-change="push.onContentChange()" ng-trim="false" placeholder="Enter message text here..."></textarea>
                <div class="textarea-block__symbols-left error__text">
                    Symbols left: <span class="-count"><% config['push-notification-limit'] - push.content.length %></span>
                </div>
            </div>

            <div class="textarea-block__settings">
                <div class="textarea-block__select">
                    <ui-select ng-model="push.template" on-select="push.onTemplateChange($item)">
                        <ui-select-match placeholder="Select template"><% $select.selected.label %></ui-select-match>
                        <ui-select-choices repeat="template in push.templates">
                            <div ng-bind-html="template.label | highlight: $select.search"></div>
                        </ui-select-choices>
                    </ui-select>
                </div>
                <div class="textarea-block__submit button -submit" ng-click="push.send(push.content)">Send</div>
            </div>
        </div>
    </div>
</div>