<tr>
    <td class="first last"></td>
    <td class="first last" colspan="3">
        <table>
            <tbody>
            <tr>
                <td style="width: 35%">Условия отмены:</td>
                <td style="width: 65%">
                    {{--                    Отмена без штрафа--}}
                    {{--                    до {{ $cancelConditions?->cancelNoFeeDate ? Format::date($cancelConditions?->cancelNoFeeDate) : '-' }}--}}
                    {{--                    <br/>--}}

                    @if($booking->cancelConditions)
                        Неявка: {{ $booking->cancelConditions->noCheckInMarkup }}% {{ $booking->cancelConditions->noCheckInMarkupType }}
                        <br />

                        @foreach($booking->cancelConditions->dailyMarkups ?? [] as $dailyMarkup)
                            За {{ $dailyMarkup->daysCount }} {{ trans_choice('[1] день|[2,4] дня|[5,*] дней', $dailyMarkup->daysCount) }}: {{ $dailyMarkup->percent }}% {{ $dailyMarkup->markupType }}
                            <br />
                        @endforeach
                    @endif
                </td>
            </tr>
            {cancelPeriods}
            </tbody>
        </table>
    </td>
    <td class="first last"></td>
</tr>
