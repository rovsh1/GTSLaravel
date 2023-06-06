@extends('layouts.main')

@section('styles')
    @vite('resources/views/hotel-booking/show/show.scss')
@endsection

@section('head-end')
    <script>
      window['view-initial-data-hotel-booking'] = {{ Js::from([
            'bookingID' => $bookingId,
            'hotelID' => $hotelId,
            'hotelRooms' => $hotelRooms,
        ]) }}
    </script>
@endsection

@section('scripts')
    @vite('resources/views/hotel-booking/show/show.ts')
@endsection

@section('content')
    <div class="content-header">
        <div class="title">{{ $title }}</div>
        <x-ui.actions-menu :$editUrl :$deleteUrl/>
        <div class="flex-grow-1"></div>

        {!! Layout::actions() !!}
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
                                <td>{{ Format::period(new \Carbon\CarbonPeriod($details->period->dateFrom, $details->period->dateTo)) }}</td>
                            </tr>
                            <tr>
                                <th>Источник</th>
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
                                <td>-</td>
                            </tr>
                            <tr>
                                <th>Автор</th>
                                <td>{{ $model->creatorId }}</td>
                            </tr>
                            <tr>
                                <th>Создана</th>
                                <td>{{ $model->dateCreate }}</td>
                            </tr>
                            <tr>
                                <th>Статус</th>
                                <td>{{ $model->status }}</td>
                            </tr>
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
