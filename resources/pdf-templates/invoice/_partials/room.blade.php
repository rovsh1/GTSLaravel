<tr>
    <td class="text-align-center">{{ ++$index }}</td>
    <td>{{ $room->name }}</td>
    <td class="text-align-center">1</td>
    <td class="text-align-center">-</td>
    <td class="text-align-center">-</td>
</tr>
<tr>
    <td></td>
    <td>Питание: {{ $room->rate }}</td>
    <td></td>
</tr>
<tr>
    <td></td>
    <td>Туристы ({{ count($room->guests) }}):</td>
    <td></td>
</tr>
@foreach($room->guests as $index => $guest)
    @include('invoice._partials.guest')
@endforeach
<tr>
    <td></td>
    <td>Время заезда: {{ $room->checkInTime }}</td>
    <td></td>
</tr>
<tr>
    <td></td>
    <td>Время выезда: {{ $room->checkOutTime }}</td>
    <td></td>
</tr>

@if(false)
    <tr>
        <td></td>
        <td>Ранний заезд: ''</td>
        <td></td>
    </tr>
@endif

@if(false)
    <tr>
        <td></td>
        <td>Поздний выезд: ''</td>
        <td></td>
    </tr>
@endif

<tr class="padding-bottom">
    <td></td>
    <td>Примечание (запрос в отель, ваучер): {{ $room->note }}</td>
    <td></td>
    <td></td>
    <td></td>
</tr>

