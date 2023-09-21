<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
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

        .services td {
            padding: 10px;
        }

        .services td.first {
            padding-top: 20px;
        }

        td.last {
            border-bottom: 2px solid;
        }
    </style>
</head>
<body>
<table>
    <tbody>
    <tr>
        <td class="text-align-left" style="width: 250px"><img src="var:logo" alt="" width="250"></td>
        <td style="width: 650px; ">
            <table class="text-align-right">
                <tbody>
                <tr>
                    <td>{company}</td>
                </tr>
                <tr>
                    <td>Тел: {phone}</td>
                </tr>
                <tr>
                    <td>E-mail: {email}</td>
                </tr>
                <tr>
                    <td>{address}</td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding-top: 15px;" colspan="2" class="text-align-right">Директору</td>
    </tr>
    <tr>
        <td>{company}</td>
        <td class="text-align-right">{airportName}</td>
    </tr>
    <tr>
        <td>{city_country}</td>
        <td class="text-align-right">{airportDirector}</td>
    </tr>
    <tr>
        <td class="title text-align-center" colspan="2">ОТМЕНА БРОНИ НА {serviceTypeName}</td>
    </tr>
    <tr>
        <td class="text-align-center" colspan="2" style="padding-top: 20px;padding-bottom: 20px">Согласно договору №
            {contractNumber} от {contractDate}, ИНН: {inn}
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
                                <td class="top-table-left" style="font-size: 24px; font-weight: bold; color: red">Номер
                                    (ID):
                                </td>
                                <td style="font-size: 24px; font-weight: bold; color: red">{airportReservNumber}</td>
                                <td class="text-align-right" colspan="2"><b>Дата и время создания:
                                        {airportReservCreatedAt}</b></td>
                            </tr>
                            <tr>
                                <td class="top-table-left"></td>
                                <td></td>
                                <td class="text-align-right" colspan="2"><b>Дата и время отмены:
                                        {airportReservCancelledAt}</b></td>
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
            "GotoStans" выражает Вам своё почтение и просит Вас организовать <br/> {serviceTypeName} для следующих лиц
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
                    <td class="text-align-left" colspan="4"><b>{serviceName}</b></td>
                </tr>
                {serviceOptions}
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
                                <td>{company}</td>
                            </tr>
                            <tr>
                                <td>Директор: <b>{signer}</b></td>
                            </tr>
                            <tr>
                                <td> </td>
                            </tr>
                            <tr>
                                <td>Менеджер: <b>{managerName}</b></td>
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
        <td class="text-align-right" style="width: 250px"><img src="var:stamp_only" alt="" width="250"></td>

    </tr>
    </tbody>
</table>
</body>
</html>
