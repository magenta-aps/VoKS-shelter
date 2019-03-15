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

                    @if ( $use_non_gps )
                        <a class="button -submit icons -sync" ng-click="syncMaps()">{{ Lang::get('system.contents.school.sync') }}</a>
                    @else
                        <a class="button -submit icons -add" ng-click="addItem()">{{ Lang::get('system.contents.school.add') }}</a>
                    @endif

                    <table class="table-style table-style__settings textarea-block">
                        <thead>
                        <tr>
                            <th>{{ Lang::get('system.contents.school.table.name') }}</th>
                            <th>{{ Lang::get('system.contents.school.table.mac') }}</th>
                            <th>{{ Lang::get('system.contents.school.table.ip') }}</th>
                          @if ( !$use_non_gps )
                            <th>{{ Lang::get('system.contents.school.table.url') }}</th>
                            <th>{{ Lang::get('system.contents.school.table.police_number') }}</th>
                            <th>{{ Lang::get('system.contents.school.table.use_gps') }}</th>
                            <th>{{ Lang::get('system.contents.school.table.display') }}</th>
                            <th>{{ Lang::get('system.contents.school.table.public') }}</th>
                          @endif
	                        @if ( $ad_enabled )
                            <th>{{ Lang::get('system.contents.school.table.ad') }}</th>
	                        @endif
                          @if ( $phone_provider )
                              <th>{{ Lang::get('system.contents.school.table.phone') }}</th>
                          @endif
                          @if ( $controllers_enabled )
                            <th>{{ Lang::get('system.contents.school.table.controller') }}</th>
                          @endif
                          <th class="-cell-options">{{ Lang::get('system.contents.school.table.options') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr ng-repeat="school in list" ng-class="{'-bold': school.id == {{ $shelterId }} }">
                            <td><span e-class="textarea-block__input-text -medium" onbeforesave="validateField($data)" editable-text="school.name" e-name="name" e-form="rowform" e-required><% school.name || '{{ Lang::get('system.contents.defaults.none') }}' %></span></td>
                            <td><span e-class="textarea-block__input-text -medium" editable-text="school.mac_address" e-name="mac_address" e-form="rowform"><% school.mac_address || '{{ Lang::get('system.contents.defaults.none') }}' %></span></td>
                            <td><span e-class="textarea-block__input-text -medium" onbeforesave="validateIp(school.id, $data)" editable-text="school.ip_address" e-name="ip_address" e-form="rowform" e-required><% school.ip_address || '{{ Lang::get('system.contents.defaults.none') }}' %></span></td>
                            @if ( !$use_non_gps )
                              <td><span e-class="textarea-block__input-text -medium" editable-text="school.url" e-name="url" e-form="rowform" e-required><% school.url || '{{ Lang::get('system.contents.defaults.none') }}' %></span></td>
                              <td><span e-class="textarea-block__input-text -medium" editable-text="school.police_number" e-name="police_number" e-form="rowform" e-required><% school.police_number || '{{ Lang::get('system.contents.defaults.none') }}' %></span></td>
                              <td><span e-class="textarea-block__input-text -medium" editable-text="school.use_gps" e-name="use_gps" e-form="rowform" e-required><% school.use_gps || '{{ Lang::get('system.contents.defaults.none') }}' %></span></td>
                              <td><span e-class="textarea-block__input-text -medium" editable-text="school.display" e-name="display" e-form="rowform" e-required><% school.display || '{{ Lang::get('system.contents.defaults.none') }}' %></span></td>
                              <td><span e-class="textarea-block__input-text -medium" editable-text="school.public" e-name="public" e-form="rowform" e-required><% school.public || '{{ Lang::get('system.contents.defaults.none') }}' %></span></td>
                            @endif
                            @if ( $ad_enabled )
                                <td><span e-class="textarea-block__input-text -medium" editable-text="school.ad_id" e-name="ad_id" e-form="rowform"><% school.ad_id || '{{ Lang::get('system.contents.defaults.none') }}' %></span></td>
                            @endif
                            @if ( $phone_provider )
                                <td><span e-class="textarea-block__input-text -medium" onshow="updatePhoneSystemIdList(school.id)" onbeforesave="validatePhoneSystemId(school.id, $data)" editable-select="school.phone_system_id" e-name="phone_system_id" e-form="rowform" e-required e-ng-options="ids.id as ids.name for ids in model.phoneSystemIds">
                                        <% model.phoneSystemIds[school.phone_system_id].name || '{{ Lang::get('system.contents.defaults.none') }}' %>
                                </span></td>
                            @endif
                            @if ( $controllers_enabled )
                                <td><span e-class="textarea-block__input-text -medium" editable-text="school.controller_url" e-name="controller_url" e-form="rowform" e-required><% school.controller_url || '{{ Lang::get('system.contents.defaults.none') }}' %></span></td>
                            @endif
                            <td class="-cell-options">
                                <form editable-form name="rowform" onbeforesave="saveItem(school, $index)" oncancel="cancel(school.id, $index)" ng-show="rowform.$visible" class="form-buttons form-inline" shown="inserted == school">
                                    <div class="buttons">
                                        <button type="submit" class="button -settings -check"><% '{{ Lang::get('system.save') }}' %></button>
                                        <button type="button" class="button -settings -delete" ng-click="rowform.$cancel();"><% '{{ Lang::get('system.cancel') }}' %></button>
                                    </div>
                                </form>
                                <div class="buttons" ng-show="!rowform.$visible">
                                  <a href="#" class="button -settings -edit" ng-click="rowform.$show()"><% '{{ Lang::get('system.edit') }}' %></a>
	                                @if ( !$use_non_gps )
                                    <a href="#" class="button -settings -drop" ng-click="removeItem(school.id, $index)"><% '{{ Lang::get('system.remove') }}' %></a>
                                  @endif
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