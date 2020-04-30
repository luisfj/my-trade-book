<!doctype html>
<html lang="pt-BR">
<head>
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-165016146-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-165016146-1');
    </script>

    <meta charset="UTF-8">
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

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/all.css') }}" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

</head>
<body>
    <div id="app" class="wrapper">
        @include('layouts.side-bar')

        <div id="content">

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
     <script>
         if (typeof(Storage) !== "undefined") {
                // Save the state of the sidebar as "open"
                if(localStorage.getItem("sidebar") == 'close' || $('#sidebar').hasClass('hidde-me')){
                    $('#sidebar').addClass('active');
                    $('#content').addClass('active');
                    $('#brand-app-name').removeClass('hidde-me');
                } else {
                    $('#sidebar').removeClass('active');
                    $('#content').removeClass('active');
                    $('#brand-app-name').addClass('hidde-me');
                }
            }
     </script>
    <script src="{{ asset('js/app.js') }}" ></script>
    <script src="{{ asset('js/Chart.min.js') }}"></script>
    <script src="{{ asset('js/jquery-dateformat.min.js') }}"></script>
    <script src="{{ asset('js/common.js') }}" ></script>

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
