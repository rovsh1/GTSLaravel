@extends('layouts.main')

@section('styles')
    @vite('resources/views/transfer-booking/show/show.scss')
@endsection

@section('head-end')
    <script>
      window['view-initial-data-airport-booking'] = {{ Js::from([
            'bookingID' => $bookingId,
        ]) }}
    </script>
@endsection

@section('scripts')
    @vite('resources/views/transfer-booking/show/show.ts')
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
                                <th>Бронь (ID)</th>
                                <td>-</td>
                            </tr>
                            <tr>
                                <th>Источник</th>
                                <td>-</td>
                            </tr>
                            <tr>
                                <th>Клиент</th>
                                <td>{{ $client->name }}</td>
                            </tr>
                            <tr>
                                <th>Тип карты оплаты</th>
                                <td>-</td>
                            </tr>
                            <tr>
                                <th>Дата прилёта/вылета</th>
                                <td>{{ $model->date }}</td>
                            </tr>
                            <tr>
                                <th>Город</th>
                                <td>-</td>
                            </tr>
                            <tr>
                                <th>Услуга</th>
                                <td>{{ $model->serviceInfo->name }}</td>
                            </tr>
                            <tr>
                                <th>Аэропорт</th>
                                <td>{{ $model->airportInfo->name }}</td>
                            </tr>
                            <tr>
                                <th>Номер рейса</th>
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
