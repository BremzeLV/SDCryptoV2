<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <link href="{{ asset('css/landingpage.css') }}" rel="stylesheet">
    </head>
    <body>
        <div id="landing-page" class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                        <a class="btn" href="{{ route('register') }}">Register</a>
                    @endauth
                </div>
            @endif

            <div class="content">
                <div id="landing-text">
                    <h1>Super Duper Crypto</h1>
                    <p class="pull-right" style="width: 50%">
                        A cryptocurrency (or crypto currency) is a controversial digital asset designed to work as a medium of exchange that uses cryptography to secure its transactions, to control the creation of additional units, and to verify the transfer of assets.
                    </p>

                    @auth
                        <a class="btn" href="{{ URL::to('/home') }}">Dashboard</a>
                    @else
                        <a class="btn" href="{{ URL::to('/register') }}">Register for free</a>
                    @endauth

                </div>
            </div>
        </div>
    </body>
</html>
