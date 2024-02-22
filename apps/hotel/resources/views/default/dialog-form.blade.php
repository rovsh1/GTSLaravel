<form action="{{ $form->action }}" method="{{ strtoupper($form->method) === 'GET' ? 'GET' : 'POST' }}" data-title="{{ $title ?? '' }}" enctype="multipart/form-data">
    {!! $form !!}
</form>
