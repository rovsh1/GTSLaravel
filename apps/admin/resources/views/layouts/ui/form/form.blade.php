<form action="{{ $form->action }}" method="{{ strtoupper($form->method) === 'GET' ? 'GET' : 'POST' }}" enctype="multipart/form-data">
    {!! $errors !!}
    @method(strtoupper($form->method))
    <div class="form-fields">{!! $elements !!}</div>
    <div class="form-buttons">
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </div>
</form>
