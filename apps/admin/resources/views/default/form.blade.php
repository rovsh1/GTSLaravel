@extends('layouts/main')

@section('content')
    <div class="card">
        <h5 class="card-header">
            {{ $title }}

            {!! Layout::actions() !!}
        </h5>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <form action="{{ $form->action }}" method="{{ strtoupper($form->method) === 'GET' ? 'GET' : 'POST' }}" enctype="multipart/form-data">
                        <div class="form-group">{!! $form !!}</div>

                        <div class="form-buttons">
                            @if(isset($cancelUrl))
                                <a href="{{ $cancelUrl }}" type="submit" class="btn btn-secondary">Отмена</a>
                            @endif
                            <button type="submit" class="btn btn-primary">{{ $submitText ?? 'Сохранить' }}</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
