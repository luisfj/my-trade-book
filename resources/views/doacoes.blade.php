@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 text-success">
        <h1>
            <span class="material-icons icon-v-bottom" style="font-size: 50px !important;">
                favorite
            </span>
            <span>Gostou do projeto? Ajude a manter essa idéia viva!</span>
        </h1>
    </div>
</div>

<hr class="bg-warning">

<div class="row justify-content-center">
    <div class="col-md-12 mb-3 mb-md-0">
        <p>
            <strong class="text-white" style="font-weight: 500;">Sobre o projeto</strong>
        </p>
        <p>
            O desenvolvimento deste projeto se deu por necessidade própria, e por não gostar de preencher planilhas manualmente.
        </p>
        <p>
            A idéia sempre foi agilizar esse processo demorado e chato, e trazer, de forma simples, rápida e fácil todos os dados das operações em bolsa,
            sejam elas B3 ou Forex, mineradas com as informações pertinentes para uma análise de performance.
        </p>

        <strong class="text-white" style="font-weight: 500;">O rato que virou elefante</strong>
        <p>
            Inicialmente o projeto seria pequeno: "importar aqui, exibir ali e pronto", mas com o decorrer do desenvolvimento, que se iniciou em setembro de 2019,
            veio ganhando mais e mais funcionalidades, e atualmente alem de vários recursos já desenvolvidos, e uma lista enorme de melhorias e implementações
            a serem feitas, não temos a menor intenção de parar.
        </p>

        <strong class="text-white" style="font-weight: 500;">Sobre as doações</strong>
        <p>
            Ao subir o projeto nos deparamos com alguns detalhes, "CUSTOS", sim, infelizmente uma hospedagem tem custos, no entanto, acreditando na idéia,
            preferimos renomeá-lo para "INVESTIMENTO", e seguir em frente.
        </p>
        <p>
            Nosso princípio sempre foi manter um projeto free, sem cobrança alguma, sem limitação de recursos, sem propagandas para todo lado, e sempre melhorando a plataforma.
        </p>
        <p>
            Mas devido aos custos para se manter um projeto no ar, ativo, com implementações sendo feitas e com recursos limitados,
            tivemos que rever o princípio original e pensar em uma forma de monetizar a plataforma.
        </p>
        <p>
            Acreditando na qualidade do produto, e na parceria da comunidade de traders, os quais podem usufruir da plataforma, resolvemos caminhar em uma direção.
            <strong>Queremos manter a premissa de uma plataforma free, sem limitações.</strong> Então, em um primeiro momento estaremos a monetizar vide doações dos usuários
            que apreciaram nosso trabalho, e que acreditam nos seus benefícios. Essas doações podem ser feitas com qualquer valor, e pelos sistemas
            seguros do pagseguro e do paypal. Em um proximo passo, adicionaremos propagandas a plataforma. Por base essas são nossas
            intenções, e temos total crença de que nada além disso será necessário para manter o projeto progredindo!
        </p>

        <strong class="text-white" style="font-weight: 500;">Agradecemos sua compreenção e ajuda! Com toda certeza você está ajudando a manter essa idéia viva!!</strong>
    </div>

    <div class="col-md-4 mb-3 mb-md-0 padt-20">
        <div class="card py-4 h-100">
          <div class="card-body text-center">
            <span class="material-icons fs18">
                favorite
            </span>
            <h4 class="text-uppercase m-0">PayPal</h4>
            <hr class="my-4">
            <div class="fs14 text-black-50">
                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
                    <input type="hidden" name="cmd" value="_donations" />
                    <input type="hidden" name="business" value="Z65RTMM7HNT4J" />
                    <input type="hidden" name="currency_code" value="BRL" />
                    <input type="image" src="https://www.paypalobjects.com/pt_BR/BR/i/btn/btn_donateCC_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Faça doações com o botão do PayPal" />
                    <img alt="" border="0" src="https://www.paypal.com/pt_BR/i/scr/pixel.gif" width="1" height="1" />
                </form>
            </div>
          </div>
        </div>
      </div>


      <div class="col-md-4 mb-3 mb-md-0 padt-20">
        <div class="card py-4 h-100">
          <div class="card-body text-center">
            <span class="material-icons fs18">
                favorite
            </span>
            <h4 class="text-uppercase m-0">PagSeguro</h4>
            <hr class="my-4">
            <div class="fs14 text-black-50">
              <!-- INICIO FORMULARIO BOTAO PAGSEGURO -->
                <form action="https://pagseguro.uol.com.br/checkout/v2/donation.html" method="post" target="_blank">
                    <!-- NÃO EDITE OS COMANDOS DAS LINHAS ABAIXO -->
                    <input type="hidden" name="currency" value="BRL" />
                    <input type="hidden" name="receiverEmail" value="md.luisfernando@gmail.com" />
                    <input type="hidden" name="iot" value="button" />
                    <input type="image" src="https://stc.pagseguro.uol.com.br/public/img/botoes/doacoes/209x48-doar-assina.gif" name="submit" alt="Pague com PagSeguro - é rápido, grátis e seguro!" />
                </form>
                <!-- FINAL FORMULARIO BOTAO PAGSEGURO -->
            </div>
          </div>
        </div>
      </div>

</div>

@endsection
