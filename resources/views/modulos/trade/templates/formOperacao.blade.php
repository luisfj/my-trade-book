<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12 card border-dark mb-3" style="padding-top:15px;">
        <h4>Dados</h4>
        <hr>

        <div class="form-group">
            {!! Form::label('ticket', 'Ticket') !!}
            {!! Form::text('ticket', null, ['placeholder' => 'Ticket', 'class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('abertura', 'Abertura') !!}
            {!! Form::datetime('abertura', null, ['placeholder' => 'Abertura','class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('fechamento', 'Fechamento') !!}
            {!! Form::datetime('fechamento', null, ['placeholder' => 'Fechamento','class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('precoentrada', 'Preço de Entrada') !!}
            {!! Form::text('precoentrada', null, ['placeholder' => 'Preço de Entrada', 'class' => 'form-control decimal-mask']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('precosaida', 'Preço de Saida') !!}
            {!! Form::text('precosaida', null, ['placeholder' => 'Preço de Saida', 'class' => 'form-control decimal-mask']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('tipo', 'Tipo') !!}
            {!! Form::select('tipo', ['Buy' => 'Compra', 'Sell' => 'Venda', 'DEP' => 'Depósito', 'WDL' => 'Saque'], $operacao->tipo ?? 'Buy', ['placeholder' => '-- Tipo --', 'class' => 'form-control'])  !!}
        </div>

        <div class="form-group">
            {!! Form::label('lotes', 'Lotes') !!}
            {!! Form::number('lotes', null, ['placeholder' => 'Lotes', 'class' => 'form-control','step' => '0.01']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('comissao', 'Comissão') !!}
            {!! Form::text('comissao', null, ['placeholder' => 'Comissão', 'class' => 'form-control decimal-mask']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('impostos', 'Imposto') !!}
            {!! Form::text('impostos', null, ['placeholder' => 'Imposto', 'class' => 'form-control decimal-mask']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('swap', 'Swap') !!}
            {!! Form::text('swap', null, ['placeholder' => 'Swap', 'class' => 'form-control decimal-mask']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('resultadobruto', 'Res. Bruto') !!}
            {!! Form::text('resultadobruto', null, ['placeholder' => 'Res. Bruto', 'class' => 'form-control decimal-mask']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('resultado', 'Resultado') !!}
            {!! Form::text('resultado', null, ['placeholder' => 'Resultado', 'class' => 'form-control decimal-mask']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('pips', 'Pips') !!}
            {!! Form::number('pips', null, ['placeholder' => 'Pips', 'class' => 'form-control', 'step' => '1']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('importacao', 'Importação') !!}
            {!! Form::select('importacao', ['1' => 'Sim', '0' => 'Não'], $operacao->importacao ?? '0', ['placeholder' => '-- Importação --', 'class' => 'form-control'])  !!}
        </div>

        <div class="form-group">
            {!! Form::label('moeda_id', 'Moeda') !!}
            {!! Form::select('moeda_id', $moedas_list, null, ['placeholder' => '-- Selecione a moeda --', 'class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('instrumento_id', 'Instrumento') !!}
            {!! Form::select('instrumento_id', $instrumentos_list, null, ['placeholder' => '-- Selecione o instrumento --', 'class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('conta_corretora_id', 'Conta Corretora') !!}
            {!! Form::select('conta_corretora_id', $contacorretora_list, null, ['placeholder' => '-- Selecione a Conta Corretora --', 'class' => 'form-control']) !!}
        </div>
        account
        corretoranome
        alavancagem



    </div>
</div>

{!! Form::submit('Salvar', ['class' => 'btn btn-success']) !!}
<a class="btn btn-warning" href="{{ route('operacao.index') }}">
    Votar para Listagem
</a>

