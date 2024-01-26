@extends('layouts.main')

@section('styles')
    @vite('resources/views/booking/hotel/show/show.scss')
@endsection

@section('scripts')
    {!! Js::variables([
        'bookingID' => $bookingId,
        'hotelRooms' => $hotelRooms,
        'timelineUrl' => $timelineUrl,
        'order' => $order,
        'currencies' => $currencies,
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
                    <div class="w-85 d-flex flex-column">
                        <div class="w-100 rounded shadow-lg p-4 flex-grow-1">
                            <table class="table-params">
                                <tbody>
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
