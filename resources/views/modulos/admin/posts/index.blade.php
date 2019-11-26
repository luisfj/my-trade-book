@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-9 col-md-9 col-sm-12"><h1>Listagem dos posts</h1></div>
    <div class="col-lg-2 offset-lg-1 col-md-2 offset-md-1 col-sm-12" style="width:100px !important;">
        <a class="btn btn-success form-control" href="{{ route('posts.add') }}">
            <i class="material-icons md-light md-24">add_circle_outline</i>
        </a>
    </div>
</div>

<hr class="bg-warning">

<table class="table table-primary">
    <thead>
      <tr>
        <th scope="col">Id</th>
        <th scope="col">Titulo</th>
        <th scope="col">Descrição</th>
        <th scope="col">Tipo</th>
        <th scope="col">Fim Enquete</th>
        <th scope="col">Res. Publico</th>
        <th scope="col">Omitir</th>
        <th scope="col">Ações</th>
      </tr>
    </thead>
    <tbody>
            @forelse ($posts as $indexKey => $post)
                <tr class="{{ $indexKey % 2 == 0 ? 'table-active' : 'table-primary' }}">
                    <th scope="row">{{ $post->id }}</th>
                    <td>{{ $post->title }}</td>
                    <td>{{ Str::limit($post->body, 30) }}</td>
                    <td>{{ $post->tipo == 'E' ? 'Enquete' : 'Mensagem' }}</td>
                    <td>{{ $post->data_fim_enquete }}</td>
                    <td>{{ $post->resultado_publico == 1 ? 'Publico' : '' }}</td>
                    <td>{{ $post->exibir == 0 ? 'Omitir' : '' }}</td>
                    <td>
                        <a href="{{ route('posts.show', $post->id) }}">
                            <i class="material-icons text-info md-18">edit</i>
                        </a>
                    </td>
                </tr>
            @empty
                <th scope="row">Nenhum Post Cadastrado</th>
            @endforelse
    </tbody>
  </table>
  {{ $posts->links() }}
@endsection
