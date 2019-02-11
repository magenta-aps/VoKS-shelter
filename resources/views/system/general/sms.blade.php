@extends('system.general.base')

@section('tab-content')
    <div class="settings text help-block" ng-controller="SmsController">
        <div class="help-block__nav block-style">
            @include('system.general.partials.tabs')
        </div>
        <div class="help-block__description">
            <h2 class="-title">{{ Lang::get('system.contents.sms.title') }}</h2>
            <p class="-text">{{ Lang::get('system.contents.sms.description') }}</p>
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
            </div>

            {!!Form::open(array('name' => 'sms-form', 'id' => 'sms-form', 'action' => 'System\General\MainController@postSaveSms'))!!}
            <script type="text/javascript">
                var model = {!! $sms !!};
            </script>
            <div class="table-style__table">
                <div class="table-style__row">
                    <div class="table-style__cell -label">
                        {{ Lang::get('system.contents.sms.trigger') }}
                    </div>
                    <div class="table-style__cell -form-element">
                        <div class="textarea-block__field">
                            {!! Form::textarea('initial_message', $sms->initial_message, [
                            'class' => 'textarea-block__textarea',
                            'maxlength' => 160,
                            'ng-model' => 'model.initial_message',
                            'ng-trim' => 'false'
                            ]) !!}
                            <div class="textarea-block__symbols-left">{{ Lang::get('system.contents.sms.symbols') }} <span
                                        class="-count -low"><% counter(model.initial_message) %></span>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="table-style__row">
                    <div class="table-style__cell -label">
                        {{ Lang::get('system.contents.sms.information') }}
                    </div>
                    <div class="table-style__cell -form-element">
                        <div class="textarea-block__field">
                            {!! Form::textarea('crisis_team_message', $sms->crisis_team_message, [
                            'class' => 'textarea-block__textarea',
                            'maxlength' => 160,
                            'ng-model' => 'model.crisis_team_message',
                            'ng-trim' => 'false'
                            ]) !!}
                            <div class="textarea-block__symbols-left">{{ Lang::get('system.contents.sms.symbols') }} <span
                                        class="-count -low"><% counter(model.crisis_team_message) %></span></div>
                        </div>
                    </div>
                </div>
            </div>

            {!! Form::close() !!}
        </div>

        <div class="help-block__bottom-nav">
            <div class="buttons">
                <div class="button -submit icons submit-form" data-form="sms-form">{{ Lang::get('system.save') }}</div>
            </div>
        </div>
    </div>
@endsection