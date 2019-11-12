@extends('layouts.app')

@section('content')
    <h1>Listagem dos Erros/Melhorias Informados</h1>
    <hr class="bg-warning">

    <table class="table table-primary">
        <thead>
          <tr>
            <th scope="col">Criação</th>
            <th scope="col">Página</th>
            <th scope="col">Tipo</th>
            <th scope="col">Relato</th>
            <th scope="col">Verificação</th>
            @if(Auth::user()->is_admin())
                <th scope="col">Resolução</th>
                <th scope="col">Autor</th>
            @endif
            <th scope="col">Ações</th>
          </tr>
        </thead>
        <tbody>
                @forelse ($bugs as $indexKey => $bug)
                    <tr class="{{ $indexKey % 2 == 0 ? 'table-active' : 'table-primary' }}">
                        <th scope="row">{{ (Auth::user()->is_admin() ? '(' . $bug->id . ') ' : '') . date_format($bug->created_at,"d/m/Y") }}</th>
                        <td>{{ $bug->pagina }}</td>
                        <td>{{ $bug->tipo }}</td>
                        <td>{{ Str::limit($bug->descricao, 10) }}</td>
                        <td>{{ $bug->data_verificacao }}</td>
                        @if(Auth::user()->is_admin())
                            <td>{{ $bug->data_resolucao }}</td>
                            <td>{{ $bug->Autor->name }}</td>
                        @endif
                        <td>
                            <icon-a-link :obj_edit="{{ $bug }}" :metodo="'editBug'" :icon="'find_in_page'"
                                :color="'text-danger'" :mdsize="'md-18'"></icon-a-link>
                        </td>
                    </tr>
                @empty
                    <th scope="row">Nenhum Erro/Melhoria Cadastrado</th>
                @endforelse
        </tbody>
      </table>
      {{ $bugs->links() }}
@endsection
