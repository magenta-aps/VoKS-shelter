{{--<control></control>--}}
<div class="block-style -school-plan-block">
    <div class="table-style__table map-tools">

        <div class="container__header table-style__row">
            <span class="container__header-title table-style__cell">Zoom</span>
            <span class="container__header-title table-style__cell">Tools</span>
            <span class="container__header-title table-style__cell">Select building</span>
            <span class="container__header-title table-style__cell">Select floor</span>
        </div>

        <div class="block-style__content table-style__row">
            <div class="table-style__cell">
                <div class="block-style__buttons">
                    <button class="button -gray icons -plus" ng-click="zoomIn()" ng-class="{'-inactive': zoomedIn()}">
                        <span class="-icon"></span>
                    </button
                    ><button class="button -gray icons -minus" ng-click="zoomOut()" ng-class="{'-inactive': zoomedOut()}">
                        <span class="-icon"></span>
                    </button
                    ><button class="button -gray icons -center" ng-click="center()">
                        <span class="-icon"></span>
                    </button>
                </div>
            </div>
            <div class="table-style__cell">
                <div class="block-style__buttons">
                    <button class="button -gray icons -move" ng-click="toggleAction(true, false)" ng-class="{'-active': action_move}">
                        <span class="-icon"></span>
                    </button
                    ><button class="button -gray icons -select" ng-click="toggleAction(false, true)" ng-class="{'-active': action_selection}">
                        <span class="-icon"></span>
                    </button>
                </div>
            </div>

            <div class="table-style__cell">
                <div class="block-style__buttons">
                    <button class="button -gray" ng-repeat="building in buildings | orderBy: 'name'" ng-click="selectBuilding($index)" ng-class="{'-active' : selectedBuilding === $index}">
                        <span class="-text">Building</span>
                    </button>
                </div>
            </div>

            <div class="table-style__cell">
                <div class="block-style__buttons">
                    <button class="button -gray" ng-repeat="floor in buildings[selectedBuilding].floors | orderBy: 'name'" ng-click="selectFloor($index)" ng-class="{'-active' : selectedFloor === $index}">
                        <span class="-text">Floor</span>
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>