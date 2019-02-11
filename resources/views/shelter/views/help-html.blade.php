<div id="help-view" class="settings text help-block ng-help">
    <p><% help.files[0].description %></p>
    <div class="help-block__container">
        <div class="help-block__side-left">
            <h2 class="-title">{{ Lang::get('help.faq') }}</h2>

            <div class="help-block__faq">
                <div class="help-block__faq-item" ng-repeat="faq in help.faq">
                    <h3 class="-title icons -expand"><% faq.question %><span class="-icon"></span></h3>
                    <div class="-text" ng-bind-html="faq.answer | html"></div>
                </div>
            </div>
        </div>

        <div class="help-block__side-right" ng-if="'off' === status.police.called">
            <h2>{{ Lang::get('help.instructions') }}</h2>
            <div>
                <a ng-repeat="file in help.files" target="_blank" href="/download/faq-file/<% file.id %>" class="icons -pdf ng-scope">
                    <span class="-text">{{ Lang::get('help.download') }}</span>
                </a>
                <a ng-repeat="print in help.files" href="javascript:void(0)" target="_blank" ng-click="printFile(print.file_url)" class="icons -print ng-scope">
                    <span class="-text">{{ Lang::get('help.print') }}</span>
                </a>
            </div>
        </div>

        <div class="help-block__side-right" ng-if="'on' === status.police.called">
            <h2>{{ Lang::get('help.instructions_police') }}</h2>
            <div>
                <a ng-repeat="file in help.files" target="_blank" href="/download/faq-file/<% file.id %>/1" class="icons -pdf ng-scope">
                    <span class="-text">{{ Lang::get('help.download') }}</span>
                </a>
                <a ng-repeat="print in help.files" href="javascript:void(0)" target="_blank" ng-click="printFile(print.police_file_url)" class="icons -print ng-scope">
                    <span class="-text">{{ Lang::get('help.print') }}</span>
                </a>
            </div>
        </div>
    </div>
</div>