<div class="service-details">
    <p>{{ __('Условия отмены:') }}</p>

    <p>
        {{ __('Отмена без штрафа до :date', ['date' => $service->cancelConditions?->cancelNoFeeDate ? Format::date($service->cancelConditions?->cancelNoFeeDate) : '-']) }}
    </p>

    @if($service->cancelConditions)
        <p>{{ __('Неявка: :percent% :type', ['percent' => $service->cancelConditions->noCheckInMarkup, 'type' => $service->cancelConditions->noCheckInMarkupType]) }}</p>

        @foreach($service->cancelConditions->dailyMarkups ?? [] as $dailyMarkup)
            <p>
                {{ __('За :days :dimension', ['days' => $dailyMarkup->daysCount, 'dimension' => trans_choice('[1] день|[2,4] дня|[5,*] дней', $dailyMarkup->daysCount)]) }}
                :

                @if($dailyMarkup->valueType === \Sdk\Shared\Enum\Pricing\ValueTypeEnum::PERCENT)
                    {{ $dailyMarkup->value }}% {{ $dailyMarkup->markupType }}
                @else
                    {{ $dailyMarkup->value }} {{$service->price->currency}}
                @endif
            </p>
        @endforeach
    @endif
</div>
