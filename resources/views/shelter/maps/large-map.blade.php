<div class="bullets">
    <div id="selectable" class="bullets" selectable="selection" selectable-list="clients">
        @if ( config('aruba.ale.coordinatesEnabled') )
            <map id="map" class="map-container" ng-class="{'h-selection-enable': action_selection}"></map>
        @else
            <bc-map id="map"></bc-map>
        @endif
    </div>
</div>