@php
    $getHumanPeriodType = function (int $cancelPeriodType) {
        return $cancelPeriodType === Module\Hotel\Moderation\Domain\Hotel\ValueObject\MarkupSettings\CancelPeriodTypeEnum::FULL_PERIOD->value ? 'За весь период' : 'За первую ночь';
    };
@endphp

<div class="position-relative mt-3 rounded shadow-lg p-4 flex-grow-1">
    <div class="control-panel-section-title">
        <span><h6 class="mb-0">Условия отмены</h6></span>
    </div>
    <hr>
    <table class="table-params">
        <tbody>
        <tr>
            <th>Отмена без штрафа</th>
            <td>
                до {{ $cancelConditions?->cancelNoFeeDate ? Format::date($cancelConditions?->cancelNoFeeDate) : '-' }}</td>
        </tr>
        @foreach($cancelConditions?->dailyMarkups ?? [] as $dailyMarkup)
            <tr>
                <th>За {{ $dailyMarkup->daysCount }} {{ trans_choice('[1] день|[2,4] дня|[5,*] дней', $dailyMarkup->daysCount) }}</th>
                <td>
                    {{ $dailyMarkup->percent }}
                    % {{ $getHumanPeriodType($dailyMarkup->cancelPeriodType) }}
                </td>
            </tr>
        @endforeach

        <tr>
            <th>Незаезд</th>
            @if($cancelConditions)
                <td>
                    {{ $cancelConditions->noCheckInMarkup->percent }}%
                    {{ $getHumanPeriodType($cancelConditions->noCheckInMarkup->cancelPeriodType) }}
                </td>
            @endif
        </tr>
        </tbody>
    </table>
</div>
