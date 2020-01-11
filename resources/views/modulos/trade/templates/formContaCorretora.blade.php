<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12 card border-dark mb-3" style="padding-top:15px;">
        <h4>Dados</h4>
        <hr>

        <div class="form-group">
            {!! Form::label('identificador', 'Identificador da Conta') !!}
            {!! Form::text('identificador', null, ['placeholder' => 'Identificador', 'class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('dtabertura', 'Data de Abertura da Conta') !!}
            {!! Form::date('dtabertura', null, ['placeholder' => 'Abertura da Conta','class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('moeda_id', 'Moeda') !!}
            {!! Form::select('moeda_id', $moedas_list, null, ['placeholder' => '-- Selecione a moeda --', 'class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('tipo', 'Tipo') !!}
            {!! Form::select('tipo', ['B' => 'Banco', 'E' => 'eWallet', 'C' => 'Corretora Nacional', 'D' => 'Corretora Internacional'], null, ['placeholder' => '-- Tipo --', 'class' => 'form-control'])  !!}
        </div>

        <div class="form-group">
            {!! Form::label('ativa', 'Ativa') !!}
            {!! Form::select('ativa', ['1' => 'Sim', '0' => 'Não'], $conta->ativa ?? '1', ['class' => 'form-control'])  !!}
        </div>

        <div class="form-group">
            {!! Form::label('exibirnopainel', 'Exibir no Painel Inicial') !!}
            {!! Form::select('exibirnopainel', ['1' => 'Sim', '0' => 'Não'], $conta->exibirnopainel ?? '1', ['class' => 'form-control'])  !!}
        </div>

        <div class="form-group">
            {!! Form::label('entradas', 'Valor de Depositos') !!}
            {!! Form::text('entradas', null, ['placeholder' => 'Depositos', 'class' => 'form-control decimal-mask']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('saidas', 'Valor de Saques') !!}
            {!! Form::text('saidas', null, ['placeholder' => 'Saques', 'class' => 'form-control decimal-mask']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('saldo', 'Valor de Saldo') !!}
            {!! Form::text('saldo', null, ['placeholder' => 'Saldo', 'class' => 'form-control decimal-mask']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('corretora_id', 'Corretora') !!}
            {!! Form::select('corretora_id', $corretoras_list, null, ['placeholder' => '-- Selecione a corretora --', 'class' => 'form-control']) !!}
        </div>
    </div>
</div>

{!! Form::submit('Salvar', ['class' => 'btn btn-success']) !!}
<a class="btn btn-warning" href="{{ route('conta.corretora.index') }}">
    Votar para Listagem
</a>

