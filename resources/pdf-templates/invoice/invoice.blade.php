@extends('layout.layout')

@section('content')
    <table>
        <tbody>
        @include('_partials.company_requisites_header')
        <tr>
            <td class="title text-align-center" colspan="2">ИНВОЙС № {{ $invoice->number }}</td>
        </tr>
        <tr>
            <td class="text-align-right" colspan="2">{{ $invoice->createdAt }}</td>
        </tr>
        <tr>
            <td colspan="2">
                <table>
                    <tbody>
                    <tr>
                        <td style="width: 45%; text-align: left">
                            <table>
                                <tr>
                                    <td style="font-size: 24px; font-weight: bold; color: red">Номер
                                        заказа: {{ $order->number }}</td>
                                </tr>
                                <tr>
                                    <td>Статус заказа: {{ $order->status }}</td>
                                </tr>
                                <tr>
                                    <td>Клиент: {{ $client->name }}</td>
                                </tr>
                                <tr>
                                    <td>Адрес: {{ $client->address }}</td>
                                </tr>
                                <tr>
                                    <td>Телефон: {{ $client->phone }}</td>
                                </tr>
                                <tr>
                                    <td>&ensp;</td>
                                </tr>
                                <tr>
                                    <td>&ensp;</td>
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
                        <th style="width: 3%;">№</th>
                        <th class="text-align-left" style="width: 52%;">Информация об услуге</th>
                        <th style="width: 15%;">Количество</th>
                        <th style="width: 15%;">Цена, {{ $order->currency }}</th>
                        <th style="width: 15%;">Итого, {{ $order->currency }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($bookings as $booking)
                        @include('invoice._partials.booking')
                    @endforeach
                    <tr class="first">
                        <td class="text-align-right" colspan="5" style="padding-bottom: 20px">
                            Общая сумма: {totalSum}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5">
                            <p>Внимание! Оплата должна быть произведена в полной мере, без учета комиссии межбанковских
                                переводов, налогов и сборов. В случае необходимости уплаты таковых, все расчеты
                                должны быть произведены заказчиком сверх сумм, указанных в настоящем инвойсе.</p>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>

        @include('_partials.bank_requisites')

        <tr>
            <td>
                <table>
                    <tbody>
                    <tr>
                        <td style="width: 500px;">
                            <table>
                                <tbody>
                                <tr>
                                    <td>{{ $company->name }}</td>
                                </tr>
                                <tr>
                                    <td>Директор: <b>{{ $company->signer }}</b></td>
                                </tr>
                                <tr>
                                    <td> </td>
                                </tr>
                                <tr>
                                    <td>Менеджер: <b>{{ $manager->fullName }}</b></td>
                                </tr>
                                <tr>
                                    <td>E-mail: {{ $manager->email }}</td>
                                </tr>
                                <tr>
                                    <td>Мобильный номер: {{ $manager->phone }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
            <td class="text-align-right" style="width: 250px">
                <img src="var:stamp" alt="" width="250"/>
            </td>
        </tr>
        </tbody>
    </table>
@endsection
