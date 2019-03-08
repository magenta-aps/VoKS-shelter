@extends('admin.base')

@section('content')
    <div class="settings text help-block" ng-controller="ReportsController">

        <div class="help-block__nav block-style">
            @include('admin.partials.tabs')
        </div>

        <div class="help-block__description">
            <h2 class="-title">{{ Lang::get('admin.reports.title') }}</h2>
            <p class="-text">{{ Lang::get('admin.reports.description') }}</p>
        </div>

        <div class="help-block__container">
            <div class="custom">

                <table class="table-style table-style__settings textarea-block">
                    <thead>
                    <tr>
                        <th>{{ Lang::get('admin.reports.table.created_at') }}</th>
                        <th>{{ Lang::get('admin.reports.table.log_type') }}</th>
                        <th>{{ Lang::get('admin.reports.table.device_type') }}</th>
                        <th>{{ Lang::get('admin.reports.table.device_id') }}</th>
                        <th>{{ Lang::get('admin.reports.table.fullname') }}</th>
                        <th>{{ Lang::get('admin.reports.table.mac_address') }}</th>
                        <th>{{ Lang::get('admin.reports.table.floor_id') }}</th>
                        <th>{{ Lang::get('admin.reports.table.x') }}</th>
                        <th>{{ Lang::get('admin.reports.table.y') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="log_item in list">
                        <td><% log_item.created_at %></td>
                        <td><% log_item.log_type %></td>
                        <td><% log_item.device_type %></td>
                        <td><% log_item.device_id %></td>
                        <td><% log_item.fullname %></td>
                        <td><% log_item.mac_address %></td>
                        <td><% log_item.floor_id %></td>
                        <td><% log_item.x %></td>
                        <td><% log_item.y %></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection