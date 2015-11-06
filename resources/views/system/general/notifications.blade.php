@extends('system.general.base')

@section('tab-content')
    <div class="settings text help-block" ng-controller="PushNotificationController">
        <div class="help-block__nav block-style">
            @include('system.general.partials.tabs')
        </div>
        <div class="help-block__description">
            <h2 class="-title">{{ Lang::get('system.contents.push.title') }}</h2>
            <p class="-text">{{ Lang::get('system.contents.push.description') }}</p>
        </div>

        <div class="help-block__container">
            <div class="custom">
                <div class="button -submit icons -add" ng-click="addItem()">
                    {{ Lang::get('system.contents.push.new') }}
                </div>
                <table class="table-style table-style__settings">
                    <thead>
                    <tr>
                        <th>{{ Lang::get('system.contents.push.table.label') }}</th>
                        <th>{{ Lang::get('system.contents.push.table.content') }}</th>
                        <th class="-cell-options">{{ Lang::get('system.contents.push.table.options') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="notification in list">
                        <td><span editable-text="notification.label" onbeforesave="validateField($data)"  e-name="label" e-form="rowform" e-required><% notification.label %></span></td>
                        <td><span editable-textarea="notification.content" e-rows="3" e-maxlength="144" e-cols="40" onbeforesave="validateField($data)" e-name="content" e-form="rowform" e-required><% notification.content %></span></td>
                        <td style="white-space: nowrap; width:1px;" class="-cell-options">
                            <form editable-form name="rowform" onbeforesave="saveItem(notification, $index)"  ng-show="rowform.$visible" class="form-buttons form-inline" shown="inserted === notification">
                                <div class="btn-group">
                                    <button type="submit" ng-disabled="rowform.$waiting" class="button -settings -check">Save</button>
                                    <button type="button" ng-disabled="rowform.$waiting" ng-click="rowform.$cancel(); cancelEdit(notification.id, $index)" class="button -settings -delete">Cancel</button>
                                </div>
                            </form>

                            <div class="buttons" ng-show="!rowform.$visible">
                                <a href="#" class="button -settings -edit" ng-click="rowform.$show()">Edit</a>
                                <a href="#" class="button -settings -drop" ng-click="removeItem(notification.id, $index)">Remove</a>
                            </div>
                        </td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection