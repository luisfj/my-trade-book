<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();


Route::middleware('super.admin')->prefix('sa')->group(function () {
    Route::post('userUpdateRole', 'Admin\UsersController@updateRole')->name('users.update.role');

    //Route::get(     'fechamentos-mes',           'BalancoMensal\FechamentoMesController@index')   ->name('fechamento.mes.index');
    //Route::get(     'fechamento-mes-edit/{id}', 'BalancoMensal\FechamentoMesController@edit')    ->name('fechamento.mes.edit');
    Route::get(     'fechamento-mes',           'BalancoMensal\FechamentoMesController@show')     ->name('fechamento.mes.show');
    Route::get(     'fechamento-mes/add',           'BalancoMensal\FechamentoMesController@add')  ->name('fechamento.mes.add');
    Route::get('fechamento-mes/grid/filtrado',   'BalancoMensal\FechamentoMesController@gridFiltrado')->name('fechamento.mes.grid.filtrados');
    Route::post('fechamento-mes/grid/filtrado',   'BalancoMensal\FechamentoMesController@gridFiltrado')->name('fechamento.mes.grid.filtrado');
    Route::post('fechamento-mes/grafico/filtrado',   'BalancoMensal\FechamentoMesController@graficoFiltrado')->name('fechamento.mes.grafico.filtrado');
    Route::post('fechamento-mes/grafico/evolucao-saldo',   'BalancoMensal\FechamentoMesController@graficoEvolucaoSaldo')->name('fechamento.mes.grafico.evolucao.saldo');
    //Route::get('fechamento-mes/grafico/evolucao-saldo',   'BalancoMensal\FechamentoMesController@graficoEvolucaoSaldo');
    //Route::put(     'fechamento-mes-edit/{id}', 'BalancoMensal\FechamentoMesController@update')  ->name('fechamento.mes.update');
    Route::put(     'fechamento-mes',           'BalancoMensal\FechamentoMesController@create')  ->name('fechamento.mes.create');
    Route::post(    'fechamento-mes',           'BalancoMensal\FechamentoMesController@select')  ->name('fechamento.mes.select');
    //Route::delete(  'fechamento-mes/{id}',      'BalancoMensal\FechamentoMesController@delete')  ->name('fechamento.mes.delete');

    Route::get(     'contas-fechamento',           'BalancoMensal\ContaFechamentoController@index')   ->name('conta.fechamento.index');
    Route::get(     'conta-fechamento-edit/{id}', 'BalancoMensal\ContaFechamentoController@edit')    ->name('conta.fechamento.edit');
    Route::get(     'conta-fechamento',           'BalancoMensal\ContaFechamentoController@add')     ->name('conta.fechamento.add');
    Route::put(     'conta-fechamento-edit/{id}', 'BalancoMensal\ContaFechamentoController@update')  ->name('conta.fechamento.update');
    Route::post(    'conta-fechamento',           'BalancoMensal\ContaFechamentoController@create')  ->name('conta.fechamento.create');
    Route::delete(  'conta-fechamento/{id}',      'BalancoMensal\ContaFechamentoController@delete')  ->name('conta.fechamento.delete');
});

Route::middleware('admin')->prefix('ad')->group(function () {
    Route::get('users', 'Admin\UsersController@index')->name('users.index');

    Route::get(     'perfis',           'Admin\PerfilInvestidorController@index')   ->name('perfil.index');
    Route::get(     'perfil-edit/{id}', 'Admin\PerfilInvestidorController@edit')    ->name('perfil.edit');
    Route::get(     'perfil',           'Admin\PerfilInvestidorController@add')     ->name('perfil.add');
    Route::put(     'perfil-edit/{id}', 'Admin\PerfilInvestidorController@update')  ->name('perfil.update');
    Route::post(    'perfil',           'Admin\PerfilInvestidorController@create')  ->name('perfil.create');
    Route::delete(  'perfil/{id}',      'Admin\PerfilInvestidorController@delete')  ->name('perfil.delete');

    Route::get( 'posts',            'Posts\PostController@index')   ->name('posts.index');
    Route::get( 'post-show/{id}',   'Posts\PostController@show')    ->name('posts.show');
    Route::put( 'post-show/{id}',   'Posts\PostController@update')  ->name('posts.update');
    Route::get( 'post',             function () { return view('modulos.admin.adicionarPostEnquete'); })->name('posts.add');
    Route::post('post',             'Posts\PostController@create')  ->name('posts.create');

    Route::get(     'moedas',            'Admin\MoedaController@index')  ->name('moeda.index');
    Route::get(     'moeda-edit/{id}',  'Admin\MoedaController@edit')   ->name('moeda.edit');
    Route::get(     'moeda',            'Admin\MoedaController@add')    ->name('moeda.add');
    Route::put(     'moeda-edit/{id}',  'Admin\MoedaController@update') ->name('moeda.update');
    Route::post(    'moeda',            'Admin\MoedaController@create') ->name('moeda.create');
    Route::delete(  'moeda/{id}',       'Admin\MoedaController@delete') ->name('moeda.delete');

    Route::get(     'corretoras',            'Admin\CorretoraController@index')  ->name('corretora.index');
    Route::get(     'corretora-edit/{id}',  'Admin\CorretoraController@edit')   ->name('corretora.edit');
    Route::get(     'corretora',            'Admin\CorretoraController@add')    ->name('corretora.add');
    Route::put(     'corretora-edit/{id}',  'Admin\CorretoraController@update') ->name('corretora.update');
    Route::post(    'corretora',            'Admin\CorretoraController@create') ->name('corretora.create');
    Route::delete(  'corretora/{id}',       'Admin\CorretoraController@delete') ->name('corretora.delete');

    Route::get(     'instrumentos',            'Trade\InstrumentoController@index')  ->name('instrumento.index');
    Route::get(     'instrumento-edit/{id}',   'Trade\InstrumentoController@edit')   ->name('instrumento.edit');
    Route::get(     'instrumento',             'Trade\InstrumentoController@add')    ->name('instrumento.add');
    Route::put(     'instrumento-edit/{id}',   'Trade\InstrumentoController@update') ->name('instrumento.update');
    Route::post(    'instrumento',             'Trade\InstrumentoController@create') ->name('instrumento.create');
    Route::delete(  'instrumento/{id}',        'Trade\InstrumentoController@delete') ->name('instrumento.delete');
});

