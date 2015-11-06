<header class="header" ng-class="{'-active': stats.status == 2}" ng-controller="HeaderController">
    <div class="header__container">
        <div class="table-style__table">
            <div class="table-style__cell">
                <a href="/#/" class="header__logo" title="{{ Lang::get('header.logo.title') }}" ng-click="changeTab('streams')">
                    <img src="/frontend/images/logo.png" alt="{{ Lang::get('header.logo.title') }}" />
                </a>
                <ul class="menu -header">
                    <li class="menu__unit" ng-repeat="item in tabs">
                        <a href="/#/<% item.path %>" ng-click="switchTab(item.path); item.onClick();" class="menu__item icons <% item.icon %>" ng-class="{'-active': tab == item.path}" title="">
                            <span class="-icon"></span>
                            <span class="-title"><% item.title %></span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="table-style__cell">
                <div class="header__status">
                    <span class="header__status-text"><% states[stats.status] %></span>
                </div>
            </div>

            <div class="table-style__cell">
                <div class="police-status">
                    <span class="police-status__text">{{ Lang::get('app.police.switch.status') }}</span>

                    <div class="police-status__toggle switch">
                        <input type="radio" name="switchHeader" class="switch__radio -on" value="2" id="switchOn" ng-model="stats.status" ng-change="toggleStatus()" />
                        <input type="radio" name="switchHeader" class="switch__radio -off" value="0" id="switchOf" ng-model="stats.status" ng-change="toggleStatus()" />

                        <label class="switch__label -off" for="switchOf">
                            <span>{{ Lang::get('app.police.switch.not_called') }}</span>
                        </label>

                        <div class="switch__container">
                            <div class="switch__button"></div>
                        </div>

                        <label class="switch__label -on" for="switchOn">
                            <span>{{ Lang::get('app.police.switch.called') }}</span>
                        </label>
                    </div>

                </div>

                <ul class="status-bar">
                    <li class="status-bar__unit">
                        <span class="status-bar__item -left">{{ Lang::get('app.police.status.label') }}</span>
                        <span class="status-bar__item -right">
                            <span ng-show="stats.status > 0">{{ Lang::get('app.police.status.on') }}</span>
                            <span ng-show="stats.status == 0">{{ Lang::get('app.police.status.off') }}</span>
                        </span>
                    </li>
                    <li class="status-bar__unit">
                        <span class="status-bar__item -left">{{ Lang::get('app.police.status.started') }}</span>
                        <span class="status-bar__item -right"><% stats.started %></span>
                    </li>
                    <li class="status-bar__unit">
                        <span class="status-bar__item -left">{{ Lang::get('app.police.status.police_called') }}</span>
                        <span class="status-bar__item -right"><% stats.police_called %> {{ Lang::get('app.police.status.users') }}</span>
                    </li>
                </ul>

                <a href="/admin/general" title="Settings" class="header__button icons -settings">
                    <span class="-icon"></span>
                </a>

            </div>
        </div>
    </div>
</header>