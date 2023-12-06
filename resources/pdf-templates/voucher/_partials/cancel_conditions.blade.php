<tr class="padding-top">
    <td style="width: 35%">Условия отмены:</td>
</tr>

<tr>
    <td style="width: 65%">
        Отмена без штрафа
        до {{ $service->cancelConditions?->cancelNoFeeDate ? Format::date($service->cancelConditions?->cancelNoFeeDate) : '-' }}
    </td>
</tr>

@if($service->cancelConditions)
    Неявка: {{ $service->cancelConditions->noCheckInMarkup }}
    % {{ $service->cancelConditions->noCheckInMarkupType }}

    @foreach($service->cancelConditions->dailyMarkups ?? [] as $dailyMarkup)
        <tr>
            <td>
                За {{ $dailyMarkup->daysCount }} {{ trans_choice('[1] день|[2,4] дня|[5,*] дней', $dailyMarkup->daysCount) }}
                :

                @if($dailyMarkup->valueType === \Sdk\Shared\Enum\Pricing\ValueTypeEnum::PERCENT)
                    {{ $dailyMarkup->value }}% {{ $dailyMarkup->markupType }}
                @else
                    {{ $dailyMarkup->value }} {{$service->price->currency}}
                @endif
            </td>
        </tr>
    @endforeach
@endif
