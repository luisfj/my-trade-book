<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <!--<meta name="viewport" content="width=device-width, initial-scale=1">-->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/all.css') }}" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

</head>
<body>
    <div id="app" class="wrapper">
        @include('layouts.side-bar')
        <div id="content">
            @include('layouts.top-bar')

            <main class="py-4">
                <div class="container">


                    <div>
                            <b-button v-b-modal.modal-1>Launch demo modal</b-button>

                            <b-modal id="modal-1" title="BootstrapVue">
                              <p class="my-4">Hello from modal!</p>
                            </b-modal>
                          </div>
                @yield('content')
                </div>
            </main>
        </div>
    </div>

     <!--Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
