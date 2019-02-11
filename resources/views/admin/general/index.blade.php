@extends('admin.base')

@section('content')
    <div class="settings text help-block">
        <div class="help-block__nav block-style">
            @include('admin.partials.tabs')
        </div>
        <div class="help-block__description">
            <h2 class="-title">{{ Lang::get('admin.general.title') }}</h2>
            <p class="-text">{{ Lang::get('admin.general.description') }}</p>
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

                {!! Form::open(array('name' => 'general-form', 'id' => 'general-form', 'action' => 'Admin\GeneralController@postSave')) !!}
                <div class="table-style__table">
                    <div class="table-style__row">
                        @include('system.general.partials.dropdown', [
                            'label' => Lang::get('admin.general.labels.timezone'),
                            'name' => 'timezone',
                            'options' => $timezones,
                            'selected' => $school->timezone
                        ])
                    </div>

                    <div class="table-style__row">
                        @include('system.general.partials.dropdown', [
                            'label' => Lang::get('admin.general.labels.language'),
                            'name' => 'locale',
                            'options' => $languages,
                            'selected' => $school->locale
                        ])
                    </div>

                    <div class="table-style__row">
                        @include('system.general.partials.dropdown', [
                            'label' => Lang::get('admin.general.labels.ordering'),
                            'name' => 'ordering',
                            'options' => $orderingOptions,
                            'selected' => $school->ordering
                        ])
                    </div>

                </div>
                {!!Form::close()!!}
            </div>
        </div>

        <div class="help-block__bottom-nav">
            <div class="buttons">
                <div class="button -submit icons submit-form" data-form="general-form">{{ Lang::get('admin.general.save') }}</div>
            </div>
        </div>
    </div>
@endsection