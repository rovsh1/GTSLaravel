@extends('layouts.main')

@section('styles')
    @vite('resources/views/booking-order/show/show.scss')
@endsection

@section('head-end')
    <script>
      window['view-initial-data-booking-order'] = {{ Js::from([
            'orderID' => $orderId,
            'manager' => $manager,
            'serviceBookingCreate' => $serviceBookingCreate,
            'hotelBookingCreate' => $hotelBookingCreate,
            'clientID' => $client->id,
        ]) }}
    </script>
@endsection

@section('scripts')
    @vite('resources/views/booking-order/show/show.ts')
@endsection

@section('content')
    <div class="content-header">
        <div class="title">{{ $title }}</div>
        <div class="flex-grow-1"></div>
    </div>

    <div class="content-body">
        <div class="card card-form disable-border">
            <div class="card-body pt-0">
                <div class="d-flex flex-row gap-4">
                    <div class="w-100 rounded shadow-lg p-4">
                        <table class="table-params">
                            <tbody>
                            <tr>
                                <th>Заказ (ID)</th>
                                <td>
                                    {{$model->id}}
                                </td>
                            </tr>
                            <tr>
                                <th>Источник</th>
                                <td>{{ $model->source }}</td>
                            </tr>
                            <tr>
                                <th>Клиент</th>
                                <td>
                                    <a href="{{ route('client.show', $client->id) }}"
                                       target="_blank">{{ $client->name }}</a>
                                </td>
                            </tr>
                            <tr>
                                <th>Период</th>
                                <td>-</td>
                            </tr>
                            <tr>
                                <th>Менеджер</th>
                                <td>
                                    <div id="order-editable-manager"></div>
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
                            <!-- <tr>
                                <th>Примечание</th>
                                <td>
                                    <div id="order-editable-note"></div>
                                </td>
                            </tr> -->
                            </tbody>
                        </table>
                    </div>
                    <div class="w-100 d-flex flex-column">
                        <div id="order-control-panel" class="d-flex flex-column flex-grow-1"></div>
                    </div>
                </div>
            </div>
        </div>

        <div id="order-details"></div>
    </div>
@endsection
