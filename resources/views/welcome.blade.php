<!DOCTYPE html>
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
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title>{{ config('app.name', 'Diário de Trade') }}</title>

        <meta name="description" content="Diário de Trade. Gerencie suas operações de trade de forma automatizada, somente importando seu relatório de performance, sem planilhas, online e gratuito.">
        <link rel="canonical" href="https://diario.trade/">
        <meta property="og:locale" content="pt_BR">
        <meta property="og:type" content="website">
        <meta property="og:title" content="{{ config('app.name', 'Diário de Trade') }}">
        <meta property="og:description" content="Diário de Trade. Gerencie suas operações de trade de forma automatizada, somente importando seu relatório de performance, sem planilhas, online e gratuito.">
        <meta property="og:url" content="https://diario.trade/">
        <meta property="og:site_name" content="Diário de Trade">

        <meta property="article:modified_time" content="2020-04-30T05:00:12+00:00">

        <!-- Bootstrap core CSS -->
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

        <!-- Styles -->
        <link href="{{ asset('css/grayscale.min.css') }}" rel="stylesheet">
    </head>
    <body id="page-top">

        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
          <div class="container">
            <a class="navbar-brand js-scroll-trigger" href="#page-top">Diário de Trade</a>
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
              Menu
              <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
              <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                  <a class="nav-link" href="#sobre">Sobre</a>
                </li>
                @if (Route::has('login'))
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/home') }}"><strong>Entrar</strong></a>
                        </li>
                    @else
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">Registrar</a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}"><strong>Login</strong></a>
                        </li>
                    @endauth
                @endif
              </ul>
            </div>
          </div>
        </nav>

        <!-- Header -->
        <header class="masthead">
          <div class="container d-flex h-100 align-items-center">
            <div class="mx-auto text-center">
              <h1 class="mx-auto my-0 text-uppercase">Diário de Trade</h1>
              <h2 class="text-white-50 mx-auto mt-2 mb-5">
                    Seu diário de trade online! Importe o relatório de performance de sua plataforma e gerencie de forma rápida e simples seu desempenho.
                </h2>
            </div>
          </div>
        </header>

        <!-- About Section -->
        <section id="sobre" class="about-section text-center">
          <div class="container">
            <div class="row">
              <div class="col-lg-8 mx-auto">
                <h2 class="text-white mb-4">Sobre o Diário de Trade</h2>
                <p class="text-white-50">
                    Desenvolvido para substituir o uso de planilhas para extrair o desempenho com o trade, o
                    <a href="#sobre">diario.trade</a> vem para simplificar, possibilitando que seus usuários apenas importem o relatório de performance de
                    sua plataforma preferida, e assim, de forma rápida, prática e simples tenha todos os indicadores necessários para gerenciar seu desempenho.
                </p>
              </div>
            </div>
            <img src="{{ asset('img/completo.PNG') }}" class="img-fluid" alt="">
          </div>
        </section>

        <!-- Projects Section -->
        <section id="projects" class="projects-section bg-light">
          <div class="container">

            <!-- Featured Project Row -->
            <div class="row align-items-center no-gutters mb-4 mb-lg-5">
              <div class="col-xl-8 col-lg-7">
                <img class="img-fluid mb-3 mb-lg-0" src="{{ asset('img/Importacao.PNG') }}" alt="">
              </div>
              <div class="col-xl-4 col-lg-5">
                <div class="featured-text text-center text-lg-left">
                  <h4>Importação</h4>
                  <p class="text-black-50 mb-0">Selecione a conta, a plataforma, o relatório e pronto. Todos os trades estarão no sistema.</p>
                </div>
              </div>
            </div>

            <!-- Project One Row -->
            <div class="row justify-content-center no-gutters mb-5 mb-lg-0">
              <div class="col-lg-6">
                <img class="img-fluid" src="{{ asset('img/EvoMensal.PNG') }}" alt="">
              </div>
              <div class="col-lg-6">
                <div class="bg-black text-center h-100 project">
                  <div class="d-flex h-100">
                    <div class="project-text w-100 my-auto text-center text-lg-left">
                      <h4 class="text-white">Evolução Mensal do Saldo</h4>
                      <p class="mb-0 text-white-50">Visualize de forma fácil o desempenho dia a dia e a evolução do saldo da sua conta no mês.</p>
                      <hr class="d-none d-lg-block mb-0 ml-0">
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Project Two Row -->
            <div class="row justify-content-center no-gutters">
              <div class="col-lg-6">
                <img class="img-fluid" src="{{ asset('img/EvoAnual.PNG') }}" alt="">
              </div>
              <div class="col-lg-6 order-lg-first">
                <div class="bg-black text-center h-100 project">
                  <div class="d-flex h-100">
                    <div class="project-text w-100 my-auto text-center text-lg-right">
                      <h4 class="text-white">Evolução Anual do Saldo</h4>
                      <p class="mb-0 text-white-50">De forma simples veja a evolução do saldo mês a mês, e compare com anos anteriores!</p>
                      <hr class="d-none d-lg-block mb-0 mr-0">
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </section>

        <!-- Signup Section -->
        <section id="signup" class="signup-section">
          <div class="container">
            <div class="row">
              <div class="col-md-10 col-lg-8 mx-auto text-center">

                <i class="far fa-paper-plane fa-2x mb-2 text-white"></i>
                <h2 class="text-white mb-5">Automatize seu diário de trade!</h2>

                <a href="{{ route('register') }}" class="btn btn-primary mx-auto">Registre-se</a>

              </div>
            </div>
          </div>
        </section>

        <!-- Contact Section -->
        <section class="contact-section bg-black">
          <div class="container">

            <div class="row">

              <div class="col-md-4 mb-3 mb-md-0">
                <div class="card py-4 h-100">
                  <div class="card-body text-center">
                    <i class="fas fa-map-marked-alt text-primary mb-2"></i>
                    <h4 class="text-uppercase m-0">Facebook</h4>
                    <hr class="my-4">
                    <div class="small text-black-50">

                    </div>
                  </div>
                </div>
              </div>


              <div class="col-md-4 mb-3 mb-md-0">
                <div class="card py-4 h-100">
                  <div class="card-body text-center">
                    <i class="fas fa-envelope text-primary mb-2"></i>
                    <h4 class="text-uppercase m-0">Email</h4>
                    <hr class="my-4">
                    <div class="small text-black-50">
                      <a href="mailto:contato@diario.trade">contato@diario.trade</a>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-4 mb-3 mb-md-0">
                <div class="card py-4 h-100">
                  <div class="card-body text-center">
                    <i class="fas fa-mobile-alt text-primary mb-2"></i>
                    <h4 class="text-uppercase m-0">Telegram</h4>
                    <hr class="my-4">
                    <div class="small text-black-50"><a target="_blank" href="https://telegram.me/diariodetrade">t.me/diariodetrade</a></div>
                  </div>
                </div>
              </div>
            </div>
<!--
            <div class="social d-flex justify-content-center">
              <a href="#" class="mx-2">
                <i class="fab fa-twitter"></i>
              </a>
              <a href="#" class="mx-2">
                <i class="fab fa-facebook-f"></i>
              </a>
              <a href="#" class="mx-2">
                <i class="fab fa-github"></i>
              </a>
            </div>
        -->
          </div>
        </section>

        <!-- Footer -->
        <footer class="bg-black small text-center text-white-50">
          <div class="container">
            Copyright &copy; Diário de Trade 2020
          </div>
        </footer>

        <!-- Bootstrap core JavaScript -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

        <!-- Plugin JavaScript -->
        <script src="{{ asset('js/jquery.easing.min.js') }}"></script>

        <!-- Custom scripts for this template -->
        <script src="{{ asset('js/grayscale.min.js') }}"></script>

      </body>

</html>
