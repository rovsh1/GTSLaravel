@extends('layout.layout')

@push('css')
    <style>
        body {
            font-size: 1.45em;
            font-family: Arial, Helvetica, sans-serif;
        }
    </style>
@endpush

@section('content')
    <table>
        <tbody>
        @include('_partials.company_requisites_header')
        <tr>
            <td class="title text-align-center" colspan="2">ОТМЕНА БРОНИРОВАНИЯ</td>
        </tr>
        <tr>
            <td colspan="2">
                <table>
                    <tbody>
                    <tr>
                        <td>
                            <table>
                                <tr>
                                    <td class="top-table-left" style="font-size: 24px; font-weight: bold; color: red">
                                        Номер (ID):
                                    </td>
                                    <td style="font-size: 24px; font-weight: bold; color: red">
                                        <b>{{ $booking->number }}</b></td>
                                    <td class="text-align-right" colspan="2">
                                        <b>Дата и время отмены: {{ $booking->updatedAt }}</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>&ensp;</td>
                                    <td>&ensp;</td>
                                    <td class="text-align-right" colspan="2">
                                        <b>Дата и время создания: {{ $booking->createdAt }}</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="top-table-left">Гостиница:</td>
                                    <td>
                                        <b>{{ $hotel->name }} ({{ $hotel->city }})</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="top-table-left">Телефон:</td>
                                    <td>
                                        <b>{{ $hotel->phone }}</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="top-table-left">Период пребывания:</td>
                                    <td><b>{{ $bookingPeriod->startDate }}</b> - <b>{{ $bookingPeriod->endDate }}</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="top-table-left">Количество ночей:</td>
                                    <td><b>{{ $bookingPeriod->nightsCount }}</b></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-top: 20px;">
                <table>
                    <thead>
                    <tr>
                        <th class="text-align-center" style="width: 5%;">№</th>
                        <th class="text-align-left">Информация о размещении</th>
                        <th style="width: 33%;"></th>
                    </tr>
                    </thead>
                    <tbody>

                    @include('booking.hotel._partials.rooms')

                    <tr class="first">
                        <td colspan="3">
                            <p style="text-align: justify"><b>Внимание!</b> Компания гарантирует оплату за количество
                                ночей и услуги указанные в заявке,
                                в случае раннего заезда / позднего выезда и прочих дополнительных услуг не указанных в
                                заявке компания не несет ответственности и отель обязуется сам взимать оплату с гостя по
                                своим расценкам.</p>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table>
                    <tbody>
                    <tr>
                        <td style="width: 500px;">
                            @include('_partials.manager_requisites')
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
            <td class="text-align-right" style="width: 250px"><img src="var:stamp" alt="" width="250"/></td>
        </tr>
        </tbody>
    </table>
@endsection
