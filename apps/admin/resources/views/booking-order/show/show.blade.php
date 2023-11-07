@extends('layouts.main')

@section('styles')
    @vite('resources/views/booking-order/show/show.scss')
@endsection

@section('head-end')
    <script>
      window['view-initial-data-booking-order'] = {{ Js::from([
            'orderId' => $orderId,
            'manager' => $manager,
        ]) }}
    </script>
@endsection

@section('scripts')
    @vite('resources/views/booking-order/show/show.ts')
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
                                    {{$model->id}}
                                </td>
                            </tr>
                            <tr>
                                <th>Инвойс (ID)</th>
                                <td>-</td>
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
                            </tbody>
                        </table>
                    </div>
                    <div class="w-100 rounded shadow-lg p-4">
                        <div id="order-control-panel"></div>
                    </div>
                </div>
            </div>
        </div>

        <div id="order-details"></div>
    </div>
@endsection
