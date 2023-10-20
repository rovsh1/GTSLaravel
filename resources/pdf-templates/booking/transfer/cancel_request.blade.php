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
        padding: 50px 0 30px;
        font-size: 2.1em;
        color: #0000ff;
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

    .top-table-left {
        width: 250px;
    }
</style>
<table>
    <tbody>
    @include('pdf-templates._partials.company_requisites_header')
    <tr>
        <td class="title text-align-center" colspan="2">ОТМЕНА БРОНИ НА ТРАНСПОРТ</td>
    </tr>
    <tr>
        <td colspan="2">
            <table>
                <tbody>
                <tr>
                    <td>
                        <table>
                            <tbody><tr>
                                <td class="top-table-left" style="font-size: 24px; font-weight: bold; color: red">Номер (ID): </td>
                                <td style="font-size: 24px; font-weight: bold; color: red">{transferNumber}</td>
                                <td class="text-align-right" colspan="2"><b>Дата и время отмены: {transferCancelledAt}</b></td>
                            </tr>
                            <tr>
                                <td class="top-table-left">Клиент: </td>
                                <td><b>{clientName}</b></td>
                                <td class="text-align-right" colspan="2"><b>Дата и время создания: {transferCreatedAt}</b></td>
                            </tr>
                            <tr>
                                <td class="top-table-left">Количество туристов: </td>
                                <td><b>{touristCount}</b></td>
                            </tr>
                            </tbody></table>
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
                    <td class="text-align-left" colspan="4"><b>{serviceName}</b></td>
                </tr>
                {cars}
                {serviceOptions}</tbody>
            </table>
        </td>
    </tr>
    <tr class="first">
        <td colspan="2" class="text-align-right"><b>Стоимость брони: {transferPrice}</b></td>
    </tr>
    <tr>
        <td>
            <table>
                <tbody>
                <tr>
                    <td style="width: 500px;">
                        @include('pdf-templates.hotel._partials.manager_requisites')
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
        <td class="text-align-right" style="width: 250px"><img src="var:stamp" alt="" width="250"/></td>
    </tr>
    </tbody>
</table>
