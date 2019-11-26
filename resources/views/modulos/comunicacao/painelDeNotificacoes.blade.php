@extends('layouts.app')

@section('content')
    <h1>Listagem das Notificações</h1>
    <hr class="bg-warning">

    <table class="table table-primary">
        <thead>
          <tr>
            <th scope="col">Tipo</th>
            <th scope="col">Titulo</th>
            <th scope="col">Descrição</th>
            <th scope="col">Fim Enquete</th>
            <th scope="col">Votada/Lida</th>
            <th scope="col">Ações</th>
          </tr>
        </thead>
        <tbody>
                @forelse ($notifications as  $notificacao)
                    <tr class="{{ $notificacao->read_at ? '' : 'table-active text-warning' }}">
                        <td><i class="material-icons {{ $notificacao->read_at ? 'text-success' : 'hidde-me' }}">check_circle_outline</i> {{ $notificacao->data['post']['tipo'] == 'E' ? 'Enquete' : 'Mensagem' }}</td>
                        <td>{{ $notificacao->data['post']['title'] }}</td>
                        <td>{{ Str::limit($notificacao->data['post']['body'], 15) }}</td>
                        <td>{{ $notificacao->data['post']['data_fim_enquete'] != '' ? \DateTime::createFromFormat("Y-m-d", $notificacao->data['post']['data_fim_enquete'])->format("d/m/Y") : '' }}</td>
                        <td>{{ $notificacao->read_at ? date_format($notificacao->read_at, "d/m/Y") : '' }}</td>
                        <td><icon-a-link :obj_edit="{{ $notificacao }}" :metodo="'editarNotificacao'" :icon="'search'"
                            :color="'text-info'" :mdsize="'md-18'" :text="'Visualizar'" :textcolor="'text-info'"></icon-a-link></td>
                    </tr>
                @empty
                    <th scope="row">Nenhuma Notificação Cadastrado</th>
                @endforelse
        </tbody>
      </table>
      {{ $notifications->links() }}
@endsection
