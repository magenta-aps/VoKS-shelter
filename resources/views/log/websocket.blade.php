<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">

    <title>Log viewer</title>
    <style type="text/css">
        html, body, div, span, applet, object, iframe, h1, h2, h3, h4, h5, h6, p, blockquote, pre, a, abbr, acronym, address, big, cite, code, del, dfn, em, img, ins, kbd, q, s, samp, small, strike, strong, sub, sup, tt, var, b, u, i, center, dl, dt, dd, ol, ul, li, fieldset, form, label, legend, table, caption, tbody, tfoot, thead, tr, th, td, article, aside, canvas, details, embed, figure, figcaption, footer, header, hgroup, menu, nav, output, ruby, section, summary, time, mark, audio, video {
            margin: 0;
            padding: 0;
            border: 0;
            font-size: 100%;
            font: inherit;
            vertical-align: baseline;
            outline: none;
            -webkit-font-smoothing: antialiased;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

        html {
            overflow-y: scroll;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 62.5%;
            line-height: 1;
            color: #585858;
            padding: 22px 10px;
            padding-bottom: 55px;
        }
        strong, b {
            font-weight: bold;
        }

        table {
            border-collapse: collapse;
            border-spacing: 0;
        }

        /** page structure **/
        #wrapper {
            display: block;
            width: 90%;
            background: #fff;
            margin: 0 auto;
            padding: 10px 17px;
        }

        #messages {
            width: 100%;
            margin: 0 auto;
            font-size: 1.2em;
            margin-bottom: 15px;
        }

        #messages thead {
            cursor: pointer;
            background: #c9dff0;
        }

        #messages thead tr th {
            font-weight: bold;
            padding: 12px 30px;
            padding-left: 42px;
        }

        #messages thead tr th span {
            padding-right: 20px;
            background-repeat: no-repeat;
            background-position: 100% 100%;
        }

        #messages tbody tr {
            color: #555;
        }

        #messages tbody tr:hover td {
            color: #000;
            background: #eee;
        }

        #messages tbody tr td {
            text-align: center;
            padding: 7px 10px;
        }

        #messages tbody tr td.lalign {
            text-align: left;
        }
    </style>
</head>
<body>
<div id="wrapper">
    <table id="messages" cellspacing="0" cellpadding="0">
        <thead>
        <tr>
            <th><span>Message</span></th>
            <th><span>Timestamp</span></th>
            <th><span>RAM Usage</span></th>
        </tr>
        </thead>
        <tbody>
        @foreach($lines as $line)
            <tr>
                <td class="lalign">{{ $line['message'] }}</td>
                <td>{{ $line['timestamp'] }}</td>
                <td>{{ $line['ram'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</body>
</html>