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
        <td class="title text-align-center" colspan="2">ИНВОЙС № {{$number}}</td>
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
                                <td>Адрес: г.{{$cityName}}, {hotelAddress}</td>
                            </tr>
                            <tr>
                                <td>Телефон: {{$hotelPhone}}</td>
                            </tr>
                            <tr>
                                <td><b>{{$reservStartDate}}</b> - <b>{{$reservEndDate}}</b></td>
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
                                <td>Статус брони: {reservStatus}</td>
                            </tr>
                            <tr>
                                <td>Клиент: {client}</td>
                            </tr>
                            <tr>
                                <td>Адрес: {clientAddress}</td>
                            </tr>
                            <tr>
                                <td>Телефон: {clientPhone}</td>
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
                    <th class="text-align-left" style="width: 52%;">Информация о размещении</th>
                    <th style="width: 15%;">Количество номеров</th>
                    <th style="width: 15%;">Цена, UZS</th>
                    <th style="width: 15%;">Итого, UZS</th>
                </tr>
                </thead>
                <tbody>
                {rooms}
                <tr>
                    <td>&ensp;</td>
                    <td>
                        <table>
                            <tbody>
                            <tr>
                                <td style="width: 35%">Условия отмены:</td>
                                <td style="width: 65%">{hotelCancelPeriod}</td>
                            </tr>
                            {cancelPeriods}
                            </tbody>
                        </table>
                    </td>
                    <td>&ensp;</td>
                </tr>
                <tr class="first">
                    <td class="text-align-right" colspan="5" style="padding-bottom: 20px">Общая сумма: {totalSum}</td>
                </tr>
                <tr>
                    <td colspan="5">
                        <p>Внимание! Оплата должна быть произведена в полной мере, без учета комиссии межбанковских переводов, налогов и сборов. В случае необходимости уплаты таковых, все расчеты
                            должны быть произведены заказчиком сверх сумм, указанных в настоящем инвойсе.</p>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>

    {bankRequisites}

    <tr>
        <td>
            <table>
                <tbody>
                <tr>
                    <td style="width: 500px;">
                        <table>
                            <tbody>
                            <tr>
                                <td>{company}</td>
                            </tr>
                            <tr>
                                <td>Директор: {signer}.</td>
                            </tr>
                            <tr>
                                <td>&ensp;</td>
                            </tr>
                            <tr>
                                <td>Менеджер: {managerName}</td>
                            </tr>
                            <tr>
                                <td>E-mail: {managerEmail}</td>
                            </tr>
                            <tr>
                                <td>Мобильный номер: {managerPhone}</td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
        <td class="text-align-right" style="width: 250px"><img src="var:stamp" alt="" width="250"/></td>
    </tbody>
</table>
