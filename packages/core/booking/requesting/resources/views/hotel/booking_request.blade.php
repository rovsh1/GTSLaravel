@extends('BookingShared::layout.layout')

@push('css')
    <style>
        .invoice-header a,
        .invoice-header b,
        .invoice-header p {
            font-family: Arial, Helvetica, sans-serif !important;
        }

        .invoice-content li,
        .invoice-content a,
        .invoice-content p,
        .invoice-content i,
        .invoice-content u,
        .invoice-content b,
        .invoice-content div,
        .invoice-footer p {
            font-size: 13px;
            font-family: Arial, Helvetica, sans-serif !important;
        }

        .invoice-wrapper p {
            margin: 0;
            padding: 0;
        }

        .invoice-wrapper .clear-both {
            overflow: auto;
        }

        .invoice-wrapper .column {
            float: left;
        }

        .invoice-wrapper .w-10 {
            width: 10%;
        }

        .invoice-wrapper .w-15 {
            width: 15%;
        }

        .invoice-wrapper .w-17 {
            width: 17.5%;
        }

        .invoice-wrapper .w-25 {
            width: 25%;
        }

        .invoice-wrapper .w-28 {
            width: 28%;
        }

        .invoice-wrapper .w-45 {
            width: 45%;
        }

        .invoice-wrapper .w-55 {
            width: 55%;
        }

        .invoice-wrapper .w-50 {
            width: 50%;
        }

        .invoice-wrapper .w-72 {
            width: 72%;
        }

        .invoice-wrapper .w-75 {
            width: 75%;
        }

        .invoice-wrapper .w-85 {
            width: 85%;
        }

        .invoice-wrapper .text-right {
            text-align: right;
        }

        .invoice-wrapper .text-center {
            text-align: center;
        }

        .invoice-footer .mark {
            width: 225px;
        }

        .invoice-header {
            padding: 0 9px;
            margin-bottom: 20px;
        }

        .invoice-header .invoice-header__left {
            width: 40%;
            text-align: left;
        }

        .invoice-header .invoice-header__right {
            width: 60%;
            text-align: right;
        }

        .invoice-header .invoice-header__right .invoice-header-title {
            font-size: 32px !important;
        }

        .invoice-header .invoice-header__right .invoice-header-description,
        .invoice-header .invoice-header__right .invoice-header-description a {
            font-size: 11px;
        }

        .invoice-header .invoice-header__left img {
            width: 215px;
        }

        .client-info {
            padding: 9px 2px;
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            margin-bottom: 10px;
        }

        .total-amount {
            padding: 0px 9px;
            margin-bottom: 15px;
        }

        .total-amount p b {
            font-size: 19px !important;
        }

        .note {
            margin-bottom: 15px;
        }

        .service-item ol {
            margin: 0;
        }

        .service-item {
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            padding: 0 9px;
            margin-bottom: 9px;
        }

        .service-item ol li {
            padding-left: 10px;
        }

        .service-title {
            page-break-after: avoid !important;
            orphans: 0;
        }
    </style>
@endpush

