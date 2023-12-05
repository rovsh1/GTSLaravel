@extends('layout.layout')

@push('css')
    <style>
        td.first {
            border-top: 2px solid;
        }

        td.booking-period, td.service-name, td.service-number {
            font-weight: bold;
            color: #073763;
        }

        td.booking-period {
            font-size: 16px;
        }

        table tr.padding-bottom td {
            padding-bottom: 10px;
        }

        table tr.padding-top td {
            padding-top: 10px;
        }

        table tr.summary td {
            padding: 20px 0;
            font-size: 22pt;
            font-weight: bold;
        }
    </style>
@endpush

@section('content')
    <table>
        <tbody>
        <tr>
            <td class="text-align-left" style="width: 250px">
                <img src="var:logo" alt="" width="250">
            </td>
            <td style="width: 650px; ">
                <table class="text-align-right">
                    <tbody>
                    <tr>
                        <td class="title">ИНВОЙС № {{ $order->number }}</td>
                    </tr>
                    <tr>
                        <td>Дата создания: {{ $invoice->createdAt }}</td>
                    </tr>
                    <tr>
                        <td>{{$company->name}}</td>
                    </tr>
                    <tr>
                        <td>Тел: {{$company->phone}}</td>
                    </tr>
                    <tr>
                        <td>E-mail: {{$company->email}}</td>
                    </tr>
                    <tr>
                        <td>{{$company->legalAddress}}</td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>

        <tr>
            <td colspan="5">
                <table>
                    <tbody>
                    <tr>
                        <td>
                            <table>
                                <tbody>
                                <tr class="first">
                                    <td>Клиент: {{ $client->name }}</td>
                                </tr>
                                <tr>
                                    <td>Договор: {{ $client->name }}</td>
                                </tr>
                                <tr>
                                    <td>Адрес: {{ $client->address  }}</td>
                                </tr>
                                <tr>
                                    <td>Телефон: {{ $client->phone }}</td>
                                </tr>
                                <tr class="last">
                                    <td>Email: {{ $client->email }}</td>
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
                <table>
                    <tbody>
                    @foreach($services as $service)
                        @include('invoice._partials.service')
                    @endforeach
                    <tr class="first summary">
                        <td class="text-align-left">ИТОГО К ОПЛАТЕ</td>
                        <td colspan="4" class="text-align-right">{{ $invoice->totalAmount }} {{ $order->currency }}</td>
                    </tr>
                    <tr>
                        <td colspan="5">
                            <p><span style="text-decoration: underline;">Важное примечание:</span> <i>Оплата должна быть
                                    произведена в полной мере, без учета комиссии межбанковских переводов, налогов и
                                    сборов. В случае необходимости уплаты таковых, все расчеты должны быть произведены
                                    заказчиком сверх сумм, указанных в настоящем инвойсе.</i>
                            </p>
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
                                    <td>Директор: {{ $company->signer }}</td>
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
