<div class="help-block__description">
    <h2 class="-title">{{ Lang::get('quickhelp-admin.faq') }}</h2>
    <p class="-text">{{ Lang::get('quickhelp-admin.faq_description') }}</p>
</div>

<div class="help-block__container">
    <div class="custom">
        {{--<h1>{{ Lang::get('quickhelp.faq_capitals') }}</h1>--}}

        <a class="button -submit icons -add" ng-click="addItem()">
            {{ Lang::get('quickhelp-admin.new_faq') }}
        </a>

        <a class="button -submit icons" ng-click="syncItems()">
            {{ Lang::get('quickhelp-admin.import_defaults') }}
        </a>

        <table class="table-style table-style__settings">
            <thead>
            <tr>
                <th>{{ Lang::get('quickhelp-admin.question') }}</th>
                <th>{{ Lang::get('quickhelp-admin.answer') }}</th>
                <th class="-cell-options">{{ Lang::get('quickhelp-admin.options') }}</th>
            </tr>
            </thead>
            <tbody>
                <tr ng-repeat="(index, item) in list">
                    <td>
                        <span editable-textarea="item.question" e-name="question" e-form="rowform" onbeforesave="validateField($data)" e-required><% item.question %></span>
                    </td>
                    <td>
                        <span editable-tinymce="item.answer" e-name="answer" e-form="rowform" onbeforesave="validateField($data)" e-required>
                            <span ng-bind-html="item.answer | html"></span>
                        </span>
                    </td>
                    <td class="-cell-options">
                        <form editable-form name="rowform" onbeforesave="saveItem(item, index)" ng-show="rowform.$visible" class="form-buttons form-inline" shown="inserted === item">
                            <div class="btn-group">
                                <button type="submit" ng-disabled="rowform.$waiting" class="button -settings -check">{{ Lang::get('quickhelp-admin.save') }}</button>
                                <button type="button" ng-disabled="rowform.$waiting" class="button -settings -delete" ng-click="rowform.$cancel(); cancelEdit(item.id, index)">
                                    Cancel
                                </button>
                            </div>
                        </form>

                        <div class="buttons" ng-show="!rowform.$visible">
                            <a ng-show="!item.visible" class="toggle-visibility" ng-click="toggleVisibility(item, true)">{{ Lang::get('admin.push.button.show') }}</a>
                            <a ng-show="item.visible" class="toggle-visibility" ng-click="toggleVisibility(item, false)">{{ Lang::get('admin.push.button.hide') }}</a>

                            <a class="button -settings -direction-top" ng-click="orderItem(item, index, 'up')">Up</a>
                            <a class="button -settings -direction-bottom" ng-click="orderItem(item, index, 'down')">Down</a>

                            <a class="button -settings -edit" ng-click="rowform.$show()">Edit</a>
                            <a class="button -settings -drop" ng-click="removeItem(item.id, $index)">Remove</a>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>