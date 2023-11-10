@extends('layout.layout')
@props(['guests' => []])

@section('content')
    <table>
        <tbody>
        @include('_partials.company_requisites_header')
        <tr>
            <td style="padding-top: 15px;" colspan="2" class="text-align-right">Директору</td>
        </tr>
        <tr>
            <td>{{ $company->name }}</td>
            <td class="text-align-right">{{ $airport->name }}</td>
        </tr>
        <tr>
            <td>{{ $company->region }}</td>
            <td class="text-align-right">{{ $airport->director }}</td>
        </tr>
        <tr>
            <td class="title text-align-center" colspan="2">БРОНЬ НА {{ strtoupper($service->typeName) }}</td>
        </tr>
        <tr>
            <td class="text-align-center" colspan="2" style="padding-top: 20px; padding-bottom: 20px">Согласно договору
                №{{ $contract->number }} от {{ $contract->date }}, ИНН: {{ $contract->inn }}
            </td>
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
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="text-align-center" style="padding-top: 20px;padding-bottom: 20px">Компания ООО
                "GotoStans" выражает Вам своё почтение и просит Вас организовать <br/> {{ $service->typeName }} для
                следующих лиц
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-top: 20px;">
                <table class="services">
                    <thead>
                    <tr>
                        <th class="text-align-center" style="width: 5%;">№</th>
                        <th class="text-align-left" colspan="4">Информация об услуге</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="first">
                        <td class="text-align-center"><b>1</b></td>
                        <td class="text-align-left" colspan="4"><b>{{ $service->title }}</b></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Дата прилёта: {{ $date }}</td>
                        <td colspan="3">Время прилёта: {{$time}}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Номер рейса: {{ $flightNumber }}</td>
                        <td colspan="2">Аэропорт: {{ $airport->name }}</td>
                    </tr>
                    <tr>
                        <td></td>
                    </tr>
                    @php
                        $guestsCount = count($guests);
                    @endphp
                    <tr>
                        <td></td>
                        <td>Туристы ({{ $guestsCount }})</td>
                    </tr>
                    @foreach($guests as $index => $guest)
                        <tr class="{{$index === $guestsCount-1 ? 'last' : ''}}">
                            <td></td>
                            <td colspan="4">
                            <span class="red">
                                {{++$index}}. {{ $guest->fullName }}, {{ $guest->gender }}, {{ $guest->countryName }}
                            </span>
                            </td>
                        </tr>
                    @endforeach
                    <tr class="first">
                        <td></td>
                        <td class="text-align-left" colspan="4">
                            Оплату перечислением гарантируем!
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
            <td class="text-align-right" style="width: 250px"><img src="var:stamp_only" alt="" width="250"></td>
        </tr>
        </tbody>
    </table>
@endsection
