<div class="help-block__description">
    <h2 class="-title">
        {{ Lang::get('quickhelp-system.faq') }}
    </h2>

    <p class="-text">
        {{ Lang::get('quickhelp-system.faq_description') }}
    </p>
</div>

<div class="help-block__container" ng-controller="FaqController">
    <div class="custom">
        {{--<h1>{{ Lang::get('quickhelp-system.faq_capitals') }}</h1>--}}

        <div class="button -submit icons -add" ng-click="addItem()">
            {{ Lang::get('quickhelp-system.new_faq') }}
        </div>
        <table class="table-style table-style__settings">
            <thead>
            <tr>
                <th>{{ Lang::get('quickhelp-system.question') }}</th>
                <th>{{ Lang::get('quickhelp-system.answer') }}</th>
                <th class="-cell-options">{{ Lang::get('quickhelp-system.options') }}</th>
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
                                <button type="submit" ng-disabled="rowform.$waiting" class="button -settings -check">{{ Lang::get('quickhelp-system.save') }}</button>
                                <button type="button" ng-disabled="rowform.$waiting" class="button -settings -delete" ng-click="rowform.$cancel(); cancelEdit(item.id, index)">
                                    Cancel
                                </button>
                            </div>
                        </form>

                        <div class="buttons" ng-show="!rowform.$visible">
                            <a ng-show="!item.visible" ng-click="toggleVisibility(item, true)">{{ Lang::get('admin.push.button.show') }}</a>
                            <a ng-show="item.visible" ng-click="toggleVisibility(item, false)">{{ Lang::get('admin.push.button.hide') }}</a>

                            <a class="button -settings -edit" ng-click="rowform.$show()">Edit</a>
                            <a class="button -settings -drop" ng-click="removeItem(item.id, $index)">Remove</a>

                            <a class="button -settings -direction-top" ng-click="orderItem(item, index, 'up')">Up</a>
                            <a class="button -settings -direction-bottom" ng-click="orderItem(item, index, 'down')">Down</a>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>