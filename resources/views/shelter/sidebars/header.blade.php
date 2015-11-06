<header class="header" ng-class="{'-active': stats.status == 1}">
    <div class="header__container">
        <div class="table-style__table">
            <div class="table-style__cell">
                <a href="#/" class="header__logo" title="BComeSafe" ng-click="changeTab('streams')">BComeSafe</a>

                <ul class="menu -header">
                    <li class="menu__unit" ng-repeat="item in tabs">
                        <a href="#" ng-click="switchTab(item.path); item.onClick();" class="menu__item icons <% item.icon %>" ng-class="{'-active': tab == item.path}" title="">
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
                    <span class="police-status__text">Change police status</span>

                    <div class="police-status__toggle switch">
                        <input type="radio" name="switchHeader" class="switch__radio -on" value="1" id="switchOn" ng-model="stats.status" ng-change="toggleStatus()" />
                        <input type="radio" name="switchHeader" class="switch__radio -off" value="0" id="switchOf" ng-model="stats.status" ng-change="toggleStatus()" />

                        <label class="switch__label -off" for="switchOf">
                            <span>Not called</span>
                        </label>

                        <div class="switch__container">
                            <div class="switch__button"></div>
                        </div>

                        <label class="switch__label -on" for="switchOn">
                            <span>Called</span>
                        </label>
                    </div>

                </div>

                <ul class="status-bar">
                    <li class="status-bar__unit">
                        <span class="status-bar__item -left">Alarm status</span>
                        <span class="status-bar__item -right">
                            <span ng-show="stats.status > 0">ON</span>
                            <span ng-show="stats.status == 0">OFF</span>
                        </span>
                    </li>
                    <li class="status-bar__unit">
                        <span class="status-bar__item -left">Started</span>
                        <span class="status-bar__item -right"><% stats.started %></span>
                    </li>
                    <li class="status-bar__unit">
                        <span class="status-bar__item -left">Police called</span>
                        <span class="status-bar__item -right"><% stats.police_called %> users</span>
                    </li>
                </ul>

                <a href="/admin/general" title="Settings" class="header__button icons -settings">
                    <span class="-icon"></span>
                </a>

                <a href="javascript:void(0);" ng-click="showQuickHelp()" title="Help" class="header__button js-help-btn icons -help">
                    <span class="-icon"></span>
                </a>
            </div>
        </div>
    </div>
</header>