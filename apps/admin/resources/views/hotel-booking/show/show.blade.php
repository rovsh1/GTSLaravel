@extends('layouts.main')

@section('styles')
    @vite('resources/views/hotel-booking/show/show.scss')
@endsection

@section('head-end')
    <script>
      window['view-initial-data-hotel-booking'] = {{ Js::from([
            'hotelID' => $model->details->hotelId,
            'rooms' => $rooms,
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
                <table class="table-params">
                    <tbody>
                    {{--                    <tr class="">--}}
                    {{--                        <th>Заказ (ID)</th>--}}
                    {{--                        <td>--}}
                    {{--                            <a href="#" target="_blank">{{$model->order_id}}</a>--}}
                    {{--                        </td>--}}
                    {{--                    </tr>--}}
                    {{--                    <tr class="">--}}
                    {{--                        <th>Дата заезда и выезда</th>--}}
                    {{--                        <td>{{$model->period}}</td>--}}
                    {{--                    </tr>--}}
                    {{--                    <tr class="">--}}
                    {{--                        <th>Источник</th>--}}
                    {{--                        <td>{{$model->source}}</td>--}}
                    {{--                    </tr>--}}
                    {{--                    <tr class="">--}}
                    {{--                        <th>Страна</th>--}}
                    {{--                        <td>{{$model->city_id}}</td>--}}
                    {{--                    </tr>--}}
                    {{--                    <tr class="">--}}
                    {{--                        <th>Клиент</th>--}}
                    {{--                        <td>{{$model->client_name}}</td>--}}
                    {{--                    </tr>--}}
                    {{--                    <tr class="">--}}
                    {{--                        <th>Отель</th>--}}
                    {{--                        <td>{{$model->hotel_id ?? null}}</td>--}}
                    {{--                    </tr>--}}
                    {{--                    <tr class="">--}}
                    {{--                        <th>Создана</th>--}}
                    {{--                        <td>{{$model->created_at}}</td>--}}
                    {{--                    </tr>--}}
                    <tr class="">
                        <th>Статус</th>
                        <td>{{$model->status}}</td>
                    </tr>
                    <tr class="">
                        <th>Примечание</th>
                        <td>{{$model->note}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="booking-rooms"></div>
    </div>
@endsection
