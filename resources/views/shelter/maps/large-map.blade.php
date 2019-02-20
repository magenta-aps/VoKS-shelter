<div class="bullets">
    <div id="selectable" class="bullets" selectable="selection" selectable-list="clients">
        <map id="map" class="map-container" ng-class="{'h-selection-enable': action_selection}" ng-if="useNonGps()"></map>
        <bc-map id="map" ng-if="useGps()"></bc-map>
    </div>
</div>