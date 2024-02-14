@extends('BookingRequesting::mail.layout.layout')

@section('content')
    <div class="mail-content">
        <div class="text-block">
            <p>{{ __('Уважаемый партнер,') }}</p>
        </div>
        <div class="text-block">
            <p>Просим Вас обработать <span class="new">запрос на бронирование</span> <a
                    href="{{ route('service-booking.show', $booking->number) }}"
                    target="_blank">по
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
            <table class="with-divider">
                <tr>
                    <td class="title-column">Услуга:</td>
                    <td class="value-column"><b>{{ $service->title }}</b></td>
                </tr>
                <tr class="disable-divider">
                    @if(isset($period))
                        <td class="title-column">Период:</td>
                        <td class="value-column"><b>{{ $period->startDate }} - {{ $period->endDate }}</b></td>
                    @else
                        <td class="title-column">Дата:</td>
                        <td class="value-column"><b>{{ $date }}</b></td>
                    @endif
                </tr>
            </table>
        </div>
        <div class="text-block">
            <p>{{ __('С детальной информацией Вы можете ознакомиться во вложенном файле.') }}</p>
            <p>{{ __('Просим произвести сверку всех деталей заказа с Вашей стороны и сообщить в случае необходимости внесения каких-либо изменений Вашему менеджеру по бронированию.') }}</p>
        </div>
    </div>

@endsection
