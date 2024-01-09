<div class="service-item">
    <div class="service-title">
        <b>{{ __('Информация об услуге') }}</b>
    </div>
    @if(count($service->guests) > 0)
        @include('voucher._partials.guests')
    @endif

    <div class="service-details clear-both">
        <div class="column service-details__left">
            <p><b>{{ $service->title }}</b></p>
            @foreach($service->detailOptions as $index => $detailOption)
                <p>{{ $detailOption->label }}: {{ $detailOption->getHumanValue() }} </p>
            @endforeach
        </div>
        <div class="column service-details__right">
            <p>Статус: {{ $service->status }}</p>
            <p>Номер подтверждения:</p>
        </div>
    </div>

    @include('voucher._partials.cancel_conditions')
</div>
