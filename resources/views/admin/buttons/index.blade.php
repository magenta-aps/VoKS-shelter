@extends('admin.base')

@section('content')
    <div class="settings text help-block" ng-controller="ButtonController">

        <div class="help-block__nav block-style">
            @include('admin.partials.tabs')
        </div>

        <div class="help-block__description">
            <h2 class="-title">{{ Lang::get('admin.buttons.title') }}</h2>
            <p class="-text">{{ Lang::get('admin.buttons.description') }}</p>
        </div>

        <div class="help-block__container">
            <div class="custom">

                <table class="table-style table-style__settings textarea-block">
                    <thead>
                    <tr>
                        <th>{{ Lang::get('admin.buttons.table.number') }}</th>
                        <th>{{ Lang::get('admin.buttons.table.name') }}</th>
                        <th>{{ Lang::get('admin.buttons.table.cbf') }}</th>
                        <th>{{ Lang::get('admin.buttons.table.mac') }}</th>
                        <th>{{ Lang::get('admin.buttons.table.number') }}</th>
                        <th>{{ Lang::get('admin.buttons.table.x') }}</th>
                        <th>{{ Lang::get('admin.buttons.table.y') }}</th>
                        <th class="-cell-options">{{ Lang::get('admin.buttons.table.options') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="button in list">
                        <td><% button.button_number %></td>
                        <td><% button.button_name %></td>
                        <td><% button.title %></td>
                        <td><% button.mac_address %></td>
                        <td><% button.ip_address %></td>
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
                            <form editable-form name="rowform" onbeforesave="saveItem(button)"
                                  ng-show="rowform.$visible" class="form-buttons form-inline"
                                  shown="inserted == button">
                                <div class="buttons">
                                    <button type="submit" class="button -settings -check">Save</button>
                                    <button type="button" ng-click="rowform.$cancel(); cancelEdit(button, $index)"
                                            class="button -settings -delete">Cancel
                                    </button>
                                </div>
                            </form>

                            <div class="buttons" ng-show="!rowform.$visible">
                                <a href="#" class="button -settings -preview"
                                   ng-click="previewButton(button)">Preview</a>
                                <a href="#" class="button -settings -edit" ng-click="rowform.$show()">Edit</a>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection