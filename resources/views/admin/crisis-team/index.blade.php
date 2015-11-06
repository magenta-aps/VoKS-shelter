@extends('admin.base')

@section('content')
    <div class="settings text help-block">

        <div class="help-block__nav block-style">
            @include('admin.partials.tabs')
        </div>

        <div class="help-block__description">
            <h2 class="-title">{{ Lang::get('admin.team.title') }}</h2>
            <p class="-text">{{ Lang::get('admin.team.description') }}</p>
        </div>

        <div class="help-block__container" ng-controller="CrisisTeamController">
            <div class="custom">
                <a class="button -submit icons" ng-click="syncItems()">
                    {{ Lang::get('admin.team.button.sync') }}
                </a>
                <table class="table-style table-style__settings textarea-block">
                    <thead>
                    <tr>
                        <th>{{ Lang::get('admin.team.table.name') }}</th>
                        <th>{{ Lang::get('admin.team.table.email') }}</th>
                        <th>{{ Lang::get('admin.team.table.phone') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->email }}</td>
                            <td>{{ $item->phone }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection