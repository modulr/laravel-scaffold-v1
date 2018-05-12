<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>QR</title>
        <style media="all">
        @page {
            size: 2.4in 4in landscape;
            margin: 0;
        }
        @page :first {
            margin: 0;
        }
        html, body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 12pt;
            margin: 0;
            padding: 0;
            border: 0;
        }
        table {
            border: none;
            border-collapse: collapse;
            border-spacing: 0;
        }
        .title {
            color: #333;
            text-transform: uppercase;
            font-size: 4pt;
            display: block;
        }
        .qr img {
            height: 100px;
            width: 100px;
        }
        .code {
            text-align: center;
            font-size: 80pt;
            line-height: 1;
        }
        </style>
    </head>
    <body>
        <div class="code">
            {{ $autopart->id }}
        </div>
        <table>
            <tr>
                <td class="qr">
                    <img src="{{ asset($autopart->qr) }}">
                    {{ asset($autopart->qr) }}
                </td>
                <td>
                    <table>
                        <tr>
                            <td class="make">
                                <span class="title">Marca</span>
                                {{ $autopart->make->name }}
                            </td>
                        </tr>
                        <tr>
                            <td class="model">
                                <span class="title">Modelo</span>
                                {{ $autopart->model->name }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="years">
                                <span class="title">Años</span>
                                @foreach ($autopart->years as $year)
                                    <span>{{ $year->name }} |</span>
                                @endforeach
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>
