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

        td.first {
            border-top: 2px solid;
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
        <td style="width: 650px;">
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
        <td class="title text-align-center" colspan="2">ВАУЧЕР № {number}</td>
    </tr>
    <tr>
        <td colspan="2" class="text-align-right">{airportReservCreatedAt}</td>
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
                                <td>Тип услуги: {serviceType}</td>
                                <td style="font-size: 24px; font-weight: bold; color: red">Номер брони:
                                    {airportReservNumber}
                                </td>
                            </tr>
                            <tr>
                                <td>Клиент: {clientName}</td>
                                <td>Статус брони: {airportReservStatus}</td>
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
                <thead>
                <tr>
                    <th style="width: 3%;">№</th>
                    <th class="text-align-left" colspan="2" style="width: 82%;">Информация об услуге</th>
                    <th style="width: 15%;">Количество</th>
                </tr>
                </thead>
                <tbody>
                <tr class="first">
                    <td class="text-align-center"><b>1</b></td>
                    <td class="text-align-left" colspan="2"><b>{serviceName}</b></td>
                    <td class="text-align-center">{tourist_count}</td>
                </tr>
                {serviceOptions}
                <tr>
                    <td class="first last"></td>
                    <td colspan="2" class="first last">
                        <table>
                            <tbody>{cancelTerms}</tbody>
                        </table>
                    </td>
                    <td class="first last"></td>
                </tr>
                <tr>
                    <td colspan="5">
                        <p>Внимание! Ваучер не действителен в случае неполной оплаты.<br> Полная предоплата должна быть
                            сделана до прибытия.</p>
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
                                <td> </td>
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
        <td class="text-align-right" style="width: 250px"><img src="var:stamp_only" alt="" width="250"></td>
    </tr>
    </tbody>
</table>
</body>
</html>