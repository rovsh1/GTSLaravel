<tr>
    <td class="first last"></td>
    <td class="first last" colspan="3">
        <table>
            <tbody>
            <tr>
                <td style="width: 35%">Условия отмены:</td>
                <td style="width: 65%">
                    Отмена без штрафа
                    до {{ $booking->cancelConditions?->cancelNoFeeDate ? Format::date($booking->cancelConditions?->cancelNoFeeDate) : '-' }}
                    <br/>

                    @if($booking->cancelConditions)
                        Неявка: {{ $booking->cancelConditions->noCheckInMarkup }}
                        % {{ $booking->cancelConditions->noCheckInMarkupType }}
                        <br/>

                        @foreach($booking->cancelConditions->dailyMarkups ?? [] as $dailyMarkup)
                            За {{ $dailyMarkup->daysCount }} {{ trans_choice('[1] день|[2,4] дня|[5,*] дней', $dailyMarkup->daysCount) }}:

                            @if($dailyMarkup->valueType === \Sdk\Shared\Enum\Pricing\ValueTypeEnum::PERCENT)
                                {{ $dailyMarkup->value }}% {{ $dailyMarkup->markupType }}
                            @else
                                {{ $dailyMarkup->value }} {{$booking->price->currency}}
                            @endif
                            <br/>
                        @endforeach
                    @endif
                </td>
            </tr>
            </tbody>
        </table>
    </td>
    <td class="first last"></td>
</tr>
