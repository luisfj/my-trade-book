@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-9 col-md-9 col-sm-12">
            <h1>
                <span class="material-icons text-info icon-v-bottom" style="font-size: 50px !important;">
                    list_alt
                </span>
                <span>Lista de Operações</span>
            </h1>
        </div>
    </div>

    <hr class="bg-warning">
    <form method="POST" class="form-horizontal" action="{{ route('operacao.filter') }}">
        @csrf
        <div class="form-group row col-sm-12" style="text-align: right;">
            <div class="form-group row col-sm-4">
                {!! Form::label('conta_id', 'Conta', ['class' => 'col-sm-2 col-form-label col-form-label-sm']) !!}
                <div class="col-sm-10">

                    {!! Form::select('conta_id', $conta_lista, $conta_id,
                        ['placeholder' => '-- Selecione a conta --', 'class' => 'form-control form-control-sm']) !!}
                </div>
            </div>

            <div class="form-group row col-sm-4">
                {!! Form::label('data_inicial', 'Data Inicial', ['class' => 'col-sm-4 col-form-label col-form-label-sm']) !!}
                <div class="col-sm-8">
                    {!! Form::date('data_inicial', $data_inicial, ['class' => 'form-control form-control-sm']) !!}
                </div>
            </div>

            <div class="form-group row col-sm-4">
                {!! Form::label('data_final', 'Data Final', ['class' => 'col-sm-4 col-form-label col-form-label-sm']) !!}
                <div class="col-sm-8">
                    {!! Form::date('data_final', $data_final, ['class' => 'form-control form-control-sm']) !!}
                </div>
            </div>

            <div class="form-group col-sm-2">
                <button type="submit" class="btn btn-warning btn-sm md-18" style="width: 100%">
                    <i class="material-icons md-18">search</i>
                    Filtrar
                </button>
            </div>
        </div>
    </form>

    <table class="table table-primary table-sm">
        <thead>
          <tr>
            <th scope="col">Abertura</th>
            <th scope="col" class="show-over-800">Ticket</th>
            <th scope="col" class="show-over-500">Tipo</th>
            <th scope="col">Instrumento</th>
            <th scope="col" class="show-over-500">Lotes</th>
            <th scope="col" class="show-over-500">Conta Corretora</th>
            <th scope="col">Fechamento</th>
            <th scope="col" class="show-over-800">Tempo</th>
            <th scope="col">Estratégia</th>
            <th scope="col">Resultado</th>
          </tr>
        </thead>
        <tbody>
                @forelse ($operacoes as $indexKey => $operacao)
                    <tr class="table-primary {{ $operacao->resultado > 0 ? 'text-success' : ($operacao->resultado < 0 ? 'text-danger' : '') }}">
                        <th scope="row">
                            @if($operacao->resultado > 0)
                                <i class="material-icons md-18">arrow_upward</i>
                            @elseif($operacao->resultado < 0)
                                <i class="material-icons md-18">arrow_downward</i>
                            @endif
                            {{ $operacao->abertura_formatado }}
                        </th>
                        <td class="show-over-800">{{ $operacao->ticket }}</td>
                        <td class="{{ $operacao->tipo == 'buy' ? 'text-info' : 'text-warning' }} show-over-500">{{ $operacao->tipo }}</td>
                        <td>{{ $operacao->instrumento->nome }}</td>
                        <td class="show-over-500">{{ $operacao->lotes }}</td>
                        <td class="show-over-500">{{ $operacao->contaCorretora->corretora->nome }}</td>
                        <td>{{ $operacao->fechamento_formatado }}</td>
                        <td class="show-over-800">{{ $operacao->duracao_trade_formatado }}</td>
                        <td>
                            <form id="form-operacao-{{$operacao->id}}" method="POST" action="{{ route('operacao.estrategia.update') }}">
                                {{ csrf_field() }}
                                <span>{{ ($operacao->estrategia ? $operacao->estrategia->nome : '-') }}</span>
                                <input type="hidden" name="operacaoId" value="{{$operacao->id}}">
                                {!! Form::select('estrategia_id', $estrategia_lista, ($operacao->estrategia ? $operacao->estrategia->id : 'null'),
                                    ['class' => 'form-control form-control-sm hidde-me'])  !!}
                                <button type="button" name="editButton" onclick="editarEstrategia({{$operacao->id}})" class="btn btn-info btn-sm button-grid-sm">
                                    <i class="material-icons md-15">edit</i>
                                </button>
                                <button type="button" name="saveButton" onclick="salvarEstrategia({{$operacao->id}})" class="btn btn-success btn-sm button-grid-sm hidde-me">
                                    <i class="material-icons md-15">save</i>
                                </button>
                                <button type="button" name="cancelButton" onclick="cancelarEdicaoEstrategia({{$operacao->id}})" class="btn btn-warning btn-sm button-grid-sm hidde-me">
                                    <i class="material-icons md-15">cancel</i>
                                </button>
                            </form>
                        </td>
                        <td class="fbold">
                            {{ $operacao->resultado }}
                        </td>
                    </tr>
                @empty
                    <th scope="row">Nenhuma Operação Cadastrada</th>
                @endforelse
        </tbody>
      </table>
