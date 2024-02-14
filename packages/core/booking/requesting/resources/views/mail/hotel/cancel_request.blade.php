@extends('BookingRequesting::mail.layout.layout')

@section('content')
    <div class="mail-content">
        <div class="text-block">
            <p>{{ __('Уважаемый партнер,') }}</p>
        </div>
        <div class="text-block">
            <p>Просим Вас обработать <span class="cancelled">запрос на аннулирование бронирования</span> <a
                    href="{{ ho_url("/booking/{$booking->number}") }}" target="_blank">по
                    ссылке</a></p>
        </div>
        <div class="table-block">
            <table class="with-divider">
                <tr>
                    <td class="title-column">Номер брони</td>
                    <td class="value-column"><b>{{ $booking->number }}</b></td>
                </tr>
                <tr class="disable-divider">
                    <td class="title-column">Дата и время создания:</td>
                    <td class="value-column"><b>{{ $booking->createdAt }}</b></td>
                </tr>
            </table>
        </div>
        <div class="text-block">
            <p><b>{{ __('Информация о заказе:') }}</b></p>
        </div>
        <div class="table-block">
            <table>
                <tr>
                    <td class="title-column">
                        Список номеров:
                    </td>
                    <td class="value-column">
                        <table class="with-divider">
                            @php
                                $roomsCount = count($rooms);
                            @endphp
                            @foreach($rooms as $index => $room)
                                @if($roomsCount === $index - 1)
                                    <tr class="disable-divider">
                                        <td><b>{{++$index}}. {{ $room->name }}</b></td>
                                    </tr>
                                @else
                                    <tr>
                                        <td><b>{{++$index}}. {{ $room->name }}</b></td>
                                    </tr>
                                @endif
                            @endforeach
                        </table>
                    </td>
                </tr>
            </table>
        </div>
        <div class="table-block">
            <table class="with-divider">
                <tr>
                    <td class="title-column">Дата заезда:</td>
                    <td class="value-column"><b>{{ $bookingPeriod->startDate }}</b></td>
                </tr>
                <tr class="disable-divider">
                    <td class="title-column">Дата выезда:</td>
                    <td class="value-column"><b>{{ $bookingPeriod->endDate }}</b></td>
                </tr>
            </table>
        </div>
        <div class="text-block">
            <p>{{ __('С детальной информацией Вы можете ознакомиться во вложенном файле.') }}</p>
            <p>{{ __('Просим произвести сверку всех деталей заказа с Вашей стороны и сообщить в случае необходимости внесения каких-либо изменений Вашему менеджеру по бронированию.') }}</p>
        </div>
    </div>

@endsection
