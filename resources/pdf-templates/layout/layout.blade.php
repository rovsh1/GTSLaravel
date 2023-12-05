<html lang="ru">
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

        table tr.first td, table tr.first th {
            padding-top: 10px;
            border-top: 2px solid;
        }

        table tr.last td, table tr.last th {
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

        span.red {
            font-weight: bold;
            color: red;
        }
    </style>
    <title></title>

    @stack('css')
</head>
<body>
@yield('content')
</body>
</html>
