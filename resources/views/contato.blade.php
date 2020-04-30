@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <h1>
            <span class="material-icons text-info icon-v-bottom" style="font-size: 50px !important;">
                email
            </span>
            <span>Contato</span>
        </h1>
    </div>
</div>

<hr class="bg-warning">

<div class="row">
    <div class="col-md-4 mb-3 mb-md-0">
        <div class="card py-4 h-100">
          <div class="card-body text-center">
            <span class="material-icons fs18">
                chat
            </span>
            <h4 class="text-uppercase m-0">Sistema Interno</h4>
            <hr class="my-4">
            <div class="fs14 text-black-50">
                <a href="#" class="text-info" v-b-modal.modal-reportar-problema>
                    Informar Erro/Melhoria
                </a>
            </div>
          </div>
        </div>
      </div>


      <div class="col-md-4 mb-3 mb-md-0">
        <div class="card py-4 h-100">
          <div class="card-body text-center">
            <span class="material-icons fs18">
                email
            </span>
            <h4 class="text-uppercase m-0">Email</h4>
            <hr class="my-4">
            <div class="fs14 text-black-50">
              <a href="mailto:contato@diario.trade" class="text-info">contato@diario.trade</a>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-4 mb-3 mb-md-0">
        <div class="card py-4 h-100">
          <div class="card-body text-center">
            <span class="material-icons fs18">
                near_me
            </span>
            <h4 class="text-uppercase m-0">Telegram</h4>
            <hr class="my-4">
            <div class="fs14 text-black-50"><a target="_blank" class="text-info" href="https://telegram.me/diariodetrade">t.me/diariodetrade</a></div>
          </div>
        </div>
      </div>
</div>

@endsection
