@extends('layouts.app')

@section('content')
    <h1>Listagem dos usuários do sistema</h1>
    <hr class="bg-warning">

    <table class="table table-primary table-sm">
        <thead>
          <tr>
            <th scope="col">Id</th>
            <th scope="col">Nome</th>
            <th scope="col">Email</th>
            <th scope="col">Email Verificado</th>
            <th scope="col">C. Corretoras</th>
            <th scope="col">Operações</th>
            <th scope="col">Criação</th>
            @if(Auth::user()->is_super_admin())
                <th scope="col">Role</th>
                <th scope="col">Ações</th>
                <th scope="col">Logar</th>
            @endif
          </tr>
        </thead>
        <tbody>
                @forelse ($users as $indexKey => $user)
                    <tr class="{{ $indexKey % 2 == 0 ? 'table-active' : 'table-primary' }}">
                        <th scope="row">{{ $user->id }}</th>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->email_verified_at }}</td>
                        <td>{{ $user->contaCorretoraCount }}</td>
                        <td>{{ $user->OperacoesCount }}</td>
                        <td>{{ $user->created_at }}</td>
                        @if(Auth::user()->is_super_admin())
                            <td>{{ $user->role }}</td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                    <button type="button" class="btn btn-danger btn-sm">Alterar Role</button>
                                    <div class="btn-group" role="group">
                                        <button id="btnGroupDrop4" type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop4" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 48px, 0px); top: 0px; left: 0px; will-change: transform;">
                                        <a class="dropdown-item" href="#" onclick="
                                            document.getElementById('change-role-form-{{ $user->id }}').getElementsByClassName('new_role')[0].value='user';
                                            event.preventDefault();
                                            document.getElementById('change-role-form-{{ $user->id }}').submit();">Para Usuário</a>

                                        <a class="dropdown-item" href="#" onclick="
                                            document.getElementById('change-role-form-{{ $user->id }}').getElementsByClassName('new_role')[0].value='admin';
                                            event.preventDefault();
                                            document.getElementById('change-role-form-{{ $user->id }}').submit();">Para Admin</a>

                                        <a class="dropdown-item" href="#" onclick="
                                            document.getElementById('change-role-form-{{ $user->id }}').getElementsByClassName('new_role')[0].value='super_admin';
                                            event.preventDefault();
                                            document.getElementById('change-role-form-{{ $user->id }}').submit();">Para Super-Admin</a>
                                        </div>
                                    </div>
                                </div>

                                <form id="change-role-form-{{ $user->id }}" action="{{ route('users.update.role') }}" method="POST" style="display: none;">
                                    @csrf
                                    <input type="hidden" id="user_id" name="user_id" value="{{ $user->id }}">
                                    <input type="hidden" class="new_role" id="new_role" name="new_role" value="user">
                                 </form>
                            </td>
                            <td>
                                <form id="login-form" action="{{ route('users.login.other') }}" method="POST">
                                    @csrf
                                    <input type="hidden" id="user_id" name="user_id" value="{{ $user->id }}">
                                    <button type="submit" class="btn btn-info btn-sm">Login</button>
                                 </form>
                            </td>
                        @endif
                    </tr>
                @empty
                    <th scope="row">Nenhum Erro/Melhoria Cadastrado</th>
                @endforelse
        </tbody>
      </table>
      {{ $users->links() }}
@endsection
