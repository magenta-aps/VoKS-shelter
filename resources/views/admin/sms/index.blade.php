@extends('admin.base')

@section('content')
    <div class="settings text help-block" ng-controller="SmsController">
        <div class="help-block__nav block-style">
            @include('admin.partials.tabs')
        </div>
        <div class="help-block__description">
            <h2 class="-title">{{ Lang::get('admin.sms.title') }}</h2>
            <p class="-text">{{ Lang::get('admin.sms.description') }}</p>
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

            {!!Form::open(array('name' => 'sms-form', 'id' => 'sms-form', 'action' => 'Admin\SmsController@postSaveSms'))!!}
            {!! Form::hidden('id', $sms->id) !!}
            <script type="text/javascript">
                var model = {!! $sms !!};
            </script>
            <div class="table-style__table" style="width: 100%;">
                <div class="table-style__row">
                    <div class="column1">
                        <div class="table-style__cell -label">
                            {{ Lang::get('admin.sms.trigger') }}
                        </div>
                        <div class="table-style__cell -form-element">
                            <div class="textarea-block__field">
                                {!! Form::textarea('initial_message', $sms->initial_message, [
                                'class' => 'textarea-block__textarea',
                                'maxlength' => 160,
                                'ng-model' => 'model.initial_message',
                                'ng-trim' => 'false'
                                ]) !!}
                                <div class="textarea-block__symbols-left">{{ Lang::get('admin.sms.symbols') }} <span
                                            class="-count -low"><% counter(model.initial_message) %></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column2">
                        <strong>{{ Lang::get('admin.sms.default') }}</strong>
                        <p>{{ $default->initial_message }}</p>
                    </div>
                </div>

                <div class="table-style__row">
                    <div class="column1">
                        <div class="table-style__cell -label">
                            {{ Lang::get('admin.sms.information') }}
                        </div>
                        <div class="table-style__cell -form-element">
                            <div class="textarea-block__field">
                                {!! Form::textarea('crisis_team_message', $sms->crisis_team_message, [
                                'class' => 'textarea-block__textarea',
                                'maxlength' => 160,
                                'ng-model' => 'model.crisis_team_message',
                                'ng-trim' => 'false'
                                ]) !!}
                                <div class="textarea-block__symbols-left">{{ Lang::get('admin.sms.symbols') }} <span
                                            class="-count -low"><% counter(model.crisis_team_message) %></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column2">
                        <strong>{{ Lang::get('admin.sms.default') }}</strong>
                        <p>{{ $default->crisis_team_message }}</p>
                    </div>
                </div>
            </div>

            {!! Form::close() !!}
            </div>
        </div>

        <div class="help-block__bottom-nav">
            <div class="buttons">
                <div class="button -submit icons submit-form" data-form="sms-form">{{ Lang::get('admin.save') }} </div>
            </div>
        </div>
    </div>
@endsection