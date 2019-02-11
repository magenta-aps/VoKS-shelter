@extends('system/base')

@section('content')
    <style>
        .-bold {
            font-weight: bold;
        }
    </style>
    <div class="container__wrapper -help" ng-controller="SchoolController">
        <div class="settings text help-block">
            <div class="help-block__description">
                <h2 class="-title">{{ Lang::get('system.contents.school.title') }}</h2>
                <p class="-text">{{ Lang::get('system.contents.school.description') }}</p>
            </div>

            <div class="help-block__container">
                <div class="custom">
                    <a class="button -submit icons -sync" ng-click="syncMaps()">{{ Lang::get('system.contents.school.sync') }}</a>

                    <table class="table-style table-style__settings textarea-block">
                        <thead>
                        <tr>
                            <th>{{ Lang::get('system.contents.school.table.name') }}</th>
                            <th>{{ Lang::get('system.contents.school.table.mac') }}</th>
                            <th>{{ Lang::get('system.contents.school.table.ip') }}</th>
                            <th>{{ Lang::get('system.contents.school.table.ad') }}</th>
                            <th>{{ Lang::get('system.contents.school.table.phone') }}</th>
                            <th class="-cell-options">{{ Lang::get('system.contents.school.table.options') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr ng-repeat="school in list" ng-class="{'-bold': school.id == {{ $shelterId }} }">
                            <td><% school.name %></td>
                            <td><span e-class="textarea-block__input-text -medium" onbeforesave="validateField($data)" editable-text="school.mac_address" e-name="mac_address" e-form="rowform" e-required><% school.mac_address %></span></td>
                            <td><span e-class="textarea-block__input-text -medium" onbeforesave="validateIp(school.id, $data)" editable-text="school.ip_address" e-name="ip_address" e-form="rowform" e-required><% school.ip_address %></span></td>
                            <td><span e-class="textarea-block__input-text -medium" onbeforesave="validateField($data)" editable-text="school.ad_id" e-name="ad_id" e-form="rowform" e-required><% school.ad_id %></span></td>
                            <td><span e-class="textarea-block__input-text -medium" onshow="updatePhoneSystemIdList(school.id)" onbeforesave="validatePhoneSystemId(school.id, $data)" editable-select="school.phone_system_id" e-name="phone_system_id" e-form="rowform" e-required e-ng-options="ids.id as ids.name for ids in model.phoneSystemIds">
                                    <% model.phoneSystemIds[school.phone_system_id].name %>
                            </span></td>
                            <td class="-cell-options">
                                <form editable-form name="rowform" onbeforesave="saveItem(school, $index)" ng-show="rowform.$visible" class="form-buttons form-inline" shown="inserted == school">
                                    <div class="buttons">
                                        <button type="submit" class="button -settings -check">Save</button>
                                        <button type="button" class="button -settings -delete" ng-click="rowform.$cancel();">Cancel</button>
                                    </div>
                                </form>
                                <div class="buttons" ng-show="!rowform.$visible">
                                    <a href="#" class="button -settings -edit" ng-click="rowform.$show()">Edit</a>
                                    {{--<a href="#" class="button -settings -drop" ng-click="removeItem(school.id, $index)">Remove</a>--}}
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