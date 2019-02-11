<div class="table-style__table map-tools">

    <div class="container__header table-style__row">
        <span class="container__header-title table-style__cell">{{ Lang::get('school-plan.toolbox.zoom') }}</span>
        <span class="container__header-title table-style__cell">{{ Lang::get('school-plan.toolbox.tools') }}</span>
        <span class="container__header-title table-style__cell">{{ Lang::get('school-plan.toolbox.building') }}</span>
        <span class="container__header-title table-style__cell">{{ Lang::get('school-plan.toolbox.floor') }}</span>
    </div>

    <div class="block-style__content table-style__row">
        <div class="table-style__cell">
            <div class="block-style__buttons">
                <button class="button -gray icons -plus" ng-click="zoomIn()" ng-class="{'-inactive': isZoomedIn()}">
                    <span class="-icon"></span>
                </button
                ><button class="button -gray icons -minus" ng-click="zoomOut()" ng-class="{'-inactive': isZoomedOut()}">
                    <span class="-icon"></span>
                </button
                ><button class="button -gray icons -center" ng-click="center()">
                    <span class="-icon"></span>
                </button>
            </div>
        </div>
        <div class="table-style__cell">
            <div class="block-style__buttons">
                <button class="button -gray icons -move" ng-click="tool('move')" ng-class="{'-active': isTool('move')}">
                    <span class="-icon"></span>
                </button
                ><button class="button -gray icons -select" ng-click="tool('select')" ng-class="{'-active': isTool('select')}">
                    <span class="-icon"></span>
                </button>
            </div>
        </div>

        <div class="table-style__cell">
            <div class="block-style__buttons">
                <building></building>
            </div>
        </div>

        <div class="table-style__cell">
            <div class="block-style__buttons">
                <floor></floor>
            </div>
        </div>

    </div>
</div>