<div class="table-style__cell -label">
    {{ $label }}
</div>
<div class="table-style__cell -form-element">
    {!! Form::select($name, $options, $selected, ['placeholder' => 'Select option', 'class' => 'js__chosen-select']) !!}
</div>