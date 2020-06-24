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
                <div aria-live="polite" aria-atomic="true" style="position: absolute; min-height: 200px; right: 10px; width: inherit; z-index: -100;">
                    <!-- Position it -->
                    <div style="position: absolute; top: 0; right: 0; text-align: -webkit-right;">
                        <div class="toast" id="toast-success" data-delay="6000" style="width: fit-content;">
                            <!--<div class="toast-header">
                            <strong class="mr-auto">Atenção</strong>
                            <small>11 mins ago</small>
                            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>-->
                            <div class="toast-body btn-success">
                                Ops. Um erro foi detectado, tente novamente em instantes!!
                            </div>
                        </div>
                        <div class="toast" id="toast-error" data-delay="6000" style="width: fit-content;" >
                            <div class="toast-body btn-danger">
                                Ops. Um erro foi detectado, tente novamente em instantes!!
                            </div>
                        </div>
                        <div class="toast" id="toast-info" data-delay="6000" style="width: fit-content;" >
                            <div class="toast-body btn-info">
                                Informação!!
                            </div>
                        </div>
                    </div>
                </div>
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
