@extends('layouts.main')

@section('styles')
    @vite('resources/views/hotel-booking/show/show.scss')
@endsection

@section('scripts')
    @vite('resources/views/hotel-booking/show/show.ts')
@endsection

@section('head-end')
    <script>
      window['view-initial-data-hotel-booking'] = {{ Js::from([
            'bookingID' => $bookingId,
            'hotelID' => $hotelId,
            'hotelRooms' => $hotelRooms,
            'order' => $order,
            'currencies' => $currencies
        ]) }}
    </script>
@endsection

@section('content')
    <div class="content-header">
        <div class="title">{{ $title }}</div>
        <x-ui.actions-menu :$editUrl :$deleteUrl class="mr-2"/>
        <div id="booking-copy-button"></div>
        <div class="flex-grow-1"></div>
    </div>

    <div class="content-body">
        <div class="card card-form mt-3">
            <div class="card-body">
                <div class="d-flex flex-row gap-4">
                    <div class="w-100 rounded shadow-lg p-4">
                        <table class="table-params">
                            <tbody>
                            <tr>
                                <th>Заказ (ID)</th>
                                <td>
                                    <a href="{{ route('booking-order.show', $model->orderId) }}" target="_blank">{{ $model->orderId }}</a>
                                </td>
                            </tr>
                            <tr>
                                <th>Дата заезда и выезда</th>
                                <td>{{ Format::period(new \Carbon\CarbonPeriod($model->period->dateFrom, $model->period->dateTo)) }}</td>
                            </tr>
                            <tr>
                                <th>Источник</th>
                                <td>-</td>
                            </tr>
                            <tr>
                                <th>Тип брони</th>
                                <td>-</td>
                            </tr>
                            <tr>
                                <th>Тип карты оплаты</th>
                                <td>-</td>
                            </tr>
                            <tr>
                                <th>Страна</th>
                                <td>{{ $hotel->country_name }} / {{ $hotel->city_name }}</td>
                            </tr>
                            <tr>
                                <th>Клиент</th>
                                <td>{{ $client->name }}</td>
                            </tr>
                            <tr>
                                <th>Отель</th>
                                <td>{{ $hotel->name }}</td>
                            </tr>
                            <tr>
                                <th>Транспортная бронь (ID)</th>
                                <td>-</td>
                            </tr>
                            <tr>
                                <th>Услуга в аэропорту (ID)</th>
                                <td>-</td>
                            </tr>
                            <tr>
                                <th>Менеджер</th>
                                <td> {{ $manager->presentation }}</td>
                            </tr>
                            <tr>
                                <th>Автор</th>
                                <td>{{ $creator->presentation }}</td>
                            </tr>
                            <tr>
                                <th>Создана</th>
                                <td>{{ $model->createdAt }}</td>
                            </tr>
                            <tr>
                            <tr>
                                <th>Примечание</th>
                                <td>{{ $model->note }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="w-100 rounded shadow-lg p-4">
                        <div id="booking-control-panel"></div>
                    </div>
                </div>
            </div>
        </div>

        <div id="booking-rooms"></div>
    </div>
@endsection
