<div class="row">
    <div class="col-sm-6 col-md-6 col-lg-6" style="padding-top:15px;">
        <div class="form-group">
            {!! Form::label('identificador', 'Identificador da Conta') !!}
            {!! Form::text('identificador', null, ['placeholder' => 'Identificador', 'class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-sm-6 col-md-6 col-lg-6" style="padding-top:15px;">
        <div class="form-group">
            {!! Form::label('dtabertura', 'Data de Abertura da Conta') !!}
            {!! Form::date('dtabertura', null, ['placeholder' => 'Abertura da Conta','class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-sm-6 col-md-6 col-lg-6">
        <div class="form-group">
            {!! Form::label('moeda_id', 'Moeda') !!}
            <select class="custom-select" id="moeda_id" name="moeda_id">
                <option selected="selected" value="">-- Selecione a moeda --</option>
            </select>
        </div>
    </div>
    <div class="col-sm-6 col-md-6 col-lg-6">
        <div class="form-group">
            {!! Form::label('tipo', 'Tipo') !!}
            {!! Form::select('tipo', ['C' => 'Corretora Nacional', 'D' => 'Corretora Internacional'], null, ['placeholder' => '-- Tipo --', 'class' => 'form-control'])  !!}
        </div>
    </div>
    <div class="col-sm-6 col-md-6 col-lg-6">
        <div class="form-group">
            {!! Form::label('ativa', 'Ativa') !!}
            {!! Form::select('ativa', ['1' => 'Sim', '0' => 'Não'], '1', ['class' => 'form-control'])  !!}
        </div>
    </div>
    <div class="col-sm-6 col-md-6 col-lg-6">
        <div class="form-group">
            {!! Form::label('padrao', 'Conta Padrão') !!}
            {!! Form::select('padrao', ['1' => 'Sim', '0' => 'Não'], '1', ['class' => 'form-control'])  !!}
        </div>
    </div>
    <div class="col-sm-6 col-md-6 col-lg-6">
        <div class="form-group">
            {!! Form::label('real_demo', 'Tipo de Conta') !!}
            {!! Form::select('real_demo', ['R' => 'Real', 'D' => 'Demo'], 'R', ['class' => 'form-control'])  !!}
        </div>
    </div>
    <div class="col-sm-6 col-md-6 col-lg-6">
        <div class="form-group">
            {!! Form::label('corretora_id', 'Corretora') !!}
            <select class="custom-select" id="corretora_id" name="corretora_id">
                <option selected="selected" value="">-- Selecione um Corretora --</option>
            </select>
        </div>
    </div>
    <div class="col-sm-6 col-md-6 col-lg-6 hidde-me" id="corretora_nome">
        <div class="form-group" >
            {!! Form::label('corretora_nm', 'Nome da Corretora') !!}
            {!! Form::text('corretora_nm', null, ['placeholder' => 'Nome da Corretora', 'class' => 'form-control']) !!}
        </div>
    </div>
</div>
