@extends('system/general/base')

@section('tab-content')
    <div class="settings text help-block">
        <div class="help-block__nav block-style">
            @include('system.general.partials.tabs')
        </div>
        <div class="help-block__description">
            <h2 class="-title">{{ Lang::get('system.contents.system.title') }}</h2>
            <p class="-text">{{ Lang::get('system.contents.system.description') }}</p>
        </div>

        <div class="help-block__container">
            <div class="custom">
                @if($errors->any())
                    <ul class="list-block error__text error__message">
                        @foreach($errors->all() as $error)
                            <li class="list-block__item">{{$error}}</li>
                        @endforeach
                    </ul>
                @endif

                {!!Form::open(array('name' => 'system-form', 'id' => 'system-form', 'action' => 'System\General\MainController@postSave'))!!}

                <div class="table-style__table">
                    <div class="table-style__row">
                        @include('system.general.partials.dropdown', [
                            'label' =>  Lang::get('system.contents.system.timezone'),
                            'name' => 'timezone',
                            'options' => $timezones,
                            'selected' => $defaults->timezone
                        ])
                    </div>

                    <div class="table-style__row">
                        @include('system.general.partials.dropdown', [
                            'label' => Lang::get('system.contents.system.language'),
                            'name' => 'locale',
                            'options' => $languages,
                            'selected' => $defaults->locale
                        ])
                    </div>

                    <div class="table-style__row">
                        @include('system.general.partials.dropdown', [
                            'label' => Lang::get('system.contents.system.ordering'),
                            'name' => 'ordering',
                            'options' => $orderingOptions,
                            'selected' => $defaults->ordering
                        ])
                    </div>

                    <div class="table-style__row">
                        @include('system.general.partials.dropdown', [
                            'label' => Lang::get('system.contents.system.sms'),
                            'name' => 'sms_provider',
                            'options' => $smsProviders,
                            'selected' => $defaults->sms_provider
                        ])
                    </div>

                    <div class="table-style__row">
                        @include('system.general.partials.dropdown', [
                            'label' => Lang::get('system.contents.system.phone'),
                            'name' => 'phone_system_provider',
                            'options' => $phoneSystemProviders,
                            'selected' => $defaults->phone_system_provider
                        ])
                    </div>

                    <div class="table-style__row">
                        @include('system.general.partials.dropdown', [
                            'label' => Lang::get('system.contents.system.user'),
                            'name' => 'user_data_source',
                            'options' => $userDataSources,
                            'selected' => $defaults->user_data_source
                        ])
                    </div>

                    <div class="table-style__row">
                        @include('system.general.partials.dropdown', [
                            'label' => Lang::get('system.contents.system.device'),
                            'name' => 'client_data_source',
                            'options' => $clientDataSources,
                            'selected' => $defaults->client_data_source
                        ])
                    </div>

                    {!!Form::close()!!}
                </div>
            </div>
        </div>

        <div class="help-block__bottom-nav">
            <div class="buttons">
                <div class="button -submit icons submit-form" data-form="system-form">{{ Lang::get('system.save') }}</div>
            </div>
        </div>
    </div>
@endsection