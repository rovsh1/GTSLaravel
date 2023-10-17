@extends('layouts.main')

@section('styles')
    @vite('resources/views/default/form/form.scss')
@endsection

@section('scripts')
    @vite('resources/views/supplier/service/form/form.ts')
@endsection

@section('head-end')
    <script>
      window['view-initial-data-supplier-service-form'] = {{ Js::from([
            'TRANSFER_TO_AIRPORT' => \Module\Shared\Enum\ServiceTypeEnum::TRANSFER_TO_AIRPORT,
            'TRANSFER_FROM_AIRPORT' => \Module\Shared\Enum\ServiceTypeEnum::TRANSFER_FROM_AIRPORT,
            'CAR_RENT_WITH_DRIVER' => \Module\Shared\Enum\ServiceTypeEnum::CAR_RENT_WITH_DRIVER,
            'DAY_CAR_TRIP' => \Module\Shared\Enum\ServiceTypeEnum::DAY_CAR_TRIP,
            'TRANSFER_FROM_RAILWAY' => \Module\Shared\Enum\ServiceTypeEnum::TRANSFER_FROM_RAILWAY,
            'TRANSFER_TO_RAILWAY' => \Module\Shared\Enum\ServiceTypeEnum::TRANSFER_TO_RAILWAY,
            'CIP_IN_AIRPORT' => \Module\Shared\Enum\ServiceTypeEnum::CIP_IN_AIRPORT,
            'OTHER' => \Module\Shared\Enum\ServiceTypeEnum::OTHER,
            'INTERCITY_TRANSFER' => \Module\Shared\Enum\ServiceTypeEnum::INTERCITY_TRANSFER,
        ]) }}
    </script>
@endsection

@section('content')
    {!! ContentTitle::default() !!}

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