@endsection
@section('page-script')
@parent
<script>
    var selectedDefault = null;

    function editarEstrategia(idForm) {
        $('#form-operacao-'+idForm).find("[name='saveButton']").removeClass('hidde-me');
        $('#form-operacao-'+idForm).find("[name='cancelButton']").removeClass('hidde-me');
        $('#form-operacao-'+idForm).find("[name='estrategia_id']").removeClass('hidde-me');
        $('#form-operacao-'+idForm).find("[name='editButton']").addClass('hidde-me');
        $('#form-operacao-'+idForm).find("span").addClass('hidde-me');

        selectedDefault = $('#form-operacao-'+idForm).find('[name="estrategia_id"')[0].value;
    }

    function cancelarEdicaoEstrategia(idForm) {
        $('#form-operacao-'+idForm).find("[name='saveButton']").addClass('hidde-me');
        $('#form-operacao-'+idForm).find("[name='cancelButton']").addClass('hidde-me');
        $('#form-operacao-'+idForm).find("[name='estrategia_id']").addClass('hidde-me');
        $('#form-operacao-'+idForm).find("[name='editButton']").removeClass('hidde-me');
        $('#form-operacao-'+idForm).find("span").removeClass('hidde-me');
        $('#form-operacao-'+idForm).find('[name="estrategia_id"')[0].value = selectedDefault;

        selectedDefault = null;
    }

    function salvarEstrategia(idForm){
        //$('#form-operacao-'+idForm).submit();
        $.ajax({
                url: $('#form-operacao-'+idForm).attr('action'),
                type: 'POST',
                data: $('#form-operacao-'+idForm).serialize(),
                success: function(data) {
                    if(data.success){
                        selectedDefault = $('#form-operacao-'+idForm).find('[name="estrategia_id"')[0].value;

                        if( $('#form-operacao-'+idForm).find('[name="estrategia_id"')[0].value === 'null' ) {
                            $('#form-operacao-'+idForm).find('span').text(' - ');
                        } else {
                            $('#form-operacao-'+idForm).find('span').text($('#form-operacao-'+idForm).find('[name="estrategia_id"')[0].selectedOptions[0].text);
                        }
                        cancelarEdicaoEstrategia(idForm);
                        mensagemSucesso('Estratégia alterada com sucesso!');
                    } else {
                        $('#errorMessageEdit').removeClass('hidde-me');
                        $('#errorMessageEdit b').html(data.error);
                        mensagemErro('Algo deu errado!');
                    }
                },
                error: function (data) {
                    $('#errorMessageEdit').removeClass('hidde-me');
                    $('#errorMessageEdit b').html(data.error);
                    mensagemErro('Algo deu errado!');
                }
            });
    }
</script>
@stop
