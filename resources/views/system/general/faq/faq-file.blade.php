<div class="help-block__description">
    <p class="-text">{{ Lang::get('quickhelp-system.description') }}</p>
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

        {!! Form::open(['name' => 'help-file-form', 'id' => 'file-form', 'action' => 'System\General\MainController@postSaveFile', 'enctype' =>'multipart/form-data']) !!}
        <div class="table-style__table">
            <div class="table-style__row">
                <div class="table-style__cell -label">
                    {{ Lang::get('quickhelp-system.file_label') }}
                </div>
                <div class="table-style__cell -form-element">
                    {!! Form::file('file', ['id' => 'file-input', 'style' => 'visibility:hidden; display: none;']) !!}

                    <span id="selected-file">
                    @if(!empty($file))
                        {{ $file->name }}
                    @endif
                    </span>
                    <a href="#" onclick="document.getElementById('file-input').click()"
                       class="button -submit -browse icons">{{ Lang::get('quickhelp-system.browse') }}</a>
                </div>
            </div>
            <div class="table-style__row">
                <div class="table-style__cell -label">
                    {{ Lang::get('quickhelp-system.police_file_label') }}
                </div>
                <div class="table-style__cell -form-element">
                    {!! Form::file('file2', ['id' => 'file-input2', 'style' => 'visibility:hidden; display: none;']) !!}

                    <span id="selected-file2">
                    @if(!empty($file))
                            {!! $file->police_name !!}
                        @endif
                    </span>
                    <a href="#" onclick="document.getElementById('file-input2').click()" class="button -file-upload -submit -browse icons">{{ Lang::get('quickhelp-system.browse') }}</a>
                </div>
            </div>

            <div class="table-style__row">
                <div class="table-style__cell -label">
                    {{ Lang::get('quickhelp-system.file_description') }}
                </div>
                <div class="table-style__cell -form-element">
                    <div class="textarea-block__field">
                        {!!Form::textarea('description', $file->description, ['class' => 'textarea-block__textarea', 'placeholder' => Lang::get('quickhelp-system.enter_text')])!!}
                    </div>
                </div>
            </div>
        </div>

        {!! Form::close() !!}
    </div>
</div>

<div class="help-block__bottom-nav mb">
    <div class="buttons">
        <div class="button -submit icons submit-form" data-form="file-form">{{ Lang::get('quickhelp-system.save') }}</div>
    </div>
</div>