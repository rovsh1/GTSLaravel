@foreach($cars as $index => $car)
    <tr>
        <td class="text-align-center"><b>{{ ++$index }}</b></td>
        <td><b>{{ $car->model }}: </b></td>
        <td class="text-align-center last">{{ $car->countCars }}</td>
        <td class="text-align-center last">-</td>
        <td class="text-align-center last">-</td>
    </tr>
    <tr>
        <td></td>
        <td>Количество пассажиров: {{ $car->countPassengers }}</td>
        <td colspan="3">
            <i>Подробный расчет: - * {{ $car->countCars }}
                @if($service->typeId === \Module\Shared\Enum\ServiceTypeEnum::CAR_RENT_WITH_DRIVER)
                    {{ $period->countDays }} {{ trans_choice('день|дня|дней', $period->countDays) }}
                @endif
                = - UZS</i>
        </td>
    </tr>
    <tr>
        <td></td>
        <td>Количество багажа: {{ $car->countBaggage > 0 ? $car->countBaggage : 'Без багажа'}}</td>
    </tr>
@endforeach
