@extends('pdf-templates.layout.layout')

@section('content')
    <table>
        <tbody>
        @include('pdf-templates._partials.company_requisites_header')
        <tr>
            <td class="title text-align-center" colspan="2">БРОНИРОВАНИЕ ТРАНСПОРТА</td>
        </tr>
        <tr>
            <td colspan="2">
                <table>
                    <tbody>
                    <tr>
                        <td>
                            <table>
                                <tbody>
                                <tr>
                                    <td class="top-table-left" style="font-size: 24px; font-weight: bold; color: red">
                                        Номер (ID):
                                    </td>
                                    <td style="font-size: 24px; font-weight: bold; color: red">{{ $booking->number }}</td>
                                    <td class="text-align-right" colspan="2">
                                        <b>Дата и время создания: {{ $booking->createdAt }}</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="top-table-left">Клиент:</td>
                                    <td><b>{{ $client->name }}</b></td>
                                </tr>
                                <tr>
                                    <td class="top-table-left">Количество туристов:</td>
                                    <td><b>{{ $guestsCount }}</b></td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-top: 20px;">
                <table class="services">
                    <thead>
                    <tr>
                        <th class="text-align-center" style="width: 5%;">№</th>
                        <th class="text-align-left">Информация об услуге</th>
                        <th class="text-align-center">Количество авто</th>
                        <th class="text-align-center">Цена, UZS</th>
                        <th class="text-align-center">Итого, UZS</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="first">
                        <td class="text-align-center"><b>#</b></td>
                        <td class="text-align-left" colspan="4"><b>{{ $service->title }}</b></td>
                    </tr>

                    @include('pdf-templates.transfer._partials.cars')
                    @include('pdf-templates.booking.transfer._partials.details')

                    <tr class="padding-bottom">
                        <td></td>
                        <td colspan="4">ФИО клиента и место подачи/отправки авто: {{ $booking->note }}</td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr class="first">
            <td colspan="2" class="text-align-right"><b>Стоимость брони: {{ $booking->supplierPrice->amount }}</b></td>
        </tr>
        <tr>
            <td>
                <table>
                    <tbody>
                    <tr>
                        <td style="width: 500px;">
                            @include('pdf-templates._partials.manager_requisites')
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
            <td class="text-align-right" style="width: 250px"><img src="var:stamp" alt="" width="250"></td>
        </tr>
        </tbody>
    </table>
@endsection
