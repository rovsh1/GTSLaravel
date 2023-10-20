@foreach($cars as $index => $car)
    <tr>
        <td class="text-align-center"><b>{{ ++$index }}</b></td>
        <td><b>{{ $car->model }}: </b></td>
        <td class="text-align-center last">{{ $car->countCars }}</td>
        <td class="text-align-center last">{{ $car->supplierPrice }}</td>
        <td class="text-align-center last">{{ $car->totalSupplierPrice }}</td>
    </tr>
    <tr>
        <td></td>
        <td>Количество пассажиров: {{ $car->countPassengers }}</td>
        <td colspan="3">
            <i>Подробный расчет: {{ $supplierPrice }} * {{ $car->countCars }}
                @if('car_rent')
                    {{ $countDays }} {{ trans_choice('день|дня|дней', $countDays) }}
                @endif
                = {{ $totalSupplierPrice }} UZS</i>
        </td>
    </tr>
    <tr>
        <td></td>
        <td>Количество багажа: {{ $car->countBaggage > 0 ? $car->countBaggage : 'Без багажа'}}</td>
    </tr>
@endforeach
