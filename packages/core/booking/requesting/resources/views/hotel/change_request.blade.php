@extends('BookingShared::layout.layout')

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
        @include('BookingShared::_partials.company_requisites_header')
        <tr>
            <td class="title text-align-center" colspan="2">ИЗМЕНЕНИЕ БРОНИРОВАНИЯ</td>
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
                                    <td style="font-size: 24px; font-weight: bold; color: red">{{ $booking->number }}</td>
                                    <td class="text-align-right" colspan="2">
                                        <b>Дата и время изменения: {{ $booking->updatedAt }}</b>
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
                        <th class="text-align-center">№</th>
                        <th class="text-align-left">Информация о размещении</th>
                    </tr>
                    </thead>
                    <tbody>

                    @include('BookingRequesting::hotel._partials.rooms')

                    {reservationDetails}
                    <tr class="first">
                        <td colspan="3">
                            <p style="text-align: justify"><b>Внимание!</b> Компания гарантирует оплату за количество
                                ночей и услуги указанные в заявке,
                                в случае раннего заезда / позднего выезда и прочих дополнительных услуг не указанных в
                                заявке компания не несет ответственности и отель обязуется сам взимать оплату с гостя по
                                своим расценкам.</p>
                            <p style="text-align: justify"><b>Информация для отеля!</b> Убедительно, просим Вас в день
                                выезда гостей, выставить счет-фактуры на сайте https://my.soliq.uz</p>
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
                            @include('BookingShared::_partials.manager_requisites')
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
