@extends('admin.base')

@section('content')
    <div class="settings text help-block" ng-controller="NotificationController">
        <div class="help-block__nav block-style">
            @include('admin.partials.tabs')
        </div>

        <div class="help-block__description">
            <h2 class="-title">{{ Lang::get('admin.push.title') }}</h2>
            <p class="-text">{{ Lang::get('admin.push.description') }}</p>
        </div>

        <div class="help-block__container">
            <div class="custom">
                <a class="button -submit icons -add" ng-click="addItem()">
                    {{ Lang::get('admin.push.button.new') }}
                </a>
                <a class="button -submit icons" ng-click="syncItems()">
                    {{ Lang::get('admin.push.button.import') }}
                </a>
                <table class="table-style table-style__settings">
                    <thead>
                    <tr>
                        <th>{{ Lang::get('admin.push.table.label') }}</th>
                        <th>{{ Lang::get('admin.push.table.content') }}</th>
                        <th class="-cell-options">{{ Lang::get('admin.push.table.options') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="(index, notification) in list">
                        <td><span editable-text="notification.label" onbeforesave="validateField($data)"  e-name="label" e-form="rowform" e-required><% notification.label %></span></td>
                        <td><span editable-textarea="notification.content" e-rows="3" e-cols="40" e-maxlength="144" onbeforesave="validateField($data)" e-name="content" e-form="rowform" e-required><% notification.content %></span></td>
                        <td style="white-space: nowrap; width:1px;" class="-cell-options">
                            <form editable-form name="rowform" onbeforesave="saveItem(notification, $index)"  ng-show="rowform.$visible" class="form-buttons form-inline" shown="inserted === notification">
                                <div class="btn-group">
                                    <button type="submit" ng-disabled="rowform.$waiting" class="button -settings -check">Save</button>
                                    <button type="button" ng-disabled="rowform.$waiting" ng-click="rowform.$cancel(); cancelEdit(notification.id, $index)" class="button -settings -delete">Cancel</button>
                                </div>
                            </form>

                            <div class="buttons" ng-show="!rowform.$visible">
                                <a ng-show="!notification.visible" class="toggle-visibility" ng-click="toggleVisibility(notification, true)">{{ Lang::get('admin.push.button.show') }}</a>
                                <a ng-show="notification.visible" class="toggle-visibility" ng-click="toggleVisibility(notification, false)">{{ Lang::get('admin.push.button.hide') }}</a>

                                <a class="button -settings -direction-top" ng-click="orderItem(notification, index, 'up')">Up</a>
                                <a class="button -settings -direction-bottom" ng-click="orderItem(notification, index, 'down')">Down</a>

                                <a class="button -settings -edit" ng-click="rowform.$show()">Edit</a>
                                <a class="button -settings -drop" ng-click="removeItem(notification.id, $index)">Remove</a>
                            </div>
                        </td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection