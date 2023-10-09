@extends('layouts.main')

@section('styles')
    @vite('resources/views/service-booking/show/show.scss')
@endsection

@section('head-end')
    <script>
      window['view-initial-data-service-booking'] = {{ Js::from([
            'bookingID' => $bookingId,
            'manager' => $manager,
            'order' => $order,
            'currencies' => $currencies,
            'editUrl' => $editUrl,
            'deleteUrl' => $deleteUrl,
        ]) }}
    </script>
@endsection

@section('scripts')
    @vite('resources/views/service-booking/show/show.ts')
@endsection

@section('content')
    <div class="content-header">
        <div class="title">{{ $title }}</div>
        <div id="booking-actions-menu"></div>
        <div id="booking-copy-button"></div>
        <div class="flex-grow-1"></div>
    </div>

    <div class="content-body">
        <div class="card card-form mt-3 pt-3">
            <div class="card-body">
                <div class="d-flex flex-row gap-4">
                    <div class="w-100 rounded shadow-lg p-4">
                        <table class="table-params">
                            <tbody>
                            <tr>
                                <th>Заказ (ID)</th>
                                <td>
                                    <a href="{{ route('booking-order.show', $model->orderId) }}"
                                       target="_blank">{{ $model->orderId }}</a>
                                </td>
                            </tr>
                            <tr>
                                <th>Источник</th>
                                <td>-</td>
                            </tr>
                            <tr>
                                <th>Клиент</th>
                                <td>
                                    <a href="{{ route('client.show', $client->id) }}" target="_blank">{{ $client->name }}</a>
                                </td>
                            </tr>
                            <tr>
                                <th>Тип карты оплаты</th>
                                <td>-</td>
                            </tr>
                            <tr>
                                <th>Город</th>
                                <td>-</td>
                            </tr>
                            <tr>
                                <th>Услуга</th>
                                <td>{{ $model->serviceType->name }}</td>
                            </tr>
                            <tr>
                                <th>Менеджер</th>
                                <td>
                                    <div id="booking-editable-manager"></div>
                                </td>
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
                                <td>
                                    <div id="booking-editable-note"></div>
                                </td>
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

        <div id="booking-details"></div>
    </div>
@endsection
