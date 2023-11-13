@foreach($rooms as $roomIndex => $room)
    @if($roomIndex === 0)
        <tr class="first">
    @else
        <tr>
    @endif
            <td>
                <b>{{ ++$roomIndex }}</b>
            </td>
            <td>
                <b>{{ $room->name }}</b>
            </td>
            <td>
                Время заезда: {{ $room->checkInTime }}
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                Тариф: {{ $room->rate }}
            </td>
            <td>
                Время выезда: {{ $room->checkOutTime }}
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                Туристы ({{ count($room->guests) }}):
            </td>
            <td></td>
        </tr>
        @foreach($room->guests as $index => $guest)
            <tr>
                <td></td>
                <td>
                    {{++$index}}. {{ $guest->fullName }}, {{ $guest->gender }}, {{ $guest->countryName }}
                </td>
                <td></td>
            </tr>
        @endforeach
        <tr>
            <td></td>
            <td>
                Примечание (запрос в отель, ваучер): {{$room->note}}
            </td>
            <td></td>
        </tr>

        @endforeach
