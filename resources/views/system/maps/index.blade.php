@extends('system/base')

@section('content')
    <div class="container__wrapper -help" ng-controller="MapsController">
        <div class="settings text help-block">
            <div class="help-block__description">
                <h2 class="-title">{{ Lang::get('system.contents.maps.title') }}</h2>
                <p class="-text">{{ Lang::get('system.contents.maps.description') }}</p>
            </div>

            <div class="help-block__container">
                <div class="custom">
                    <div class="buttons" style="margin-bottom: 25px;">
                        <a class="button -submit icons -sync" ng-click="syncMaps()">{{ Lang::get('system.contents.maps.sync') }}</a>
                    </div>
                    <ul class="building-list">
                        <li ng-class="{'-empty': !campus.buildings.length}" ng-repeat="campus in list">
                            <div class="building-list__item icons -list-expand" toggle-class>
                                <span class="-text"><% campus.campus_name %></span>
                                <span class="-icon"></span>
                            </div>
                            <ul class="-sub" ng-if="campus.buildings">
                                <li ng-repeat="building in campus.buildings">
                                    <div class="building-list__item icons -list-expand" toggle-class>
                                        <span class="-text"><% building.building_name %></span>
                                        <span class="-icon"></span>
                                    </div>
                                    <ul class="-sub" ng-if="building.floors">
                                        <li ng-repeat="floor in building.floors">
                                            <div class="building-list__item icons -list-expand">
                                                <span class="-name"><% floor.floor_name %></span>
                                                <div class="button -settings -preview" ng-if="floor.image" ng-click="previewMap(floor.id)">Preview</div>
                                            </div>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection