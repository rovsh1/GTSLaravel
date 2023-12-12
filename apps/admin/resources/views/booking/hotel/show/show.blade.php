@extends('layouts.main')

@section('styles')
    @vite('resources/views/booking/hotel/show/show.scss')
@endsection

@section('scripts')
    {!! Js::variables([
        'bookingID' => $bookingId,
        'hotelID' => $hotelId,
        'hotelRooms' => $hotelRooms,
        'order' => $order,
        'currencies' => $currencies,
        'timelineUrl' => $timelineUrl,
        'editUrl' => $editUrl,
        'deleteUrl' => $deleteUrl,
        'manager' => $manager,
        'isHotelBooking' => true,
        'isOtherServiceBooking' => false,
    ]) !!}

    @vite('resources/views/booking/hotel/show/show.ts')
@endsection

@section('content')
    <div class="content-header">
        <div class="title">{{ $title }}</div>
        <div id="booking-actions-menu"></div>
        <div class="flex-grow-1"></div>
    </div>

    <div class="content-body">
        <div class="card card-form disable-border">
            <div class="card-body">
                <div class="d-flex flex-row gap-4">
                    <div class="w-100 d-flex flex-column">
                        <div class="w-100 rounded shadow-lg p-4 flex-grow-1">
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
                                    <th>Дата заезда и выезда</th>
                                    <td>{{ Format::period(new \Carbon\CarbonPeriod($model->details->period->dateFrom, $model->details->period->dateTo)) }}</td>
                                </tr>
                                <tr>
                                    <th>Источник</th>
                                    <td>{{ $model->source }}</td>
                                </tr>
                                <tr>
                                    <th>Тип брони</th>
                                    <td>{{$model->details->quotaProcessingMethod === \Sdk\Booking\Enum\QuotaProcessingMethodEnum::REQUEST ? 'По запросу' : 'По квоте'}}</td>
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
                                    <td>
                                        <a href="{{ route('client.show', $client->id) }}"
                                           target="_blank">{{ $client->name }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Отель</th>
                                    <td>
                                        <a href="{{ route('hotels.show', $hotel->id) }}"
                                           target="_blank">{{ $hotel->name }}</a>
                                    </td>
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
                                <tr>
                                    <th>Примечание</th>
                                    <td>
                                        <div id="booking-editable-note"></div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div id="booking-cancel-conditions"
                             class="position-relative mt-3 rounded shadow-lg p-4 flex-grow-1"></div>
                    </div>
                    <div class="w-100 d-flex flex-column">
                        <div id="booking-control-panel" class="d-flex flex-column flex-grow-1"></div>
                    </div>
                </div>
            </div>
        </div>

        <div id="booking-rooms"></div>
    </div>
@endsection
