@extends('admin.base')

@section('content')
    <div class="settings text help-block" ng-controller="HelpController">
        <div class="help-block__nav block-style">
            @include('admin.partials.tabs')
        </div>

        @include('admin.help.faq-file')
        @include('admin.help.faq-list')
    </div>
@endsection