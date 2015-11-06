<header class="header" ng-class="{'-active': alarm.stats.status == 2}" ng-controller="HeaderController">
    <div class="header__container">
        <div class="table-style__table">
            <div class="table-style__cell">
                <a href="/" class="header__logo" title="BComeSafe" ng-click="changeTab('stream')">BComeSafe</a>

                <ul class="menu -header">
                    <li class="menu__unit" ng-repeat="item in tabs">
                        <a href="/#/<% item.path %>" class="menu__item icons <% item.icon %>" ng-class="{'-active': view == item.path}" title="">
                            <span class="-icon"></span>
                            <span class="-title"><% item.title %></span>
                        </a>
                    </li>
                    <li class="menu__unit">
                        <a href="#" ng-click="alarm.reset()" class="menu__item icons" title="Reset">
                            <span class="-title">Reset</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="table-style__cell">
                <div class="header__status">
                    <span class="header__status-text"><% alarm.statusMessages[alarm.stats.status] %></span>
                </div>
            </div>


            <div class="table-style__cell">
                <div class="police-status">
                    <span class="police-status__text">Change police status</span>

                    <div class="police-status__toggle switch">
                        <input type="radio" name="switchHeader" class="switch__radio -on" value="2" id="switchOn" ng-model="alarm.stats.status" ng-change="alarm.toggleStatus()" />
                        <input type="radio" name="switchHeader" class="switch__radio -off" value="0" id="switchOf" ng-model="alarm.stats.status" ng-change="alarm.toggleStatus()" />

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
                            <span ng-show="alarm.stats.status > 0">ON</span>
                            <span ng-show="alarm.stats.status == 0">OFF</span>
                        </span>
                    </li>
                    <li class="status-bar__unit">
                        <span class="status-bar__item -left">Started</span>
                        <span class="status-bar__item -right"><% alarm.stats.started %></span>
                    </li>
                    <li class="status-bar__unit">
                        <span class="status-bar__item -left">Police called</span>
                        <span class="status-bar__item -right"><% alarm.stats.police_called %> users</span>
                    </li>
                </ul>

                <a href="/admin/configuration" title="Settings" class="header__button icons -settings">
                    <span class="-icon"></span>
                </a>

                <a href="#" title="Help" class="header__button icons -help">
                    <span class="-icon"></span>
                </a>
            </div>
        </div>
    </div>
</header>