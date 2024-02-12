<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking request</title>
    <style>
        .document-header a,
        .document-header b,
        .document-header p {
            font-family: Arial, Helvetica, sans-serif !important;
        }

        .document-content li,
        .document-content a,
        .document-content p,
        .document-content i,
        .document-content u,
        .document-content b,
        .document-content div,
        .document-footer p {
            font-size: 13px;
            font-family: Arial, Helvetica, sans-serif !important;
        }

        .document-wrapper p {
            margin: 0;
            padding: 0;
        }

        .document-wrapper .clear-both {
            overflow: auto;
        }

        .document-wrapper .column {
            float: left;
        }

        .document-wrapper .w-10 {
            width: 10%;
        }

        .document-wrapper .w-15 {
            width: 15%;
        }

        .document-wrapper .w-17 {
            width: 17.5%;
        }

        .document-wrapper .w-25 {
            width: 25%;
        }

        .document-wrapper .w-28 {
            width: 28%;
        }

        .document-wrapper .w-45 {
            width: 45%;
        }

        .document-wrapper .w-50 {
            width: 50%;
        }

        .document-wrapper .w-55 {
            width: 55%;
        }

        .document-wrapper .w-72 {
            width: 72%;
        }

        .document-wrapper .w-75 {
            width: 75%;
        }

        .document-wrapper .w-85 {
            width: 85%;
        }

        .document-wrapper .text-right {
            text-align: right;
        }

        .document-wrapper .text-center {
            text-align: center;
        }

        .document-wrapper .fs-big {
            font-size: 18px;
        }

        .document-wrapper .mb-default {
            margin-bottom: 10px;
        }

        .document-footer .mark {
            width: 150px;
        }

        .document-header {
            padding: 0 9px;
            margin-bottom: 20px;
        }

        .document-header .document-header__left {
            width: 40%;
            text-align: left;
        }

        .document-header .document-header__right {
            width: 60%;
            text-align: right;
        }

        .document-header .document-header__right .document-header-title {
            font-size: 22px !important;
        }

        .document-wrapper .document-content .document-content-header p b,
        .document-wrapper .document-content .document-content-header p {
            font-size: 20px !important;
        }

        .document-wrapper .cancelled {
            color: red !important;
        }

        .document-wrapper .changed {
            color: blue !important;
        }

        .document-wrapper .new {
            color: #000 !important;
            background-color: #00FF00 !important;
        }


        .document-header .document-header__right .document-header-description,
        .document-header .document-header__right .document-header-description a {
            font-size: 11px;
        }

        .document-header .document-header__left img {
            width: 215px;
        }

        .client-info {
            padding: 9px 2px;
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            margin-bottom: 10px;
        }

        .total-amount {
            padding: 0px 9px;
            margin-bottom: 15px;
        }

        .total-amount p b {
            font-size: 19px !important;
        }

        .note {
            margin-bottom: 15px;
        }

        .service-item ol {
            margin: 0;
        }

        .service-item {
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            padding: 0 9px;
            margin-bottom: 9px;
        }

        .service-item ol li {
            padding-left: 10px;
        }

        .service-title {
            orphans: 0;
        }

        .footer-elements-fix-height {
            height: 150px !important;
        }

    </style>
</head>

<body>
<div class="document-wrapper">
    <div class="document-header clear-both">
        @yield('header')
    </div>

    <div class="document-content">
        @yield('content')
    </div>

    <div class="document-footer">
        @yield('footer')
    </div>
</div>
</body>

</html>
