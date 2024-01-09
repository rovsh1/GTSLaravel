<div class="service-item">
    <div class="service-title clear-both">
        <div class="column w-55">
            <b>{{ __('Информация об услуге') }}</b>
        </div>
        <div class="column w-10 text-center"><b>{{ __('Кол-во') }}</b></div>
        <div class="column w-17 text-right"><b>{{ __('Цена') }}</b></div>
        <div class="column w-17 text-right"><b>{{ __('Итого') }}</b></div>
    </div>
    @if(count($service->guests) > 0)
        @include('invoice._partials.guests')
    @endif

    @include('invoice._partials.details')
</div>