Route::middleware('auth')->group(function () {
    Route::get( '/home',        'HomeController@index')                         ->name('home');
    Route::post('comment',      'Posts\CommentController@store')                ->name('comment.store');
    Route::get( 'profile',      'Configuracoes\ProfileController@index')        ->name('profile.index');
    Route::put( 'profile',      'Configuracoes\ProfileController@update')       ->name('profile.update');
    Route::get( 'configuracoes','Configuracoes\ConfiguracoesController@index')  ->name('configuracoes.index');
    Route::put( 'configuracoes','Configuracoes\ConfiguracoesController@update') ->name('configuracoes.update');

    Route::get('painel-notificacoes',               'Notifications\NotificationController@painelNotificacoes')  ->name('notifications.panel');
    Route::get('notifications',                     'Notifications\NotificationController@notifications')       ->name('notifications');
    Route::put('notification-read',                 'Notifications\NotificationController@markAsRead');
    Route::put('notification-read-all',             'Notifications\NotificationController@markAllAsRead');
    Route::get('notification/post/{idnotification}','Notifications\NotificationController@getPostFromNotification');
    Route::put('enquete/votar',                     'Posts\VotacaoController@votarEnquete');

    Route::get( 'bugs',             'Bugs\BugController@index')                 ->name('bugs');
    Route::get( 'bug-show',         'Bugs\BugController@show')                  ->name('bug.show');
    Route::post('bugs',             'Bugs\BugController@store')                 ->name('bug.store');
    Route::post('bug-message',      'Bugs\BugController@addMessage')            ->name('bug.addmessage');
    Route::post('bug-verificado',   'Bugs\BugController@marcarComoVerificada')  ->name('bug.verificado');

    Route::get('painel-comunicacao', 'Comunicacao\ComunicaoController@index')   ->name('comunicacao.index');

    Route::get('historico-myfx', 'Downloads\FtpDownloadController@historicoSincFtpMyFxBook');

    Route::get(     'contas-corretora',         'Trade\ContaCorretoraController@index')  ->name('conta.corretora.index');
    Route::get(     'conta-corretora-edit/{id}','Trade\ContaCorretoraController@edit')   ->name('conta.corretora.edit');
    Route::get(     'conta-corretora',          'Trade\ContaCorretoraController@add')    ->name('conta.corretora.add');
    Route::put(     'conta-corretora-edit/{id}','Trade\ContaCorretoraController@update') ->name('conta.corretora.update');
    Route::post(    'conta-corretora',          'Trade\ContaCorretoraController@create') ->name('conta.corretora.create');
    Route::delete(  'conta-corretora/{id}',     'Trade\ContaCorretoraController@delete') ->name('conta.corretora.delete');

    Route::get(     'operacoes',         'Trade\OperacoesController@index')  ->name('operacao.index');
    Route::get(     'operacao-edit/{id}','Trade\OperacoesController@edit')   ->name('operacao.edit');
    Route::get(     'operacao',          'Trade\OperacoesController@add')    ->name('operacao.add');
    Route::put(     'operacao-edit/{id}','Trade\OperacoesController@update') ->name('operacao.update');
    Route::post(    'operacao',          'Trade\OperacoesController@create') ->name('operacao.create');
    Route::delete(  'operacao/{id}',     'Trade\OperacoesController@delete') ->name('operacao.delete');
    Route::get(  'operacoes/importar',     'Trade\OperacoesController@importarOperacoesIndex') ->name('operacao.importar');
    Route::post(  'operacoes/importar',     'Trade\OperacoesController@importarOperacoes');

    Route::get('transacoes' ,  'Transacoes\TransacoesContaController@index')->name('transacoes.index');
    Route::post('transacoes' , 'Transacoes\TransacoesContaController@index')->name('transacoes.filter');
    Route::delete('transacao/{transacao}' , 'Transacoes\TransacoesContaController@remover')  ->name('transacao.delete');
    Route::get(   'transacao-edit/{id}'   , 'Transacoes\TransacoesContaController@editar')   ->name('transacao.edit');
    Route::put(   'transacao-edit/{id}'   , 'Transacoes\TransacoesContaController@atualizar')->name('transacao.update');

});
