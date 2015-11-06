@extends('system/base')

@section('content')
    <div class="container__wrapper -help" ng-controller="ButtonController">
        <div class="settings text help-block">
            <div class="help-block__description">
                <h2 class="-title">{{ Lang::get('admin.buttons.title') }}</h2>
                <p class="-text">{{ Lang::get('admin.buttons.description') }}</p>
            </div>

            <div class="help-block__container">
                <div class="custom">
                    <a class="button -submit icons -add" ng-click="addItem()">{{ Lang::get('admin.buttons.add') }}</a>

                    <table class="table-style table-style__settings textarea-block">
                        <thead>
                        <tr>
                            <th>{{ Lang::get('admin.buttons.table.number') }}</th>
                            <th>{{ Lang::get('admin.buttons.table.name') }}</th>
                            <th>{{ Lang::get('admin.buttons.table.cbf') }}</th>
                            <th>{{ Lang::get('admin.buttons.table.mac') }}</th>
                            <th>{{ Lang::get('admin.buttons.table.ip') }}</th>
                            <th>{{ Lang::get('admin.buttons.table.x') }}</th>
                            <th>{{ Lang::get('admin.buttons.table.y') }}</th>
                            <th class="-cell-options">{{ Lang::get('admin.buttons.table.options') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr ng-repeat="button in list">
                            <td>
                                <span editable-text="button.button_number" onbeforesave="validateField($data)"
                                      e-class="textarea-block__input-text -small" e-name="button_number"
                                      e-form="rowform" e-required><% button.button_number %></span>
                            </td>
                            <td><span editable-text="button.button_name" onbeforesave="validateField($data)"
                                      e-class="textarea-block__input-text -medium" e-name="button_name" e-form="rowform"
                                      e-required><% button.button_name %></span>
                            </td>
                            <td ng-show="rowform.$visible">
                                <ui-select ng-model="button.floor" on-select="onFloorChange(button, $model)" theme="select2">
                                    <ui-select-match placeholder={{ Lang::get('admin.buttons.placeholder') }}><% $select.selected.title %></ui-select-match>
                                    <ui-select-choices repeat="floor in floors | filter: $select.search">
                                        <div ng-bind-html="floor.title | highlight: $select.search"></div>
                                    </ui-select-choices>
                                </ui-select>
                            </td>

                            <td ng-show="!rowform.$visible"><% floor = (floors | filter: {id: button.floor_id}); floor[0].title %></td>

                            <td><span editable-text="button.mac_address" onbeforesave="validateField($data)"
                                      e-class="textarea-block__input-text -medium" e-name="mac_address" e-form="rowform"
                                      e-required><% button.mac_address %></span>
                            </td>
                            <td><span editable-text="button.ip_address" e-name="ip_address" onbeforesave="validateField($data)"
                                      e-form="rowform" e-class="textarea-block__input-text -medium"
                                      e-required><% button.ip_address %></span>
                            </td>
                            <td><span editable-text="button.x" e-name="x" onbeforesave="validateField($data)"
                                      e-form="rowform" e-class="textarea-block__input-text -small"
                                      e-required><% button.x %></span>
                            </td>
                            <td>
                                <span editable-text="button.y" e-name="y" onbeforesave="validateField($data)"
                                      e-form="rowform" e-class="textarea-block__input-text -small"
                                      e-required><% button.y %>
                                </span>
                            </td>
                            <td class="-cell-options">
                                <form editable-form name="rowform" onbeforesave="saveItem(button, $index)"
                                      ng-show="rowform.$visible" class="form-buttons form-inline"
                                      shown="inserted == button">
                                    <div class="buttons">
                                        <button type="submit" class="button -settings -check">Save</button>
                                        <button type="button" ng-click="rowform.$cancel(); cancelEdit(button, $index)" class="button -settings -delete">Cancel</button>
                                    </div>
                                </form>

                                <div class="buttons" ng-show="!rowform.$visible">
                                    <a href="#" class="button -settings -preview" ng-click="previewButton(button)">Preview</a>
                                    <a href="#" class="button -settings -edit" ng-click="rowform.$show()">Edit</a>
                                    <a href="#" class="button -settings -drop" ng-click="removeItem(button.id, $index)">Remove</a>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection