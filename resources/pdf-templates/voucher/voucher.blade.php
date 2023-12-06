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
                        <td class="title">ВАУЧЕР №{{ $voucher->number }}</td>
                    </tr>
                    <tr>
                        <td>Дата создания: {{ $voucher->createdAt }}</td>
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
            <td style="padding-top: 20px;" colspan="2">
                <table>
                    <tbody>
                    @foreach($services as $service)
                        @include('voucher._partials.service')
                    @endforeach
                    </tbody>
                </table>
            </td>
        </tr>

        <tr>
            <td>
                <table>
                    <tbody>
                    <tr>
                        <td style="width: 500px;" colspan="2">
                            <table>
                                <tbody>
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
                {{--                <img src="var:stamp" alt="" width="250"/>--}}
            </td>
        </tr>

        </tbody>
    </table>
@endsection
