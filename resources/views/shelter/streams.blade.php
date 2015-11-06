<div class="container__content streams" ng-show="!state.singleStream && tab === 'streams'">
    @include('shelter/views/small-view')
</div>
<div class="streams ng-hide" ng-show="state.singleStream && tab === 'streams'">
    @include('shelter/views/large-view')
</div>