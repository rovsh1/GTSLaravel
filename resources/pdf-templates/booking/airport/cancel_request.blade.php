@extends('booking.layout.layout')
@props(['guests' => []])

@section('content')
    <table>
        <tbody>
        <tr>
            <td class="text-align-left" style="width: 250px"><img src="var:logo" alt="" width="250"></td>
            <td style="width: 650px; ">
                <table class="text-align-right">
                    <tbody>
                    <tr>
                        <td>{{$company}}</td>
                    </tr>
                    <tr>
                        <td>Тел: {{$phone}}</td>
                    </tr>
                    <tr>
                        <td>E-mail: {{$email}}</td>
                    </tr>
                    <tr>
                        <td>{{$address}}</td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding-top: 15px;" colspan="2" class="text-align-right">Директору</td>
        </tr>
        <tr>
            <td>{{$company}}</td>
            <td class="text-align-right">{{$airportName}}</td>
        </tr>
        <tr>
            <td>{{$cityAndCountry}}</td>
            <td class="text-align-right">{{$airportDirector}}</td>
        </tr>
        <tr>
            <td class="title text-align-center" colspan="2">ОТМЕНА БРОНИ НА {{$serviceTypeName}}</td>
        </tr>
        <tr>
            <td class="text-align-center" colspan="2" style="padding-top: 20px;padding-bottom: 20px">Согласно договору №
                {{$contractNumber}} от {{$contractDate}}, ИНН: {{$inn}}
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
                                        Номер
                                        (ID):
                                    </td>
                                    <td style="font-size: 24px; font-weight: bold; color: red">{{$reservNumber}}</td>
                                    <td class="text-align-right" colspan="2">
                                        <b>Дата и время создания: {{$reservCreatedAt}}</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="top-table-left"></td>
                                    <td></td>
                                    <td class="text-align-right" colspan="2">
                                        <b>Дата и время изменения: {{$reservCancelledAt}}</b>
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
                "GotoStans" выражает Вам своё почтение и просит Вас организовать <br/> {{$serviceTypeName}} для
                следующих
                лиц
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
                        <td class="text-align-left" colspan="4"><b>{{$serviceName}}</b></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Дата прилёта: {{$departureDate}}</td>
                        <td colspan="3">Время прилёта: {{$time}}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Номер рейса: {{$flightNumber}}</td>
                        <td colspan="2">Аэропорт: {{$airportName}}</td>
                    </tr>
                    <tr>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Туристы ({{$guestsCount}})</td>
                    </tr>
                    @foreach($guests as $index => $guest)
                        <tr class="{{$index === $guestsCount-1 ? 'last' : ''}}">
                            <td></td>
                            <td colspan="4">
                            <span>
                                {{++$index}}. {{$guest->fullName()}}, {{$guest->gender() === \Module\Shared\Enum\GenderEnum::MALE ? 'Мужской' : 'Женский' }}, {{$countryNamesById[$guest->countryId()] ?? ''}}
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
                                    <td>{{$company}}</td>
                                </tr>
                                <tr>
                                    <td>Директор: <b>{{$signer}}</b></td>
                                </tr>
                                <tr>
                                    <td> </td>
                                </tr>
                                <tr>
                                    <td>Менеджер: <b>{{$managerName}}</b></td>
                                </tr>
                                <tr>
                                    <td>E-mail: {{$managerEmail}}</td>
                                </tr>
                                <tr>
                                    <td>Мобильный номер: {{$managerPhone}}</td>
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
