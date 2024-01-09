<div class="service-item">
    <div class="service-title clear-both">
        <div class="column w-55">
            <p><b>{{ __('Информация об услуге') }}</b></p>
        </div>
        <div class="column w-10 text-center"><p><b>{{ __('Кол-во') }}</b></p></div>
        <div class="column w-17 text-right"><p><b>{{ __('Цена') }}</b></p></div>
        <div class="column w-17 text-right"><p><b>{{ __('Итого') }}</b></p></div>
    </div>
    @if(count($service->guests) > 0)
        @include('invoice._partials.guests')
    @endif

    @include('invoice._partials.details')
</div>
