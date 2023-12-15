<tr class="padding-top">
    <td style="width: 35%">{{ __('Условия отмены:') }}</td>
</tr>

<tr>
    <td style="width: 65%">
        {{ __('Отмена без штрафа до :date', ['date' => $service->cancelConditions?->cancelNoFeeDate ? Format::date($service->cancelConditions?->cancelNoFeeDate) : '-']) }}
    </td>
</tr>

@if($service->cancelConditions)
    {{ __('Неявка: :percent% :type', ['percent' => $service->cancelConditions->noCheckInMarkup, 'type' => $service->cancelConditions->noCheckInMarkupType]) }}

    @foreach($service->cancelConditions->dailyMarkups ?? [] as $dailyMarkup)
        <tr>
            <td>
                {{ __('За :days :dimension', ['days' => $dailyMarkup->daysCount, 'dimension' => trans_choice('[1] день|[2,4] дня|[5,*] дней', $dailyMarkup->daysCount)]) }}:

                @if($dailyMarkup->valueType === \Sdk\Shared\Enum\Pricing\ValueTypeEnum::PERCENT)
                    {{ $dailyMarkup->value }}% {{ $dailyMarkup->markupType }}
                @else
                    {{ $dailyMarkup->value }} {{$service->price->currency}}
                @endif
            </td>
        </tr>
    @endforeach
@endif
