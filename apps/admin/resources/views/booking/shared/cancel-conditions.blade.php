@php
    $getHumanPeriodType = function (int $cancelPeriodType) {
        return $cancelPeriodType === Module\Hotel\Moderation\Domain\Hotel\ValueObject\MarkupSettings\CancelPeriodTypeEnum::FULL_PERIOD->value ? 'За весь период' : 'За первую ночь';
    };
@endphp

<div class="position-relative mt-3 rounded shadow-lg p-4">
    <div class="control-panel-section-title">
        <span><h6>Условия отмены</h6></span>
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
                <th>За {{ $dailyMarkup->daysCount }} дней</th>
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
