<!-- Sidebar -->

<nav id="sidebar" class="navbar-dark bg-primary {{ Auth::check() ? '' : 'hidde-me' }}">
    @include('layouts.menu-list')
</nav>

<!--@ include('modulos.bugs-report.modal-reportar-problema')-->
<bugsmodal :useradmin="{{ Auth::check() && Auth::User()->is_admin() ? 'true' : 'false' }}"></bugsmodal>
