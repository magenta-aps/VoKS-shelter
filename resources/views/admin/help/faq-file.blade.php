<div class="help-block__description">
    <p class="-text">{{ Lang::get('quickhelp-admin.description') }}</p>
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

        {!! Form::open(['name' => 'help-file-form', 'id' => 'file-form', 'action' => 'Admin\HelpController@postSaveFile', 'enctype' =>'multipart/form-data']) !!}
        {!! Form::hidden('id', $file->id) !!}

        <div class="table-style__table" style="float: left; width: 50%;">
            <div class="table-style__row">
                <div class="table-style__cell -label">
                    {{ Lang::get('quickhelp-admin.file_label') }}
                </div>
                <div class="table-style__cell -form-element">
                    {!! Form::file('file', ['id' => 'file-input', 'style' => 'visibility:hidden; display: none;']) !!}

                    <span id="selected-file">
                    @if($file->name)
                        {!! $file->name !!}
                    @endif
                    </span>
                    <a href="#" onclick="document.getElementById('file-input').click()" class="button -file-upload -submit -browse icons">{{ Lang::get('quickhelp-admin.browse') }}</a>
                </div>
            </div>
            <div class="table-style__row">
                <div class="table-style__cell -label">
                    {{ Lang::get('quickhelp-admin.police_file_label') }}
                </div>
                <div class="table-style__cell -form-element">
                    {!! Form::file('file2', ['id' => 'file-input2', 'style' => 'visibility:hidden; display: none;']) !!}

                    <span id="selected-file2">
                    @if($file->police_name)
                            {!! $file->police_name !!}
                        @endif
                    </span>
                    <a href="#" onclick="document.getElementById('file-input2').click()" class="button -file-upload -submit -browse icons">{{ Lang::get('quickhelp-admin.browse') }}</a>
                </div>
            </div>

            <div class="table-style__row">
                <div class="table-style__cell -label">
                    {{ Lang::get('quickhelp-admin.file_description') }}
                </div>
                <div class="table-style__cell -form-element">
                    <div class="textarea-block__field">
                        {!!Form::textarea('description', $file->description, ['class' => 'textarea-block__textarea', 'placeholder' => Lang::get('quickhelp-admin.enter_text')])!!}
                    </div>
                </div>
            </div>
        </div>
        <div class="-defaults" style="float: right; font-weight: bold; text-align: left; width: 40%">
            <a href="/download/faq-file/default" style="color: #111;">{{ Lang::get('quickhelp-admin.download') }}</a><br /><br />
            <a href="/download/faq-file/default/1" style="color: #111;">{{ Lang::get('quickhelp-admin.download_police') }}</a><br /><br />
            <p>{{ $defaults->description }}</p>
        </div>

        {!! Form::close() !!}
    </div>
</div>

<div class="help-block__bottom-nav mb">
    <div class="buttons">
        <div class="button -submit icons submit-form" data-form="file-form">{{ Lang::get('quickhelp-admin.save') }}</div>
    </div>
</div>