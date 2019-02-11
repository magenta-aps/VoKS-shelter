@extends('system.general.base')

@section('tab-content')
    <div class="settings text help-block">
        <div class="help-block__nav block-style">
            @include('system.general.partials.tabs')
        </div>

        @include('system.general.faq.faq-file')
        @include('system.general.faq.faq-list')
    </div>
@endsection