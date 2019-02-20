<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>{{ Lang::get('errors.generic') }}</title>

    <link href='//fonts.googleapis.com/css?family=Open+Sans:700,400&subset=latin,latin-ext' rel='stylesheet'
          type='text/css'/>
    <link href="{{ elixir('css/app.css') }}" rel="stylesheet" />
    <link href="/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet"/>
</head>

<body>
<main class="wrapper">
    <section class="container">
        <div class="container__wrapper -help">
            <div class="container-fluid" style="padding-top: 100px;">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="panel panel-default">
                            <div class="panel-heading">{{ Lang::get('errors.generic') }}</div>
                            <div class="panel-body">
                                {{ $msg }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

</body>
</html>
