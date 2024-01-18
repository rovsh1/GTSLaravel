<!DOCTYPE HTML
  PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
  xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="x-apple-disable-message-reformatting">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title></title>
  <style type="text/css">
    @media only screen and (min-width: 620px) {
      .mail-row {
        width: 600px !important;
      }
    }

    @media (max-width: 620px) {
      .mail-row {
        width: 100% !important;
      }

      .mail-container .mail-row .mail-content {
        padding: 20px !important;
      }
    }

    body {
      margin: 0;
      padding: 0;
    }

    .mail-container table,
    .mail-container tr,
    .mail-container td {
      vertical-align: top;
      border-collapse: collapse;
    }

    .mail-container p,
    .mail-container td,
    .mail-container a,
    .mail-container h2 {
      margin: 0;
      color: #000000;
      font-family: Arial, Helvetica, sans-serif !important;
      font-size: 13px;
    }

    .mail-container .m0 {
      margin-bottom: 0 !important;
    }

    .mail-container {
      background-color: #e7e7e7;
    }

    .mail-container a {
      color: #0000ee;
      text-decoration: underline;
    }

    .mail-container .mail-row {
      margin: 0 auto;
      background-color: #fff;
    }

    .mail-container .mail-row table {
      border-collapse: collapse;
      table-layout: fixed;
      border-spacing: 0;
      mso-table-lspace: 0pt;
      mso-table-rspace: 0pt;
      vertical-align: top;
      margin: 0 auto;
      width: 100%
    }

    .mail-container .mail-row table td {
      padding: 5px 0;
      vertical-align: top;
    }

    .mail-container .mail-row table td.title-column {
      width: 40%;
    }

    .mail-container .mail-row table td.value-column {
      width: 60%;
      padding-left: 10px;
    }

    .mail-container .mail-row .mail-header {
      padding: 35px 10px 35px;
      text-align: center;
      border-bottom: 1px solid #0e80f6;
    }

    .mail-container .mail-row .mail-footer {
      padding: 30px 40px;
      background-color: #0e80f6;
    }

    .mail-container .mail-row .mail-content {
      padding: 20px 65px;
    }

    .mail-container .mail-row .text-block,
    .mail-container .mail-row .table-block {
      margin-bottom: 20px;
    }

    .mail-container .mail-row .table-block {
      background-color: #f8f8fc;
      padding: 20px;
    }

    .mail-container .mail-row .mail-footer p,
    .mail-container .mail-row .mail-footer td,
    .mail-container .mail-row .mail-footer a,
    .mail-container .mail-row .mail-footer h2 {
      color: #fff !important;
    }

    .mail-container .mail-row table.with-divider tr td {
      border-bottom: 1px solid #e6e6e6;
    }

    .mail-container .mail-row table.with-divider tr:last-child td {
      border-bottom: none !important;
    }

    .mail-container .mail-row table.with-divider tr.disable-divider td {
      border-bottom: none !important;
    }
  </style>
</head>

<body>
  
</body>
<body>
  <div class="mail-container">
    <div class="mail-row">
      <div class="mail-header">
        <img align="center" border="0" src="{{ asset('apps/site/public/images/logo.png') }}" alt="image" title="image"
          style="outline: none;text-decoration: none;max-width: 200px;" width="200px" />
      </div>
      @yield('content')
      <div class="mail-footer">
        <div class="text-block">
          <h2 style="font-size: 23px;">С уважением,</h2>
        </div>
        <div class="text-block">
          <p style="font-size: 15px !important;">Сардор Зоитов</p>
        </div>
        <div class="text-block m0">
          <p>
            <a href="mailto:example@gmail.com" title="Почта" target="_blank"
              style="margin-right: 10px; color: transparent !important;">
              <img src="{{ asset('apps/site/public/images/mail.png') }}" alt="Почта" title="Почта" width="32"
                style="outline: none;text-decoration: none;max-width: 32px !important;" />
            </a>
            <a href="tel:+8888888" title="Телефон" target="_blank" style="color: transparent !important;">
              <img src="{{ asset('apps/site/public/images/phone.png') }}" alt="Телефон" title="Телефон" width="32"
                style="outline: none;text-decoration: none;max-width: 32px !important" />
            </a>
          </p>
        </div>
      </div>
    </div>
  </div>
</body>

</html>
