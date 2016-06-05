<!DOCTYPE html>
<html>
    <head>
        <title>Laravel</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
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
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">Laravel 5</div>
                {{ 'database: ' . getenv('DATABASE_URL') }}

                <ul style="font-weight: bold;">
                    @foreach ($apiTokens as $apiToken)
                        <li>
                            <div><span>Api Token: </span><span>{{ $apiToken->api_token }}</span></div>
                            <div><span>Client Id: </span><span>{{ $apiToken->client_id }}</span></div>
                            <div><span>User Id: </span><span>{{ $apiToken->user_id }}</span></div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </body>
</html>
