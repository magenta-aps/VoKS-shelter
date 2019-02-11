    <div class="settings text help-block ng-help" ng-show="tab === 'help'" ng-controller="HelpController">
        <h1>Help and Documentation</h1>

        <p>List of persons who are added into crysis team. Nullam sed vestibulum lectus. Fusce porta posuere diam, quis pharetra odio iaculis vel. Suspendisse potenti. Integer vitae eros finibus, semper risus eu, suscipit dui. Cras vehicula interdum arcu. Morbi consequat libero nisl, eu convallis enim posuere id. Etiam non bibendum ante. Donec facilisis purus nec maximus porttitor. Integer vitae maximus urna.</p>

        <div class="help-block__container">
            <div class="help-block__side-left">
                <h2 class="-title">Frequently Asked Questions and Answers</h2>

                <div class="help-block__faq">
                    <div class="help-block__faq-item" ng-repeat="faq in help.faq">
                        <h3 class="-title icons -expand"><% faq.question %><span class="-icon"></span></h3>
                        <p class="-text"><% faq.answer %> </p>
                    </div>
                </div>
            </div>

            <div class="help-block__side-right">
                <h2>Quick Use Instructions</h2>
                <div ng-if="stats.status == 0 || stats.status == 1">
                    <a ng-repeat="file in help.files" target="_blank" href="/download/faq-file/<% file.id %>" class="icons -pdf ng-scope">
                        <span class="-text">Download Quick Use Instruction</span>
                    </a>
                    <a ng-repeat="print in help.files" href="javascript:void(0)" target="_blank" ng-click="printFile(print.file_url)" class="icons -print ng-scope">
                        <span class="-text">Print Quick Use Instruction</span>
                    </a>
                </div>
                <div ng-if="stats.status > 1">
                    <a ng-repeat="file in help.files" target="_blank" href="/download/faq-file/<% file.id %>/1" class="icons -pdf ng-scope">
                        <span class="-text">Download Police Quick Use Instruction</span>
                    </a>
                    <a ng-repeat="print in help.files" href="javascript:void(0)" target="_blank" ng-click="printFile(print.police_file_url)" class="icons -print ng-scope">
                        <span class="-text">Print Police Quick Use Instruction</span>
                    </a>
                </div>
            </div>
        </div>
    </div>