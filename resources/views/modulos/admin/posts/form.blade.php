<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12 card border-dark mb-3" style="padding-top:15px;">
        <h4>Dados</h4>
        <hr>

        <div class="form-group">
            {!! Form::label('title', 'Titulo') !!}
            {!! Form::text('title', null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('body', 'Descrição') !!}
            {!! Form::text('body', null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('exibir', 'Exibir no quadro') !!}
            {!! Form::select('exibir', ['1' => 'Sim', '0' => 'Não'], $post->exibir ?? '1', [ 'class' => 'form-control'])  !!}
        </div>

        <div class="form-group">
            {!! Form::label('tipo', 'Tipo') !!}
            {!! Form::select('tipo', ['M' => 'Mensagem', 'E' => 'Enquete'], $post->tipo ?? 'M', ['class' => 'form-control']) !!}
        </div>

        <div id="dataFim" class="form-group">
            {!! Form::label('data_fim_enquete', 'Fim da enquete') !!}
            {!! Form::date('data_fim_enquete', null, ['class' => 'form-control']) !!}
        </div>

        <div id="resPublico" class="form-group">
            {!! Form::label('resultado_publico', 'Resultado público') !!}
            {!! Form::select('resultado_publico', ['1' => 'Sim', '0' => 'Não'], $post->resultado_publico ?? '0', ['class' => 'form-control'])  !!}
        </div>

        <div id="resMultiescolha" class="form-group">
            {!! Form::label('multiescolha', 'Respostas multiplas') !!}
            {!! Form::select('multiescolha', ['1' => 'Sim', '0' => 'Não'], $post->multiescolha ?? '0', ['class' => 'form-control'])  !!}
        </div>

        <table id="opcoesEnquete" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="35%">Nome</th>
                    <th width="35%">Detalhamento</th>
                    <th width="30%">Ações <button type="button" name="add" id="add" class="btn btn-info">Add Opção</button></th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>

    </div>
</div>

{!! Form::submit('Salvar', ['class' => 'btn btn-success']) !!}
<a href="{{ route('posts.index') }}" class="btn btn-warning">Ir para a Lista</a>
