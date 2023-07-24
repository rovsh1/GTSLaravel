@foreach($rooms as $roomIndex => $room)
    @if($roomIndex === 0)
        <tr class="first">
    @else
        <tr>
            @endif
            <td>
                <b>{{++$roomIndex}}</b>
            </td>
            <td>
                <b>{{$room->roomInfo()->name()}}</b>
            </td>
            <td>
                Время заезда: {{$room->details()->earlyCheckIn()?->timePeriod()->from() ?? $hotelDefaultCheckInTime}}
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                Питание: без завтрака
            </td>
            <td>
                Время выезда: {{$room->details()->lateCheckOut()?->timePeriod()->to() ?? $hotelDefaultCheckOutTime}}
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                Туристы ({{$room->guests()->count()}}):
            </td>
            <td></td>
        </tr>
        @foreach($room->guests() as $index => $guest)
            <tr>
                <td></td>
                <td>
                    {{++$index}}. {{$guest->fullName()}}, {{$guest->gender()->value}}, {{$guest->countryId()}}
                </td>
                <td></td>
            </tr>
        @endforeach
        <tr>
            <td></td>
            <td>
                Примечание (запрос в отель, ваучер):
            </td>
            <td></td>
        </tr>

@endforeach
