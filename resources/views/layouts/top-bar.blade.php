
<nav id="topbar" class="navbar navbar-expand-lg navbar-dark bg-primary shadow-md">

    <div class="container">

        <div id="sidebarButtonCollapse" class="navbar-brand {{ Auth::check() ? '' : 'hidde-me' }}">
            <button type="button" id="sidebarCollapse" class="navbar-toggler">
                <span class="navbar-toggler-icon"></span>
            </button>

        </div>
        <a id="brand-app-name" class="navbar-brand text-danger" href="{{ route('home') }}">
            {{ config('app.name', 'Laravel') }}
        </a>
        <a id="brand-app-name-sm" class="navbar-brand text-danger hidde-me" href="{{ route('home') }}">
            {{ config('app.name', 'Laravel') }}
        </a>

        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive"
            aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
            Menu
          </button>
          <div class="collapse navbar-collapse" id="navbarResponsive">
            @include('layouts.menu-list')

            <ul class="list-unstyled components">
                <li>
                    <a href="{{ route('profile.index') }}">
                            Profile
                    </a>
                </li>
                <li>
                    <a href="{{ route('configuracoes.index') }}">
                            Configurações
                    </a>
                </li>
                    <hr class="hr3 mar-0">
                <li>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>
                </li>
            </ul>
          </div>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto" id="atalhosRapidosTopBar">
                @if(Auth::check())
                    <a class="btn btn-active marr-10 noBorder" href="{{ route('home')}}" style="min-width: 150px;">
                        <span class="material-icons fs18 text-success icon-v-bottom">
                            pie_chart
                        </span>
                        ESTATÍSTICAS
                    </a>
                    <a class="btn btn-active marr-10 noBorder" href="{{ route('operacao.importar')}}" style="min-width: 150px;">
                        <span class="material-icons fs18 text-success icon-v-bottom">
                            save_alt
                        </span>
                        Importar
                    </a>
                    <a class="btn btn-active marr-10 noBorder" href="{{ route('conta.corretora.index')}}" style="min-width: 150px;">
                        <span class="material-icons fs18 text-warning icon-v-bottom">
                            account_balance_wallet
                        </span>
                        Contas
                    </a>
                    <a class="btn btn-active noBorder topbarItemDep" href="{{ route('transacoes.index')}}">
                        <span class="material-icons fs18 text-success icon-v-bottom">
                            attach_money
                        </span>
                        Depósitos e Saques
                    </a>
                @endif
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Registrar</a>
                        </li>
                    @endif
                @else
                <a class="btn btn-active noBorder text-success topbarItemAju" style="padding-top: 15px;" href="{{ route('doacoes')}}">
                    <span class="material-icons fs18 icon-v-bottom">
                        favorite
                    </span>
                    Ajude a manter essa idéia viva
                </a>
                    <notifications :urlpanel='{!! json_encode(route('notifications.panel')) !!}'></notifications>
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">

                            <a class="dropdown-item" href="{{ route('profile.index') }}">
                                Profile
                            </a>

                            <a class="dropdown-item" href="{{ route('configuracoes.index') }}">
                                 Configurações
                            </a>

                            <div class="dropdown-divider"></div>

                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                    <notificacaomodal :useradmin="{{ Auth::check() && Auth::User()->is_admin() ? 'true' : 'false' }}"></notificacaomodal>
                @endguest
            </ul>
        </div>
    </div>
</nav>
