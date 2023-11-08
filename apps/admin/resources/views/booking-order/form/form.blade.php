@extends('layouts.main')

@section('styles')
    @vite('resources/views/default/form/form.scss')
@endsection

@section('scripts')
    @vite('resources/views/booking-order/form/form.ts')
@endsection

@section('head-end')
    <script>
      window['view-initial-data-booking-order'] = {{ Js::from([
            'orderId' => $model->id ?? null,
        ]) }}
    </script>
@endsection

@section('content')
    <x-ui.content-title/>

    <div class="content-body">
        <div class="card card-form">
            <div class="card-body">
                <form action="{{ $form->action }}" method="{{ strtoupper($form->method) === 'GET' ? 'GET' : 'POST' }}" enctype="multipart/form-data">
                    <div class="form-group">{!! $form !!}</div>

                    <div class="form-buttons">
                        <button type="submit" class="btn btn-primary">{{ $submitText ?? 'Сохранить' }}</button>
                        @if(isset($cancelUrl))
                            <a href="{{ $cancelUrl }}" class="btn btn-cancel">Отмена</a>
                        @endif
                        <div class="spacer"></div>
                        <x-form.delete-button :url="$deleteUrl ?? null"/>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
