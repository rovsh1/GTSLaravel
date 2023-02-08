<form method="{{ $form->method }}" enctype="multipart/form-data">
    {!! $csrf !!}
    {!! $errors !!}
    <div class="form-fields">{!! $elements !!}</div>
    <div class="form-buttons">
        <button type="submit">Submit</button>
    </div>
</form>
