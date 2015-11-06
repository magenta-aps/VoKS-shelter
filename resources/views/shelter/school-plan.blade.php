<div class="streams -reset ng-hide" ng-show="tab === 'school-plan'" ng-controller="SchoolPlanController">
    <div class="streams__large">
        <div class="streams__main">
            @include('shelter/maps/large-map')
            @include('shelter/school-plan/toolbox')
            @include('shelter/school-plan/messages')
        </div>
        <div class="streams__small">
            @include('shelter/streams/small-stream-list')
        </div>
    </div>
</div>