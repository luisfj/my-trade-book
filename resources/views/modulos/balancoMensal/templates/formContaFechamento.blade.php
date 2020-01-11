<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12 card border-dark mb-3" style="padding-top:15px;">
        <h4>
            Dados
        </h4>
        <hr>

        <div class="form-group">
            {!! Form::label('nome', 'Nome') !!}
            {!! Form::text('nome', null, ['placeholder' => 'Nome', 'class' => 'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('tipo', 'Tipo') !!}
            {!! Form::select('tipo', [
                'carteira'       => 'Carteira',
                'conta_corrente' => 'Conta Corrente',
                'conta_poupanca' => 'Poupança',
                'carteira_virtual'=>'Carteira Virtual',
                'conta_virtual'  => 'Conta Virtual',
                'outras'         => 'Outras'
            ], $conta->tipo ?? 'carteira',
                    ['placeholder' => '-- Tipo --', 'class' => 'form-control'])  !!}
        </div>

        <div class="form-group">
            {!! Form::label('abertura', 'Data Abertura') !!}
            {!! Form::date('abertura', null, ['placeholder' => 'Data Abertura','class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('saldo_inicial', 'Saldo Inicial') !!}
            {!! Form::text('saldo_inicial', null, ['placeholder' => 'Saldo Inicial', 'class' => 'form-control decimal-mask']) !!}
        </div>

    </div>
</div>

{!! Form::submit('Salvar', ['class' => 'btn btn-success']) !!}
<a class="btn btn-warning" href="javascript:history.back()">
    Votar para Listagem
</a>
