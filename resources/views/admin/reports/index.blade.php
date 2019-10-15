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
                <form>
                    <div class="form-group">
                        <label for="dateRange" class="control-label">{{ Lang::get('admin.reports.filters.date_range') }}</label>
                        <div>
                            <input date-range-picker id="dateRange" name="dateRange" class="form-control date-picker" type="text" ng-model="searchFilter.date" options="dateOpts" clearable="true"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>
                            <input type="radio" ng-model="searchFilter.false_alarm" value="all">
                            {{ Lang::get('admin.reports.filters.false_alarm.all') }}
                        </label><br/>
                        <label>
                            <input type="radio" ng-model="searchFilter.false_alarm" value="no_false">
                            {{ Lang::get('admin.reports.filters.false_alarm.exclude_false') }}
                        </label><br/>
                        <label>
                            <input type="radio" ng-model="searchFilter.false_alarm" value="only_false">
                            {{ Lang::get('admin.reports.filters.false_alarm.only_false') }}
                        </label>
                    </div>
                </form>
                <hr>
                <div ng-if="!list.length">
                    <p>{{ Lang::get('admin.reports.no_results') }}</p>
                </div>
                <table ng-if="list.length" class="table-style table-style__settings textarea-block">
                    <thead>
                    <tr>
                        <th>{{ Lang::get('admin.reports.table.triggered_at') }}</th>
                        <th>{{ Lang::get('admin.reports.table.duration') }}</th>
                        <th>{{ Lang::get('admin.reports.table.device_type') }}</th>
                        <th>{{ Lang::get('admin.reports.table.device_id') }}</th>
                        <th>{{ Lang::get('admin.reports.table.fullname') }}</th>
                        <th>{{ Lang::get('admin.reports.table.push_notifications') }}</th>
                        <th>{{ Lang::get('admin.reports.table.video_chats') }}</th>
                        <th>{{ Lang::get('admin.reports.table.download_log') }}</th>
                        <th>{{ Lang::get('admin.reports.table.download_report') }}</th>
                        <th ng-if="video_enabled()">{{ Lang::get('admin.reports.table.has_video') }}</th>
                        <th>{{ Lang::get('admin.reports.table.false_alarm') }}</th>
                        <th>{{ Lang::get('admin.reports.table.note') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="report_item in list">
                        <td><% report_item.triggered_at %></td>
                        <td><% report_item.duration %></td>
                        <td><% report_item.device_type %></td>
                        <td><% report_item.device_id %></td>
                        <td><% report_item.fullname %></td>
                        <td><% report_item.push_notifications %></td>
                        <td><% report_item.video_chats %></td>
                        <td><a href="<% report_item.log_download_link %>">{{ Lang::get('admin.reports.table.download_csv') }}</a></td>
                        <td><a href="<% report_item.report_download_link %>">{{ Lang::get('admin.reports.table.download_pdf') }}</a></td>
                        <td ng-if="video_enabled()">
                            <a ng-if="report_item.video_link" href="" class="tooltip" copy-to-clipboard="file:///var/www/<% report_item.video_link %>">
                                {{ Lang::get('admin.reports.table.yes') }}
                                <span class="tooltip-text">
                                    {{ Lang::get('admin.reports.table.video_tooltip') }}
                                </span>
                            </a>
                        </td>
                        <td><span editable-checkbox="report_item.false_alarm" e-name="false_alarm" e-title="" e-form="rowform">
                                <% report_item.false_alarm && "{{ Lang::get('admin.reports.table.yes') }}" || "{{ Lang::get('admin.reports.table.no') }}" %>
                            </span></td>
                        <td><span e-class="textarea-block__input-text -medium" editable-text="report_item.note" e-name="note" e-form="rowform"><% report_item.note || '{{ Lang::get('system.contents.defaults.none') }}' %></span></td>
                        <td class="-cell-options">
                                <form editable-form name="rowform" onbeforesave="saveItem(report_item, $index)" oncancel="cancel(report_item.id, $index)" ng-show="rowform.$visible" class="form-buttons form-inline" shown="inserted == report_item">
                                    <div class="buttons">
                                        <button type="submit" class="button -settings -check"><% '{{ Lang::get('system.save') }}' %></button>
                                        <button type="button" class="button -settings -delete" ng-click="rowform.$cancel();"><% '{{ Lang::get('system.cancel') }}' %></button>
                                    </div>
                                </form>
                                <div class="buttons" ng-show="!rowform.$visible">
                                  <a href="#" class="button -settings -edit" ng-click="rowform.$show()"><% '{{ Lang::get('system.edit') }}' %></a>
                                    <a href="#" class="button -settings -drop" ng-click="removeItem(report_item.id, $index)"><% '{{ Lang::get('system.remove') }}' %></a>
                                </div>
                            </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
