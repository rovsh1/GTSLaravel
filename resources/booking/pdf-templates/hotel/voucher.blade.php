<style>
    body {
        font-size: 1.45em;
        font-family: Tahoma, sans-serif;
    }

    table {
        border-spacing: 0;
        border-collapse: collapse;
        width: 100%;
    }

    .title {
        padding: 20px 0;
        font-size: 2.1em;
    }

    .text-align-left {
        text-align: left;
    }

    .text-align-right {
        text-align: right;
    }

    .text-align-center {
        text-align: center;
    }

    table tr.first td {
        padding-top: 10px;
        border-top: 2px solid;
    }

    table tr.last td {
        padding-bottom: 10px;
        border-bottom: 2px solid;
    }
</style>
<table>
    <tbody>

    @include('hotel._partials.company_requisites_header')

    <tr>
        <td class="title text-align-center" colspan="2">ВАУЧЕР № {{$number}}</td>
    </tr>
    <tr>
        <td class="text-align-right" colspan="2">{{$createdAt}}</td>
    </tr>
    <tr>
        <td colspan="2">
            <table>
                <tbody>
                <tr>
                    <td style="width: 55%">
                        <table>
                            <tr>
                                <td>Тип услуги: Проживание</td>
                            </tr>
                            <tr>
                                <td>Гостиница: {{$hotelName}}</td>
                            </tr>
                            <tr>
                                <td>Адрес: г.{{$cityName}}, {{$hotelAddress}}</td>
                            </tr>
                            <tr>
                                <td>Телефон: {{$hotelPhone}}</td>
                            </tr>
                            <tr>
                                <td>Дата заезда: {{$reservStartDate}}</td>
                            </tr>
                            <tr>
                                <td>Дата выезда: {{$reservEndDate}}</td>
                            </tr>
                            <tr>
                                <td>Количество ночей: {{$reservNightCount}}</td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 45%; text-align: left">
                        <table>
                            <tr>
                                <td style="font-size: 24px; font-weight: bold; color: red">Номер брони: {{$reservNumber}}</td>
                            </tr>
                            <tr>
                                <td>Статус брони: {{$reservStatus}}</td>
                            </tr>
                            <tr>
                                <td>&ensp;</td>
                            </tr>
                            <tr>
                                <td>&ensp;</td>
                            </tr>
                            <tr>
                                <td>&ensp;</td>
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
                    <th style="width: 5%;">№</th>
                    <th class="text-align-left" style="width: 70%;">Информация о размещении</th>
                    <th style="width: 25%;">Количество номеров</th>
                </tr>
                </thead>
                <tbody>

                @foreach($rooms as $roomIndex => $room)
                    @if($roomIndex === 0)
                        <tr class="first">
                    @else
                        <tr>
                    @endif
                            <td>
                                <b>{{++$roomIndex}}</b>
                            </td>
                            <td>
                                <b>{{$room->roomInfo()->name()}}</b>
                            </td>
                            <td>
                                <b>1</b>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                Питание: без завтрака
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                Туристы ({{$room->guests()->count()}}):
                            </td>
                            <td></td>
                        </tr>
                        @foreach($room->guests() as $index => $guest)
                            <tr>
                                <td></td>
                                <td>
                                    {{++$index}}. {{$guest->fullName()}}, {{$guest->gender() === \Module\Shared\Enum\GenderEnum::MALE ? 'Мужской' : 'Женский' }}, {{$countryNamesById[$guest->countryId()] ?? ''}}
                                </td>
                                <td></td>
                            </tr>
                        @endforeach
                        <tr>
                            <td></td>
                            <td>
                                Время заезда: {{$room->details()->earlyCheckIn()?->timePeriod()->from() ?? $hotelDefaultCheckInTime}}
                            </td>
                            <td></td>
                        </tr>

                        <tr>
                            <td></td>
                            <td>
                                Время выезда: {{$room->details()->lateCheckOut()?->timePeriod()->to() ?? $hotelDefaultCheckOutTime}}
                            </td>
                            <td></td>
                        </tr>

                        <tr>
                            <td></td>
                            <td>
                                Примечание (запрос в отель, ваучер):
                            </td>
                            <td></td>
                        </tr>
                @endforeach

                <tr>
                    <td></td>
                    <td>
                        <table>
                            <tbody>
                                <tr>
                                    <td style="width: 25%">Условия отмены:</td>
                                    <td style="width: 75%">{hotelCancelPeriod}</td>
                                </tr>
                            {cancelPeriods}
                            </tbody>
                        </table>
                    </td>
                    <td>&ensp;</td>
                </tr>

                <tr class="first">
                    <td>&ensp;</td>
                    <td>
                        <p>Внимание! Полная оплата должна быть произведена до прибытия.</p>
                        <p>Ваучер недействителен в случае не полной оплаты.</p>
                    </td>
                    <td>&ensp;</td>
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
                        @include('hotel._partials.manager_requisites')
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
        <td class="text-align-right" style="width: 250px"><img src="var:stamp" alt="" width="250"/></td>
    </tr>
    </tbody>
</table>
