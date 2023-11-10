<tr>
    <td class="text-align-center">{servicesCount}</td>
    <td>{room_name}</td>
    <td class="text-align-center">1</td>
    <td class="text-align-center">{$roomPrice->total_avg}</td>
    <td class="text-align-center">{$roomPrice->total_gross}</td>
</tr>
<tr>
    <td></td>
    <td>Питание: {rate_name}</td>
    <td></td>
</tr>
<tr>
    <td></td>
    <td>Туристы ({$room->getGuestsNumber()}):</td>
    <td></td>
</tr>
@foreach($room->guests as $index => $guest)
    @include('invoice._partials.guest')
@endforeach
<tr>
    <td></td>
    <td>Время заезда: ' . $this->getCheckInText($room->checkin_id) . '</td>
    <td></td>
</tr>
<tr>
    <td></td>
    <td>Время выезда: ' . $this->getCheckOutText($room->checkout_id) . '</td>
    <td></td>
</tr>

@if(false)
    <tr>
        <td></td>
        <td>Ранний заезд: ' . discount($this->getConditionPercent($room->checkin_id, 'CheckIn')) . ' - ' . price($roomPrice->checkIn) . '</td>
        <td></td>
    </tr>
@endif

@if(false)
    <tr>
        <td></td>
        <td>Поздний выезд: ' . discount($this->getConditionPercent($room->checkout_id, 'CheckOut')) . ' - ' . price($roomPrice->checkOut) . '</td>
        <td></td>
    </tr>
@endif

<tr class="padding-bottom">
    <td></td>
    <td>Примечание (запрос в отель, ваучер): ' . $room->note . '</td>
    <td></td>
    <td></td>
    <td></td>
</tr>

