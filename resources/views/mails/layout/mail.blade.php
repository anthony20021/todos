<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style type="text/css">
        body {
            margin: 0;
            padding: 0;
            min-width: 100% !important;
            width: 100% !important;
            font-family: 'Calibri', 'Gill Sans', 'Gill Sans MT', 'Trebuchet MS', sans-serif;
            /* background-color:rgb(244,244,244) */
        }

        table.content {
            width: 100%;
            max-width: 600px;
            background-color: #fff;
            box-shadow: 0 7px 7px #ddd;
            font-family: "Calibri", sans-serif
        }

        table.table {
            border-collapse: collapse;
        }

        table.table th,
        table.table td {
            border: 1px solid black;
        }

        .table .thead-dark th {
            color: #fff;
            background-color: #343a40;
            border-color: #454d55;
        }

        @media only screen and (min-device-width:601px) {
            .content {
                width: 600px !important
            }
        }

        .mail-body td {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            color: #333
        }

        .mail-confirm {
            display: block;
            margin: 0 auto;
            border: none;
            background: #0c6e34;
            color: white;
            padding: 20px 30px;
            border-radius: 50px;
            font-size: 16px;
            text-decoration: none;
            width: 200px
        }

    </style>
</head>

<body>
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                @include('mails.layout.header')
            </td>
        </tr>
        <tr>
            <td>
                <table>
                    <tbody class="mail-body">
                        <tr>
                            <td style="width: 100%" colspan="2">
                                @if (isset($simple_body))
                                    {!! nl2br($simple_body) !!}
                                @else
                                    @yield('content')
                                @endif
                            </td>
                        </tr>
                    </tbody>
                    @if (isset($user))
                        <tfoot>
                            @include('mails.layout.footer')
                        </tfoot>
                    @endif
                </table>
            </td>
        </tr>
    </table>
</body>
