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
                @changemark("accommodation[$room->accommodationId]")
                    <b>{{ $room->name }}</b>
                @endchangemark
            </td>
            <td>
                @changemark("accommodation[$room->accommodationId].earlyCheckIn")
                    Время заезда: {{ $room->checkInTime }}
                @endchangemark
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                @changemark("accommodation[$room->accommodationId].rateId")
                    Тариф: {{ $room->rate }}
                @endchangemark
            </td>
            <td>
                @changemark("accommodation[$room->accommodationId].lateCheckOut")
                    Время выезда: {{ $room->checkOutTime }}
                @endchangemark
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                @changemark("accommodation[$room->accommodationId].guests")
                    Туристы ({{ count($room->guests) }}):
                @endchangemark
            </td>
            <td></td>
        </tr>
        @foreach($room->guests as $index => $guest)
            <tr>
                <td></td>
                <td>
                    @changemark("accommodation[$room->accommodationId].guests")
                        {{++$index}}. {{ $guest->fullName }}, {{ $guest->gender }}, {{ $guest->countryName }}
                    @endchangemark
                </td>
                <td></td>
            </tr>
        @endforeach
        <tr>
            <td></td>
            <td>
                @changemark("accommodation[$room->accommodationId].guestNote")
                    Примечание (запрос в отель, ваучер): {{ $room->note }}
                @endchangemark
            </td>
            <td></td>
        </tr>

        @endforeach
