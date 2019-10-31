@extends('layouts.app')

@section('content')
    <h1>Listagem dos posts</h1>

    @forelse ($posts as $post)
        {{ $post->title }}
        <hr>
    @empty
        <p>Nenhum Post Cadastrado</p>
    @endforelse

    {{ $posts->links() }}
@endsection
