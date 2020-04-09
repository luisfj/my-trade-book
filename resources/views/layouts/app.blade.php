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
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css"
        integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">

    <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table.min.css">
    <!-- Styles -->

    <!-- Compiled and minified CSS
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">-->

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/all.css') }}" rel="stylesheet">
    <!--<link rel="stylesheet" href="http://davidstutz.de/bootstrap-multiselect/dist/css/bootstrap-multiselect.css">-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>


<!--<script src="http://demos.codexworld.com/multi-select-dropdown-list-with-checkbox-jquery/multiselect/jquery.multiselect.js"></script>
-->
</head>
<body>
    <div id="app" class="wrapper">
        @include('layouts.side-bar')

        <div id="content" class="{{ Auth::check() ? '' : 'active' }}">

            @include('layouts.top-bar')
            <div class="container">
                    @if(session('success'))
                    <div class="alert alert-success">
                        <b>{{ session('success')['messages'] }}</b>
                    </div>
                    @endif
                    @if(session('error'))
                    <div class="alert alert-danger">
                        <b>{{ session('error')['messages'] }}</b>
                    </div>
                    @endif

                @yield('content')
                <hr class="bg-warning">
                <div class="mb-5"></div>
            </div>

        </div>
    </div>

     <!--Scripts -->
    <script src="{{ asset('js/app.js') }}" ></script>
    <script src="{{ asset('js/Chart.min.js') }}"></script>
    <script src="{{ asset('js/jquery-dateformat.min.js') }}"></script>
    <script src="{{ asset('js/common.js') }}" ></script>

    <!-- Compiled and minified JavaScript
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
-->
    @yield('page-script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

    <script src="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table.min.js"></script>
    <script src="https://rawgit.com/DashboardCode/BsMultiSelect/master/dist/js/BsMultiSelect.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/4.4.0/papaparse.min.js"></script>

    <script>
        Popper.Defaults.modifiers.computeStyle.gpuAcceleration = !(window.devicePixelRatio < 1.5 && /Win/.test(navigator.platform));
     </script>
</body>
</html>