@section('content')
    <div class="invoice-wrapper">
        <div class="invoice-header clear-both">
            <div class="column invoice-header__left">
                <img src="var:logo" alt="logo">
            </div>
            <div class="column invoice-header__right">
                <p class="invoice-header-title"><b>НОВОЕ БРОНИРОВАНИЕ #{{$booking->number }}</b></p>
                <p class="invoice-header-description">{{ __('Дата создания: :date', ['date' => $booking->createdAt]) }}</p>
                <br/>
                <p class="invoice-header-description"><b>{{ $company->name }}</b></p>
                <p class="invoice-header-description">{{ __('Тел: :phone', ['phone'=>$company->phone]) }}</p>
                <p class="invoice-header-description">
                    <a href="mailto:{{ $company->email }}">E-mail: {{ $company->email }}</a>
                </p>
                <p class="invoice-header-description">{{ $company->legalAddress }}</p>
            </div>
        </div>
        <div class="invoice-content">

            <h1 style="text-align: center">
                <mark style="background-color: #30d931;">ПРОСИМ ПОДТВЕРДИТЬ БРОНИРОВАНИЕ</mark>
            </h1>

            <div class="client-info">
                <div class="clear-both" style="font-size: 14pt">
                    <div class="column w-15">
                        <p>Отель:</p>
                    </div>
                    <div class="column w-85">
                        <p><b>{{ $hotel->name }} ({{ $hotel->city }})</b></p>
                    </div>
                </div>
                <div class="clear-both">
                    <div class="column w-15">
                        <p>{{ __('Телефон') }}:</p>
                    </div>
                    <div class="column w-85">
                        <p>{{ $hotel->phone }}</p>
                    </div>
                </div>
            </div>

            @foreach($rooms as $roomIndex => $room)
                <div class="service-item">
                    <div class="service-title clear-both">
                        <div class="column w-55">
                            <p><b>Информация о размещении</b></p>
                        </div>
                        <div class="column w-17 text-right"><p><b>Итого стоимость</b></p></div>
                    </div>

                    @if(count($room->guests) > 0)
                        <div class="service-guests w-55">
                            <p>{{ __('Гости (:count)', ['count' => count($room->guests)]) }}:</p>
                            <ol>
                                @foreach($room->guests ?? [] as $guest)
                                    @changemark("accommodation[$room->accommodationId].guests")
                                    <li>{{ $guest->fullName }} ({{ $guest->countryName }})</li>
                                    @endchangemark
                                @endforeach
                            </ol>
                        </div>
                    @endif

                    <div class="service-details clear-both">
                        <div class="column w-55">
                            <p>Дата заезда: {{ $bookingPeriod->startDate }}</p>
                        </div>
                        <div class="column w-17">
                            <p>Время заезда:</p>
                        </div>
                    </div>

                    <div class="service-details clear-both">
                        <div class="column w-55">
                            <p>Дата выезда: {{ $bookingPeriod->endDate }}</p>
                        </div>
                        <div class="column w-17">
                            <p>Время выезда:</p>
                        </div>
                    </div>

                </div>
            @endforeach

        </div>
    </div>


    {{--    <table>--}}
    {{--        <tbody>--}}
    {{--        @include('BookingShared::_partials.company_requisites_header')--}}
    {{--        <tr>--}}
    {{--            <td class="title text-align-center" colspan="2">ПРОСИМ ПОДТВЕРДИТЬ БРОНИРОВАНИЕ</td>--}}
    {{--        </tr>--}}
    {{--        <tr>--}}
    {{--            <td colspan="2">--}}
    {{--                <table>--}}
    {{--                    <tbody>--}}
    {{--                    <tr>--}}
    {{--                        <td>--}}
    {{--                            <table>--}}
    {{--                                <tbody>--}}
    {{--                                <tr>--}}
    {{--                                    <td class="top-table-left" style="font-size: 24px; font-weight: bold; color: red">--}}
    {{--                                        Новое бронирование #--}}
    {{--                                    </td>--}}
    {{--                                    <td style="font-size: 24px; font-weight: bold; color: red">{{ $booking->number }}</td>--}}
    {{--                                    <td class="text-align-right" colspan="2">--}}
    {{--                                        <b>Дата создания: {{ $booking->createdAt }}</b>--}}
    {{--                                    </td>--}}
    {{--                                </tr>--}}
    {{--                                </tbody>--}}
    {{--                            </table>--}}
    {{--                        </td>--}}
    {{--                    </tr>--}}
    {{--                    </tbody>--}}
    {{--                </table>--}}
    {{--            </td>--}}
    {{--        </tr>--}}

    {{--        <table>--}}
    {{--            <tbody>--}}
    {{--            <tr>--}}
    {{--                <td class="top-table-left">Гостиница:</td>--}}
    {{--                <td>--}}
    {{--                    <b>{{ $hotel->name }} ({{ $hotel->city }})</b>--}}
    {{--                </td>--}}
    {{--            </tr>--}}
    {{--            <tr>--}}
    {{--                <td class="top-table-left">Телефон:</td>--}}
    {{--                <td>--}}
    {{--                    <b>{{ $hotel->phone }}</b>--}}
    {{--                </td>--}}
    {{--            </tr>--}}
    {{--            </tbody>--}}
    {{--        </table>--}}

    {{--        <tr>--}}
    {{--            <td colspan="2" style="padding-top: 20px;">--}}
    {{--                <table>--}}
    {{--                    <thead>--}}
    {{--                    <tr>--}}
    {{--                        <th class="text-align-center" style="width: 5%;">№</th>--}}
    {{--                        <th class="text-align-left">Информация о размещении</th>--}}
    {{--                        <th style="width: 33%;"></th>--}}
    {{--                    </tr>--}}
    {{--                    </thead>--}}
    {{--                    <tbody>--}}

    {{--                    @include('BookingRequesting::hotel._partials.rooms')--}}

    {{--                    <tr class="first">--}}
    {{--                        <td colspan="3">--}}
    {{--                            <p style="text-align: justify"><b>Внимание!</b> Компания гарантирует оплату за количество--}}
    {{--                                ночей--}}
    {{--                                и услуги указанные в заявке,--}}
    {{--                                в случае раннего заезда / позднего выезда и прочих дополнительных услуг не указанных в--}}
    {{--                                заявке компания не несет ответственности и отель обязуется сам взимать оплату с гостя по--}}
    {{--                                своим расценкам.</p>--}}
    {{--                            <p style="text-align: justify"><b>Информация для отеля!</b> Убедительно, просим Вас в день--}}
    {{--                                выезда гостей, выставить счет-фактуры на сайте https://my.soliq.uz</p>--}}
    {{--                        </td>--}}
    {{--                    </tr>--}}
    {{--                    </tbody>--}}
    {{--                </table>--}}
    {{--            </td>--}}
    {{--        </tr>--}}
    {{--        <tr>--}}
    {{--            <td>--}}
    {{--                <table>--}}
    {{--                    <tbody>--}}
    {{--                    <tr>--}}
    {{--                        <td style="width: 500px;">--}}
    {{--                            @include('BookingShared::_partials.manager_requisites')--}}
    {{--                        </td>--}}
    {{--                    </tr>--}}
    {{--                    </tbody>--}}
    {{--                </table>--}}
    {{--            </td>--}}
    {{--            <td class="text-align-right" style="width: 250px"><img src="var:stamp" alt="" width="250"></td>--}}
    {{--        </tr>--}}
    {{--        </tbody>--}}
    {{--    </table>--}}
@endsection
