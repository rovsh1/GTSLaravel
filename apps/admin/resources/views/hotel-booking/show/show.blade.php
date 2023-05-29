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
                                    <a href="#" target="_blank">{{ $model->orderId }}</a>
                                </td>
                            </tr>
                            <tr>
                                <th>Дата заезда и выезда</th>
                                <td>-</td>
                            </tr>
                            <tr>
                                <th>Источник</th>
                                <td>-</td>
                            </tr>
                            <tr>
                                <th>Страна</th>
                                <td>-</td>
                            </tr>
                            <tr>
                                <th>Клиент</th>
                                <td>-</td>
                            </tr>
                            <tr>
                                <th>Отель</th>
                                <td>-</td>
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
                                <th>Создана</th>
                                <td>{{ $model->dateCreate }}</td>
                            </tr>
                            <tr>
                                <th>Статус</th>
                                <td>-</td>
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
