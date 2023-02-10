<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <form method="{{ $form->method }}" enctype="multipart/form-data">
                    {!! $csrf !!}
                    {!! $errors !!}
                    <div class="form-fields">{!! $elements !!}</div>
                    <div class="form-buttons">
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
