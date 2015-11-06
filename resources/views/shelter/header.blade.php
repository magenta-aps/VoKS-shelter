<header class="header" ng-controller="HeaderController as Header" ng-class="{'-active': Header.isPoliceCalled()}">
    <div class="header__container">
        <div class="table-style__table">
            <div class="table-style__cell">
                <a href="/#/" class="header__logo" title="{{ Lang::get('header.logo.title') }}">
                    <img src="/images/logo.png" alt="{{ Lang::get('header.logo.title') }}" />
                </a>
                <ul class="menu -header">
                    @foreach ($menu as $item)
                    <li class="menu__unit">
                        <a href="/#/{{ $item['path'] }}" class="menu__item icons {{ $item['class'] }}" title="{{ $item['title'] }}" ng-class="{'-active': Header.isMenu('{{ $item['path'] }}')}">
                            <span class="-icon"></span>
                            <span class="-title">{{ $item['title'] }}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            <div class="table-style__cell">
                <div class="header__status">
                    <span class="header__status-text"><% Header.text.heading[Header.status.police.status] | translate %></span>
                </div>
            </div>

            <div class="table-style__cell">
                <ul class="status-bar">
                    <li class="status-bar__unit">
                        <span class="status-bar__item"><% Header.text.alarm[Header.status.alarm.status] | translate %><% Header.status.alarm.time %></span>
                    </li>
                    <li class="status-bar__unit" ng-show="Header.status.alarm.status">
                        <span class="status-bar__item">{{ Lang::get('header.status.police.title') }}: <% Header.status.police.count %> {{ Lang::get('header.status.police.users') }}</span>
                    </li>
                </ul>

                <div class="police-status">
                    <span class="police-status__text">{{ Lang::get('header.police.title') }}</span>
                    <div class="police-status__toggle switch">
                        <input id="switchOn" type="radio" name="switchHeader" class="switch__radio -on" value="on" ng-model="Header.status.police.called" />
                        <input id="switchOf" type="radio" name="switchHeader" class="switch__radio -off" value="off" ng-model="Header.status.police.called" />
                        <label for="switchOf" class="switch__label -off" ng-click="Header.policeOff()">
                            <span>{{ Lang::get('header.police.no') }}</span>
                        </label>
                        <div class="switch__container">
                            <div class="switch__button"></div>
                        </div>
                        <label for="switchOn" class="switch__label -on" ng-click="Header.policeOn()">
                            <span>{{ Lang::get('header.police.yes') }}</span>
                        </label>
                    </div>
                </div>

                <a href="javascript:void(0);" ng-click="Header.showQuickHelp()" title="{{ Lang::get('header.menu.user.help') }}" class="header__button js-help-btn icons -help">
                    <span class="-icon"></span>
                </a>

                <a href="/admin/general" title="{{ Lang::get('header.menu.user.settings') }}" class="header__button icons -settings">
                    <span class="-icon"></span>
                </a>
            </div>
        </div>
    </div>
</header>