<tr>
    <td class="text-align-center" style="padding: 5px 0;">{{ ++$index }}</td>
    <td style="padding: 5px 0;">Модель авто: {{ $car->mark }} {{ $car->model }}</td>
    <td class="text-align-center last" style="padding: 5px 0;">{{ $car->carsCount }}</td>
    <td class="text-align-center last" style="padding: 5px 0;">
        {{ Format::number($car->clientPrice->pricePerCar) }}
    </td>
    <td class="text-align-center" style="padding: 5px 0;">
        {{ Format::number($car->clientPrice->allCarsAmount) }}
    </td>
</tr>
