<!-- Sidebar -->

<nav id="sidebar" class="navbar-dark bg-primary {{ Auth::check() ? '' : 'hidde-me' }}">
    <ul class="list-unstyled components">
        <p>
            <a class="navbar-brand text-danger" href="{{ route('home') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
        </p>
        <hr class="bg-danger">
        <li class="active">
            <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Home</a>
            <ul class="collapse list-unstyled show" id="homeSubmenu">
                <li>
                    <a href="{{ route('operacao.importar') }}">
                        <span class="material-icons fs20 text-success icon-v-bottom">
                            save_alt
                        </span>
                        <span class="fs16">
                            Importar Operações
                        </span>
                    </a>
                </li>
                <hr class="hr3 mar-0">
                <li>
                    <a href="{{ route('conta.corretora.index') }}">
                        <span class="material-icons fs18 text-warning icon-v-bottom">
                            account_balance_wallet
                        </span>
                        <span>
                            Contas em Corretoras
                        </span>
                    </a>
                </li>
                <hr class="hr3 mar-0">
                <li>
                    <a href="{{ route('registros.importacoes.index') }}">
                        <span class="material-icons fs18 text-info icon-v-bottom">
                            low_priority
                        </span>
                        <span>
                            Registros de Importações
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('operacao.index') }}">
                        <span class="material-icons fs18 text-info icon-v-bottom">
                            list_alt
                        </span>
                        <span>
                            Lista de Operações
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('transacoes.index') }}">
                        <span class="material-icons fs18 text-success icon-v-bottom">
                            attach_money
                        </span>
                        <span>
                            Depósitos e Saques
                        </span>
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="{{ route('contato')}}">
                <span class="material-icons fs18 text-info icon-v-bottom">
                    email
                </span>
                Contato
            </a>
        </li>
        <hr class="hr3 mar-0">
        <li>
            <a href="#"  v-b-modal.modal-reportar-problema>
                <span class="material-icons fs18 text-warning icon-v-bottom">
                    bug_report
                </span>
                Informar Erro/Melhoria
            </a>
        </li>
        <li>
            <a href="{{ route('comunicacao.index') }}">
                <span class="material-icons fs18 text-info icon-v-bottom">
                    chat
                </span>
                Painel de Mensagens
            </a>
        </li>
        <hr class="hr3 mar-0">
        <li>
            <a class="btn btn-active noBorder text-success" style="padding-top: 15px;" href="{{ route('doacoes')}}">
                <span class="material-icons fs18 icon-v-bottom">
                    favorite
                </span>
                Gostou do projeto? Ajude a manter essa idéia viva!
            </a>
        </li>

        @if(Auth::check() && Auth::User()->is_admin())
            <li>
                <a href="#pageAdminSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Admin</a>
                <ul class="collapse list-unstyled" id="pageAdminSubmenu">
                    <li>
                        <a href="{{ route('users.index') }}">Usuários</a>
                    </li>
                    <li>
                        <a href="{{ route('perfil.index') }}">Perfil de Investidor</a>
                    </li>
                    <li>
                        <a href="{{ route('moeda.index') }}">Moedas</a>
                    </li>
                    <li>
                        <a href="{{ route('corretora.index') }}">Corretoras</a>
                    </li>
                    <li>
                        <a href="{{ route('posts.index') }}">Posts/Enquetes</a>
                    </li>
                    <li>
                        <a href="{{ route('instrumento.index') }}">Instrumentos</a>
                    </li>
                </ul>
            </li>
        @endif

        @if(Auth::check() && Auth::User()->is_super_admin())
            <li>
                <a href="#pageSuperAdminSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Extra Admin</a>
                <ul class="collapse list-unstyled" id="pageSuperAdminSubmenu">
                    <li>
                        <a href="{{ route('admin.index.migration') }}">MIGRATIONS</a>
                    </li>
                </ul>
            </li>
        @endif
    </ul>

</nav>

<!--@ include('modulos.bugs-report.modal-reportar-problema')-->
<bugsmodal :useradmin="{{ Auth::check() && Auth::User()->is_admin() ? 'true' : 'false' }}"></bugsmodal>
