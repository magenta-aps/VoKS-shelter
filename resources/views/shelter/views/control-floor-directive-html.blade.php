<button class="button -gray" ng-repeat="floor in ::getFloors()" ng-class="{'-active': isFloor(floor.id)}" ng-click="setFloor(floor.id)">
    <span class="-text"><% floor.name %></span>
</button>