<!-- Sidebar -->

<nav id="sidebar" class="navbar-dark bg-primary {{ Auth::check() ? '' : 'active' }}">
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
                    <a href="{{ route('posts.index') }}">Posts</a>
                </li>
                <li>
                    <a href="#">Home 2</a>
                </li>
                <li>
                    <a href="#">Home 3</a>
                </li>
            </ul>
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
                        <a href="{{ route('posts.index') }}">Posts/Enquetes</a>
                    </li>
                </ul>
            </li>
        @endif

        @if(Auth::check() && Auth::User()->is_super_admin())
            <li>
                <a href="#pageSuperAdminSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Extra Admin</a>
                <ul class="collapse list-unstyled" id="pageSuperAdminSubmenu">
                    <li>
                        <a href="#">Page 1</a>
                    </li>
                    <li>
                        <a href="#">Page 2</a>
                    </li>
                    <li>
                        <a href="#">Page 3</a>
                    </li>
                </ul>
            </li>
        @endif

        <li>
            <a href="#">Contact</a>
        </li>
        <li>
            <a href="#">About</a>
        </li>
        <li>
            <a href="#"  v-b-modal.modal-reportar-problema>Informar Erro/Melhoria</a>
        </li>
        <li>
        <a href="{{ route('comunicacao.index') }}">Painel de Comunicação</a>
        </li>
    </ul>

</nav>
<!--@ include('modulos.bugs-report.modal-reportar-problema')-->
<bugsmodal :useradmin="{{ Auth::check() && Auth::User()->is_admin() ? 'true' : 'false' }}"></bugsmodal>
