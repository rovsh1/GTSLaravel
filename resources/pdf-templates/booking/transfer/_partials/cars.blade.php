@foreach($cars as $index => $car)
    <tr>
        <td class="text-align-center"><b>{{ ++$index }}</b></td>
        <td><b>{{ $car->mark }} {{ $car->model }}: </b></td>
        <td class="text-align-center last">{{ $car->carsCount }}</td>
        <td class="text-align-center last">{{ Format::number($car->supplierPrice->pricePerCar) }}</td>
        <td class="text-align-center last">{{ Format::number($car->supplierPrice->allCarsAmount) }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Количество пассажиров: {{ $car->passengersCount }}</td>
        <td colspan="3">
            <i>Подробный расчет: {{ Format::number($car->supplierPrice->pricePerCar) }} * {{ $car->carsCount }}
                @if($service->typeId === \Module\Shared\Enum\ServiceTypeEnum::CAR_RENT_WITH_DRIVER)
                   * {{ $period->countDays }} {{ trans_choice('день|дня|дней', $period->countDays) }}
                @endif
                = {{ Format::number($car->supplierPrice->totalAmount) }} UZS</i>
        </td>
    </tr>
    <tr>
        <td></td>
        <td>Количество багажа: {{ $car->baggageCount > 0 ? $car->baggageCount : 'Без багажа'}}</td>
    </tr>
@endforeach
