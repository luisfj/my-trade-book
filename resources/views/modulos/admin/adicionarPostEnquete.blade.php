@extends('layouts.app')

@section('content')

    <h1 class="text-active">Adicionar Mensagem/Enquete aos Usuários</h1>
    <hr class="bg-warning">

    {!! Form::open(['route' => ['posts.create'], 'method' => 'post']) !!}

        @include('modulos.admin.posts.form')

    {!! Form::close() !!}
        <div class="mb-5"></div>

@endsection


@section('page-script')
<script>
    $(document).ready(function(){
        var count = 0;

        changeTipo();

        function dynamic_field(number){
            var html = '<tr>';
            html += '<td> <input type="text" name="nome[]" class="form-control" /> </td>';
            html += '<td> <input type="text" name="detalhamento[]" class="form-control" /> </td>';
            html += '<td> <button type="button" name="remove" id="remove" class="btn btn-danger">Remover</button> </td></tr>';
            $('tbody').append(html);

        }

        function changeTipo(){
            if($('#tipo').val() == 'E') {//se Enquete
                //Mostro a tabela de opções
                $('#opcoesEnquete').removeClass('hidde-me');
                $('#dataFim').removeClass('hidde-me');
                $('#resPublico').removeClass('hidde-me');
                $('#resMultiescolha').removeClass('hidde-me');
            } else { //se Mensagem
                //Não mostro a tabela de opções
                $('#opcoesEnquete').addClass('hidde-me');
                $('#dataFim').addClass('hidde-me');
                $('#resPublico').addClass('hidde-me');
                $('#resMultiescolha').addClass('hidde-me');
            }
        }


        $('#add').click(function(){
            count++;
            dynamic_field(count);
        });

        $(document).on('click', '#remove', function(){
            if(count > 0){
                count--;
                dynamic_field(count);
            }
        });

        $('#tipo').on('change', changeTipo);
    });
</script>
@endsection
