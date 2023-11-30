<tr>
    <td>Номер: {{ $room->name }}</td>
    <td class="text-align-center">1</td>
    <td class="text-align-center">{{ $room->price }}</td>
    <td class="text-align-center">{{ $room->price }}</td>
</tr>
<tr>
    <td>Гости ({{ count($room->guests) }}):</td>
    <td colspan="3"></td>
</tr>
@foreach($room->guests as $index => $guest)
    @include('invoice._partials.guest')
@endforeach
<tr>
    <td>Дата заезда: {{ Format::date($booking->bookingPeriod->startDate) }} время заезда: {{ $room->checkInTime }}</td>
    <td colspan="3"></td>
</tr>
<tr>
    <td>Дата выезда: {{ Format::date($booking->bookingPeriod->endDate) }} время выезда: {{ $room->checkOutTime }}</td>
    <td colspan="3"></td>
</tr>
<tr>
    <td>Ночей: {{ $booking->bookingPeriod->countDays }}</td>
    <td colspan="3"></td>
</tr>
<tr>
    <td>Питание: {{ $room->rate }}</td>
    <td colspan="3"></td>
</tr>

@if(false)
    <tr>
        <td>Ранний заезд: ''</td>
        <td></td>
    </tr>
@endif

@if(false)
    <tr>
        <td>Поздний выезд: ''</td>
        <td></td>
    </tr>
@endif

<tr class="padding-bottom"></tr>

