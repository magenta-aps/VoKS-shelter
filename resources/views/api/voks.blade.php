<html>
    <head>
        <title>Laravel</title>

        <link href='//fonts.googleapis.com/css?family=Lato:100' rel='stylesheet' type='text/css'>

        <style>
            body {
                margin: 0;
                padding: 0;
                width: 100%;
                height: 100%;
                color: #B0BEC5;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 96px;
                margin-bottom: 40px;
            }

            .quote {
                font-size: 24px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <form action="/api/voks/register-device" method="post">
                    <input type="text" value="duid-1" name="device_id" />
                    <input type="text" value="ios" name="device_type" />
                    <input type="text" value="gcmid-2" name="gcm_id" />
                    <input type="text" value="mac-3" name="mac_address" />
                    <input type="submit" value="Send" />
                </form>
                <form action="/api/voks/trigger-alarm" method="post">
                    <input type="text" value="asd" name="device_id" />
                    <input type="text" value="0" name="call_police" />
                    <input type="submit" value="Send" />
                </form>
            </div>
        </div>
    </body>
</html>
