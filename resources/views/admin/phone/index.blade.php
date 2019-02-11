@extends('admin.base')

@section('content')
    <div class="settings text help-block">
        <div class="help-block__nav block-style">
            @include('admin.partials.tabs')
        </div>

        {{-- Alarm trigger --}}
        <div class="help-block__description">
            <h2 class="-title">{{ Lang::get('admin/phone.alarm.title') }}</h2>
            <p class="-text">{{ Lang::get('admin/phone.alarm.desc') }}</p>
        </div>
        <div class="help-block__container">
            <div class="custom">
                {!! Form::open(array('name' => 'alarm-trigger', 'id' => 'alarm-trigger', 'action' => 'Admin\PhoneSystemController@postSaveAlarm')) !!}
                <div class="table-style__table">
                    <div class="table-style__row">
                        @include('system.general.partials.dropdown', [
                            'label' => Lang::get('admin/phone.alarm.field.group.label'),
                            'name' => 'phone_system_group_id',
                            'options' => $alarm['groups'],
                            'selected' => $alarm['group_id']
                        ])
                        @if($errors->first('phone_system_group_id'))
                        <div>{{ $errors->first('phone_system_group_id') }}</div>
                        @endif
                    </div>
                    <div class="table-style__row">
                        @include('system.general.partials.dropdown', [
                            'label' => Lang::get('admin/phone.alarm.field.media.label'),
                            'name' => 'phone_system_media_id',
                            'options' => $alarm['media'],
                            'selected' => $alarm['media_id']
                        ])
                        @if($errors->first('phone_system_media_id'))
                        <div>{{ $errors->first('phone_system_media_id') }}</div>
                        @endif
                    </div>
                    <div class="table-style__row">
                        @include('system.general.partials.dropdown', [
                            'label' => Lang::get('admin/phone.alarm.field.interrupt.label'),
                            'name' => 'phone_system_interrupt_id',
                            'options' => $alarm['interrupt'],
                            'selected' => $alarm['interrupt_id']
                        ])
                        @if($errors->first('phone_system_interrupt_id'))
                            <div>{{ $errors->first('phone_system_interrupt_id') }}</div>
                        @endif
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
        <div class="help-block__bottom-nav">
            <div class="buttons">
                <div class="button -submit icons submit-form" data-form="alarm-trigger">{{ Lang::get('admin/phone.alarm.field.save.label') }} </div>
            </div>
        </div>

        {{-- Live broadcast --}}
        <div class="help-block__description">
            <h2 class="-title">{{ Lang::get('admin/phone.broadcast.title') }}</h2>
            <p class="-text">{{ Lang::get('admin/phone.broadcast.desc') }}</p>
        </div>
        <div class="help-block__container">
            <div class="custom">
                {!! Form::open(array('name' => 'live-broadcast', 'id' => 'live-broadcast', 'action' => 'Admin\PhoneSystemController@postSaveBroadcast')) !!}
                <div class="table-style__table">
                    <div class="table-style__row">
                        <div class="table-style__cell -label">
                            {{ Lang::get('admin/phone.broadcast.field.number.label') }}
                        </div>
                        <div class="table-style__cell -form-element">
                            {!! Form::text('phone_system_number', $broadcast['number'], array('class' => 'textarea-block__input-text', 'required')) !!}
                        </div>
                        @if($errors->first('phone_system_number'))
                            <div>{{ $errors->first('phone_system_number') }}</div>
                        @endif
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
        <div class="help-block__bottom-nav">
            <div class="buttons">
                <div class="button -submit icons submit-form" data-form="live-broadcast">{{ Lang::get('admin/phone.broadcast.field.save.label') }} </div>
            </div>
        </div>

    </div>
@endsection