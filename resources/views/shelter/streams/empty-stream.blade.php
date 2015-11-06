<div class="streams__empty" ng-show="!client">
    <div class="empty-block">
        <div class="empty-block__content">
            <div class="empty-block__title">Empty place for video stream</div>
            <div class="empty-block__text">Select stream from waiting line or list below</div>
            <ui-select ng-model="selected.client" on-select="$item.placeIntoView(position); selected.client = null;">
                <ui-select-match placeholder="Select stream"><% $select.selected.profile.name %></ui-select-match>
                <ui-select-choices repeat="item in clients | filter: {profile: {name: $select.search}} | filter: {position: {inQueue: true}}">
                    <div ng-bind-html="item.profile.name | highlight: $select.search"></div>
                </ui-select-choices>
            </ui-select>
        </div>
    </div>
</div>