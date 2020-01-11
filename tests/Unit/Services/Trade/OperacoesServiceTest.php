<?php

namespace Tests\Unit\Services\Trade;

use App\Models\ContaCorretora;
use App\Models\Corretora;
use App\Models\DepositoEmConta;
use App\Models\Instrumento;
use App\Models\Moeda;
use App\Models\Operacoes;
use App\Models\User;
use App\Services\Trade\ContaCorretoraService;
use App\Services\Trade\CorretoraService;
use App\Services\Trade\DepositoEmContaService;
use App\Services\Trade\InstrumentoService;
use App\Services\Trade\MoedaService;
use App\Services\Trade\OperacoesService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use Tests\TestCase;

class OperacoesServiceTest extends TestCase
{
    use DatabaseTransactions;

    private $operacao_falsa;
    private $operacao_falsa_outro_usuario;
    private $usuario_auth;

    private $service;
    private $repository;

    private $transferencia_service;
    private $corretora_service;
    private $conta_service;
    private $conta_serviceMock;
    private $moeda_service;
    private $instrumento_service;

    protected function setUp():void
    {
        parent::setUp();

        $this->moeda_service = new MoedaService(new Moeda());
        $this->corretora_service = new CorretoraService(new Corretora());
        $this->conta_service =  new ContaCorretoraService(new ContaCorretora(), $this->moeda_service, $this->corretora_service);
        $this->conta_serviceMock = $this->mock(ContaCorretoraService::class);
        $this->transferencia_service = $this->mock(DepositoEmContaService::class);
        $this->instrumento_service = new InstrumentoService(new Instrumento());

        $this->repository = new Operacoes();
        $this->service = $this->app->make(OperacoesService::class);// new OperacoesService($this->repository, $this->transferencia_service, $this->conta_service, $this->instrumento_service);

        $this->loginComUsuarioFake();
        $this->conta_adicionada = $this->popularBaseComTresOperacoesDoUsuarioEUmaDeOutroUsuario();
    }

    public function tearDown():void
    {
        parent::tearDown();
        Mockery::close();
    }

    function test_Testar_Dados_Gerados()
    {
        $todas = $this->repository->all();
        $this->assertNotNull($todas);
        $this->assertCount(4, $todas);
        $outro_usuario = $todas->where('usuario_id', '<>', $this->usuario_auth->id)->all();
        $this->assertNotNull($outro_usuario);
        $this->assertCount(1, $outro_usuario);
        $do_usuario = $todas->where('usuario_id', $this->usuario_auth->id)->all();
        $this->assertNotNull($do_usuario);
        $this->assertCount(3, $do_usuario);
    }

    public function test_Create_Com_Ticket_Que_Nao_Existe_Deve_Criar_Uma_Operacao_Nova()
    {
        $operacao = $this->criarOperacaoFalsa();
        $operacao['ticket'] = 'TK9999';
        $operacao['account'] = '1234';
        $operacao['corretoranome'] = 'corretoraDemo';

        $this->service->create($operacao);

        $todas = $this->repository->all();
        $this->assertNotNull($todas);
        $this->assertCount(5, $todas);
        $do_usuario = $todas->where('usuario_id', $this->usuario_auth->id)->all();
        $this->assertNotNull($do_usuario);
        $this->assertCount(4, $do_usuario);
        $this->assertDatabaseHas('operacoes', [
            'ticket' => 'TK9999',
            'account' => '1234',
            'corretoranome' => 'corretoraDemo',
            'usuario_id' => $this->usuario_auth->id
        ]);
    }

    public function test_Create_Com_Ticket_Que_Existe_Deve_Atualizar_A_Operacao_Existente()
    {
        $todas = $this->repository->all();
        $this->assertNotNull($todas);
        $this->assertCount(4, $todas);

        $operacao = $this->criarOperacaoFalsa();
        $operacao['account'] = '1234567';
        $operacao['corretoranome'] = 'corretoraDemonstra';

        $this->service->create($operacao);

        $todas = $this->repository->all();
        $this->assertNotNull($todas);
        $this->assertCount(4, $todas);

        $do_usuario = $todas->where('usuario_id', $this->usuario_auth->id)->all();

        $this->assertNotNull($do_usuario);
        $this->assertCount(3, $do_usuario);
        $this->assertDatabaseHas('operacoes', [
            'ticket' => $operacao['ticket'],
            'account' => '1234567',
            'corretoranome' => 'corretoraDemonstra',
            'usuario_id' => $this->usuario_auth->id
        ]);
        $this->assertDatabaseMissing('operacoes', [
            'ticket' => $operacao['ticket'],
            'account' => '4321',
            'corretoranome' => 'corretoraDemoDois',
            'usuario_id' => $this->usuario_auth->id
        ]);
    }

    public function test_Update_Com_Id_Inexistente_Deve_Gerar_Excecao()
    {
        $this->expectException(ModelNotFoundException::class);
        $operacao = $this->criarOperacaoFalsa();

        $this->service->update($operacao, 0);
    }

    public function test_Update_Deve_Alterar_A_Operacao()
    {
        $operacao = $this->criarOperacaoFalsa();
        $operacao['account'] = '1111';
        $operacao['corretoranome'] = 'corretoraAlterada';

        $this->service->update($operacao, $this->operacao_falsa->id);

        $opera_at = $this->service->getById($this->operacao_falsa->id);

        $this->assertNotNull($opera_at);
        $this->assertEquals($this->operacao_falsa->id, $opera_at->id);
        $this->assertEquals('1111', $opera_at->account);
        $this->assertEquals('corretoraAlterada', $opera_at->corretoranome);
        $this->assertNotEquals($this->operacao_falsa->account, $opera_at->account);
        $this->assertNotEquals($this->operacao_falsa->corretoranome, $opera_at->corretoranome);

    }

    public function test_Delete_Deve_Remover_A_Operacao_Pelo_Id()
    {
        $todos = $this->service->getAllByUser();
        $this->assertCount(3, $todos);

        $this->service->delete($this->operacao_falsa->id);

        $todos = $this->service->getAllByUser();
        $this->assertCount(2, $todos);

        $this->assertDatabaseMissing('operacoes', [
            'id' => $this->operacao_falsa->id
        ]);
    }

    public function test_Delete_De_Operacao_De_Outro_Usuario_Deve_Gerar_Exception()
    {
        $this->expectException(ModelNotFoundException::class);
        $todos = $this->service->getAllByUser();
        $this->assertCount(3, $todos);

        $this->service->delete($this->operacao_falsa_outro_usuario->id);

        $this->fail('Deveria ter gerado exceção de modelnotfound por se tratar de uma operação de outro usuario');
    }

    public function test_getById_Com_Id_De_Operacao_De_Outro_Usuario_Deve_Gerar_Excecao()
    {
        $this->expectException(ModelNotFoundException::class);
        $op_outro_usu = $this->service->getById($this->operacao_falsa_outro_usuario->id);
        $this->fail('Deveria ter gerado exceção de ModelNotFound por ser uma operação de outro usuário');
    }

    public function test_getById_Com_Id_Que_Nao_Existe_Deve_Gerar_Excecao()
    {
        $this->expectException(ModelNotFoundException::class);
        $op_inexistente = $this->service->getById(0);
        $this->fail('Deveria ter gerado exceção de ModelNotFound por ser uma operação que não existe no sistema');
    }

    public function test_getById_Deve_Retornar_Instancia_Da_Operacao_Buscada()
    {
        $op = $this->service->getById($this->operacao_falsa->id);
        $this->assertNotNull($op);
        $this->assertEquals($this->operacao_falsa->id, $op->id);
    }

    public function test_getByTicket_Com_Ticket_De_Outro_Usuario_Deve_Retornar_Null()
    {
        $op_outro_usuario  = factory(Operacoes::class)->create([
            'account'       => '1234',
            'corretoranome' => 'corretoraDemo',
            'ticket'        => 'otick123',
            'precoentrada'   => 50000.12345,
            'precosaida'     => 0.00159,
            'lotes'          => 1.03,
            'comissao'       => 100,
            'impostos'       => 20.1,
            'swap'           => 3000.81,
            'resultadobruto' => 1000000.05,
            'resultado'      => 0.30,
            'usuario_id'     => function(){
                                    return User::where(
                                        'name', 'mock dois')->where(
                                        'email', 'teste2@mock.com.py')->first()->id;
                                }
        ]);

        $op_outro_usu = $this->service->getByTicket($op_outro_usuario->ticket);
        $this->assertNull($op_outro_usu);
//tem uma operação com o mesmo ticket para os 2 usuarios, se buscar tem que retornar a do usuario logado
        $op_outro_usu = $this->service->getByTicket($this->operacao_falsa_outro_usuario->ticket);
        $this->assertNotNull($op_outro_usu);
        $this->assertNotEquals($this->operacao_falsa_outro_usuario->id, $op_outro_usu->id);
    }

    public function test_getByTicket_Com_Ticket_Que_Nao_Existe_Deve_Retornar_Null()
    {
        $op_nao_existe = $this->service->getByTicket('testeNaoExiste');
        $this->assertNull($op_nao_existe);
    }

    public function test_getByTicket_Deve_Retornar_Instancia_Da_Operacao_Buscada()
    {
        $op = $this->service->getByTicket($this->operacao_falsa->ticket);
        $this->assertNotNull($op);
        $this->assertEquals($this->operacao_falsa->id, $op->id);
        $this->assertEquals($this->operacao_falsa->ticket, $op->ticket);
    }

    public function test_importarOperacoes_Testar_Cabecalho()
    {
        $this->conta_serviceMock->shouldReceive('getByCodigoOrCreate')
            ->with('ContaTeste','corretoraTest','USD Teste')
            ->once()
            ->andReturn(new ContaCorretora(['identificador' => 'teste']));
        $conta = $this->service->importarCabecalho('corretoraTest', $this->getCabecalhoDemonstracao());
        $this->assertNotNull($conta);
        $this->assertEquals('teste', $conta->identificador);
    }

    public function test_importarOperacoes_Testar_Depositos()
    {
        $this->transferencia_service->shouldReceive('adicionarSeNaoExistir')
            ->with('T1', '2019-12-31', 'XXX1', 50.30, null)
            ->once()
            ->andReturn(true);
        $this->transferencia_service->shouldReceive('adicionarSeNaoExistir')
            ->with('T2', '2019-12-31', 'XXX2', 2000.25, null)
            ->once()
            ->andReturn(true);
        $this->transferencia_service->shouldReceive('adicionarSeNaoExistir')
            ->with('T3', '2019-12-31', 'XXX3', 80.45, null)
            ->once()
            ->andReturn(false);
        $conta = $this->service->importarDepositos($this->getDepositosDemonstracao(), null);
        $this->assertNotNull($conta);
        $this->assertEquals(['adicionados' => 2, 'valor' => 2050.55], $conta);
    }

    public function test_importarOperacoes_Testar_Trades_Fechados()
    {
        $conta = factory(ContaCorretora::class)->create();
        $tradesFechados = $this->service->importarTradesFechados($this->getOperacoesFechadasDemonstracao(), 'conta123', 'corretora_teste', '1:500', $conta);

        $this->assertNotNull($tradesFechados);

        $operacoesAdicionadas = $tradesFechados['operacoesAdicionadas'];
        $operacoesAbertas     = $tradesFechados['operacoesAbertas'];
        $operacoesFechadas    = $tradesFechados['operacoesFechadas'];
        $valorOperacoes       = $tradesFechados['valorOperacoes'];

        $this->assertEquals(3, $operacoesAdicionadas);
        $this->assertEquals(3, $operacoesAbertas);
        $this->assertEquals(3, $operacoesFechadas);
        $this->assertEquals(3.51, $valorOperacoes);

        $this->assertDatabaseHas('operacoes', [
                'tipo'       => 'buy',
                'ticket'     => 'b01',
                'abertura'   => '2019-12-25 08:00:00',
                'lotes'      => 0.01,
                'resultado'  => 1.20,
                'usuario_id' => $this->usuario_auth->id
            ]);
        $this->assertDatabaseHas('operacoes', [
                'tipo'       => 'buy',
                'ticket'     => 'b02',
                'abertura'   => '2019-12-25 10:00:00',
                'lotes'      => 0.02,
                'resultado'  => -0.65,
                'usuario_id' => $this->usuario_auth->id
            ]);
        $this->assertDatabaseHas('operacoes', [
                'tipo'       => 'sell',
                'ticket'     => 's02',
                'abertura'   => '2019-12-25 11:10:00',
                'lotes'      => 0.02,
                'resultado'  => 2.96,
                'usuario_id' => $this->usuario_auth->id
            ]);
    }

    public function test_importarOperacoes_Testar_Trades_Fechados_Importando_Trades_Existentes_Nao_Deve_Adicionar()
    {
        $conta = factory(ContaCorretora::class)->create();
        $this->service->importarTradesFechados($this->getOperacoesFechadasDemonstracao(), 'conta123', 'corretora_teste', '1:500', $conta);

        $tradesFechados = $this->service->importarTradesFechados($this->getOperacoesFechadasDemonstracao(), 'conta123', 'corretora_teste', '1:500', $conta);

        $this->assertNotNull($tradesFechados);

        $operacoesAdicionadas = $tradesFechados['operacoesAdicionadas'];
        $operacoesAbertas     = $tradesFechados['operacoesAbertas'];
        $operacoesFechadas    = $tradesFechados['operacoesFechadas'];
        $valorOperacoes       = $tradesFechados['valorOperacoes'];

        $this->assertEquals(0, $operacoesAdicionadas);
        $this->assertEquals(0, $operacoesAbertas);
        $this->assertEquals(0, $operacoesFechadas);
        $this->assertEquals(0, $valorOperacoes);
    }

    public function test_importarOperacoes_Testar_Trades_Abertos()
    {
        $conta = factory(ContaCorretora::class)->create();
        $tradesAbertos = $this->service->importarTradesAbertos($this->getOperacoesAbertasDemonstracao(), 'conta123', 'corretora_teste', '1:500', $conta);

        $this->assertNotNull($tradesAbertos);

        $operacoesAdicionadas = $tradesAbertos['operacoesAdicionadas'];
        $operacoesAbertas     = $tradesAbertos['operacoesAbertas'];

        $this->assertEquals(2, $operacoesAdicionadas);
        $this->assertEquals(2, $operacoesAbertas);

        $this->assertDatabaseHas('operacoes', [
                'tipo'       => 'buy',
                'ticket'     => 'b03',
                'abertura'   => '2019-12-25 08:00:00',
                'lotes'      => 0.01,
                'precoentrada'=> 1520.00,
                'usuario_id' => $this->usuario_auth->id
            ]);
        $this->assertDatabaseHas('operacoes', [
                'tipo'       => 'sell',
                'ticket'     => 's02',
                'abertura'   => '2019-12-25 11:10:00',
                'lotes'      => 0.02,
                'precoentrada'=> 1600.00,
                'usuario_id' => $this->usuario_auth->id
            ]);
    }

    public function test_importarOperacoes_Testar_Trades_Abertos_Importando_Trades_Existentes_Nao_Deve_Adicionar()
    {
        $conta = factory(ContaCorretora::class)->create();
        $this->service->importarTradesAbertos($this->getOperacoesAbertasDemonstracao(), 'conta123', 'corretora_teste', '1:500', $conta);

        $tradesAbertos = $this->service->importarTradesAbertos($this->getOperacoesAbertasDemonstracao(), 'conta123', 'corretora_teste', '1:500', $conta);

        $this->assertNotNull($tradesAbertos);

        $operacoesAdicionadas = $tradesAbertos['operacoesAdicionadas'];
        $operacoesAbertas     = $tradesAbertos['operacoesAbertas'];

        $this->assertEquals(0, $operacoesAdicionadas);
        $this->assertEquals(0, $operacoesAbertas);
    }

    public function test_importarOperacoes_Testar_Fechar_Trades_Abertos()
    {
        $conta = factory(ContaCorretora::class)->create();
        $tradesAbertos = $this->service->importarTradesAbertos($this->getOperacoesAbertasDemonstracao(), 'conta123', 'corretora_teste', '1:500', $conta);

        $operacoesAdicionadas = $tradesAbertos['operacoesAdicionadas'];
        $operacoesAbertas     = $tradesAbertos['operacoesAbertas'];

        $this->assertEquals(2, $operacoesAdicionadas);
        $this->assertEquals(2, $operacoesAbertas);

        $tradesFechados = $this->service->importarTradesFechados($this->getOperacoesFechadasDemonstracao(), 'conta123', 'corretora_teste', '1:500', $conta);

        $this->assertNotNull($tradesFechados);

        $operacoesAdicionadas = $tradesFechados['operacoesAdicionadas'];
        $operacoesAbertas     = $tradesFechados['operacoesAbertas'];
        $operacoesFechadas    = $tradesFechados['operacoesFechadas'];
        $valorOperacoes       = $tradesFechados['valorOperacoes'];

        $this->assertEquals(3, $operacoesAdicionadas);
        $this->assertEquals(2, $operacoesAbertas);
        $this->assertEquals(3, $operacoesFechadas);
        $this->assertEquals(3.51, $valorOperacoes);

        $this->assertDatabaseHas('operacoes', [
                'tipo'       => 'sell',
                'ticket'     => 's02',
                'abertura'   => '2019-12-25 11:10:00',
                'lotes'      => 0.02,
                'resultado'  => 2.96,
                'fechamento' => '2019-12-25 11:22:00',
                'usuario_id' => $this->usuario_auth->id
            ]);
    }

    public function test_importarOperacoes_Testar_Fechar_Trades_Abertos_Importando_Trades_Existentes_Nao_Deve_Adicionar()
    {
        $conta = factory(ContaCorretora::class)->create();
        $this->service->importarTradesAbertos($this->getOperacoesAbertasDemonstracao(), 'conta123', 'corretora_teste', '1:500', $conta);
        $this->service->importarTradesFechados($this->getOperacoesFechadasDemonstracao(), 'conta123', 'corretora_teste', '1:500', $conta);
        $tradesFechados = $this->service->importarTradesFechados($this->getOperacoesFechadasDemonstracao(), 'conta123', 'corretora_teste', '1:500', $conta);
        $this->assertNotNull($tradesFechados);

        $operacoesAdicionadas = $tradesFechados['operacoesAdicionadas'];
        $operacoesAbertas     = $tradesFechados['operacoesAbertas'];
        $operacoesFechadas    = $tradesFechados['operacoesFechadas'];
        $valorOperacoes       = $tradesFechados['valorOperacoes'];

        $this->assertEquals(0, $operacoesAdicionadas);
        $this->assertEquals(0, $operacoesAbertas);
        $this->assertEquals(0, $operacoesFechadas);
        $this->assertEquals(0, $valorOperacoes);
    }

    public function test_importarOperacoes_Deve_Importar_E_Retornar_Mensagem_Dizendo()
    {
        $this->transferencia_service->shouldReceive('adicionarSeNaoExistir')
            ->with('T1', '2019-12-31', 'XXX1', 50.30, Mockery::any())
            ->andReturn(true);
        $this->transferencia_service->shouldReceive('adicionarSeNaoExistir')
            ->with('T2', '2019-12-31', 'XXX2', 2000.25, Mockery::any())
            ->andReturn(true);
        $this->transferencia_service->shouldReceive('adicionarSeNaoExistir')
            ->with('T3', '2019-12-31', 'XXX3', 80.45, Mockery::any())
            ->andReturn(false);

        $this->conta_serviceMock->shouldReceive('getByCodigoOrCreate')
            ->andReturn(factory(ContaCorretora::class)->create());

        $this->conta_serviceMock->shouldReceive('atualizarSaldoContaPorOperacoes')
            ->with(Mockery::any(), '3.51', 4, 3)
            ->once();

        $retornoImportacao = $this->service->importarOperacoes('corretora_teste', $this->getCabecalhoDemonstracao(), $this->getDepositosDemonstracao(), $this->getOperacoesAbertasDemonstracao(), $this->getOperacoesFechadasDemonstracao());

        $this->assertNotNull($retornoImportacao);

        $this->assertEquals('Importação concluida! Foram adicionados 4 operações e 2 transferencias.', $retornoImportacao);
    }

    public function test_importarOperacoes_Que_Ja_Existem_Nao_Deve_Importar_E_Retornar_Mensagem_Dizendo_Zero_Importados()
    {
        $this->transferencia_service->shouldReceive('adicionarSeNaoExistir')
            ->with('T1', '2019-12-31', 'XXX1', 50.30, Mockery::any())
            ->andReturn(true);
        $this->transferencia_service->shouldReceive('adicionarSeNaoExistir')
            ->with('T2', '2019-12-31', 'XXX2', 2000.25, Mockery::any())
            ->andReturn(true);
        $this->transferencia_service->shouldReceive('adicionarSeNaoExistir')
            ->with('T3', '2019-12-31', 'XXX3', 80.45, Mockery::any())
            ->andReturn(false);

        $this->conta_serviceMock->shouldReceive('getByCodigoOrCreate')
            ->andReturn(factory(ContaCorretora::class)->create());

        $this->conta_serviceMock->shouldReceive('atualizarSaldoContaPorOperacoes')
            ->with(Mockery::any(), '3.51', 4, 3)
            ->once();
            $this->conta_serviceMock->shouldReceive('atualizarSaldoContaPorOperacoes')
            ->with(Mockery::any(), '0.00', 0, 0)
            ->once();

        $this->service->importarOperacoes('corretora_teste', $this->getCabecalhoDemonstracao(), $this->getDepositosDemonstracao(), $this->getOperacoesAbertasDemonstracao(), $this->getOperacoesFechadasDemonstracao());
        $retornoImportacao = $this->service->importarOperacoes('corretora_teste', $this->getCabecalhoDemonstracao(), $this->getDepositosDemonstracao(), $this->getOperacoesAbertasDemonstracao(), $this->getOperacoesFechadasDemonstracao());

        $this->assertNotNull($retornoImportacao);

        $this->assertEquals('Importação concluida! Foram adicionados 0 operações e 2 transferencias.', $retornoImportacao);
    }

    /* Funcoes Auxiliares */
    private function getOperacoesFechadasDemonstracao(){
        return [ 0 => [
                    'tipo' => 'buy',
                    'ticket' => 'b01',
                    'abertura' => '2019-12-25 08:00:00',
                    'contratos' => 0.01,
                    'instrumento' => 'xauusd',
                    'preco_entrada' => 1520.00,
                    'fechamento' => '2019-12-25 08:32:18',
                    'preco_saida' => 1640.00,
                    'comissao' => 0.00,
                    'impostos' => 0.00,
                    'swap' => 0.00,
                    'resultado_bruto' => 1.20,
                    'resultado' => 1.20,
                    'pontos' => 120,
                    'tempo_operacao_dias' => '0',
                    'tempo_operacao_horas' => '00:32:18',
                    ],
                1 => [
                    'tipo' => 'buy',
                    'ticket' => 'b02',
                    'abertura' => '2019-12-25 10:00:00',
                    'contratos' => 0.02,
                    'instrumento' => 'xauusd',
                    'preco_entrada' => 1600.00,
                    'fechamento' => '2019-12-26 10:32:00',
                    'preco_saida' => 1540.00,
                    'comissao' => 0.00,
                    'impostos' => 0.00,
                    'swap' => 0.15,
                    'resultado_bruto' => -0.80,
                    'resultado' => -0.65,
                    'pontos' => -40,
                    'tempo_operacao_dias' => '1',
                    'tempo_operacao_horas' => '00:32:00',
                ],
                2 => [
                    'tipo' => 'sell',
                    'ticket' => 's02',
                    'abertura' => '2019-12-25 11:10:00',
                    'contratos' => 0.02,
                    'instrumento' => 'xauusd',
                    'preco_entrada' => 1600.00,
                    'fechamento' => '2019-12-25 11:22:00',
                    'preco_saida' => 1440.00,
                    'comissao' => 0.12,
                    'impostos' => 0.00,
                    'swap' => 0.00,
                    'resultado_bruto' => 3.20,
                    'resultado' => 2.96,
                    'pontos' => 160,
                    'tempo_operacao_dias' => '0',
                    'tempo_operacao_horas' => '00:12:00',
                ]
        ];
    }

    private function getOperacoesAbertasDemonstracao(){
        return [ 0 => [
                    'tipo' => 'buy',
                    'ticket' => 'b03',
                    'abertura' => '2019-12-25 08:00:00',
                    'contratos' => 0.01,
                    'instrumento' => 'xauusd',
                    'preco_entrada' => 1520.00,
                    ],
                1 => [
                    'tipo' => 'sell',
                    'ticket' => 's02',
                    'abertura' => '2019-12-25 11:10:00',
                    'contratos' => 0.02,
                    'instrumento' => 'xauusd',
                    'preco_entrada' => 1600.00,
                ]
        ];
    }

    private function getCabecalhoDemonstracao(){
        return [0 => [
            'conta'       => 'ContaTeste',
            'currency'    => 'USD Teste',
            'alavancagem' => '1:500'
        ]];
    }

    private function getDepositosDemonstracao(){
        return [
            0 => [
                'ticket' => 'T1',
                'data'   => '2019-12-31',
                'codigo' => 'XXX1',
                'valor'  => 50.30
            ],
            1 => [
                'ticket' => 'T2',
                'data'   => '2019-12-31',
                'codigo' => 'XXX2',
                'valor'  => 2000.25
            ],
            2 => [
                'ticket' => 'T3',
                'data'   => '2019-12-31',
                'codigo' => 'XXX3',
                'valor'  => 80.45
            ],
        ];
    }

    private function popularBaseComTresOperacoesDoUsuarioEUmaDeOutroUsuario()
    {
        factory(Operacoes::class)->create([
            'account'       => '1234',
            'corretoranome' => 'corretoraDemo',
            'ticket'        => 'tick123',
            'precoentrada'   => 50000.12345,
            'precosaida'     => 0.00159,
            'lotes'          => 1.03,
            'comissao'       => 100,
            'impostos'       => 20.1,
            'swap'           => 3000.81,
            'resultadobruto' => 1000000.05,
            'resultado'      => 0.30,
        ]);

        $this->operacao_falsa  = factory(Operacoes::class)->create([
            'account'       => '1234',
            'corretoranome' => 'corretoraDemo',
            'ticket'        => 'tick124',
            'precoentrada'   => 50001.12345,
            'precosaida'     => 0.10159,
            'lotes'          => 2.00,
            'comissao'       => 101,
            'impostos'       => 21.1,
            'swap'           => 3001.81,
            'resultadobruto' => 1000001.00,
            'resultado'      => 1.30,
        ]);

        factory(Operacoes::class)->create([
            'account'       => '4321',
            'corretoranome' => 'corretoraDemoDois',
            'ticket'        => 'tick125',
            'precoentrada'   => 50001.12345,
            'precosaida'     => 0.10159,
            'lotes'          => 2.00,
            'comissao'       => 101,
            'impostos'       => 21.1,
            'swap'           => 3001.81,
            'resultadobruto' => 1000001.00,
            'resultado'      => 1.30,
        ]);

        $this->operacao_falsa_outro_usuario  = factory(Operacoes::class)->create([
            'account'       => '1234',
            'corretoranome' => 'corretoraDemo',
            'ticket'        => 'tick123',
            'precoentrada'   => 50000.12345,
            'precosaida'     => 0.00159,
            'lotes'          => 1.03,
            'comissao'       => 100,
            'impostos'       => 20.1,
            'swap'           => 3000.81,
            'resultadobruto' => 1000000.05,
            'resultado'      => 0.30,
            'usuario_id'     => function(){
                                    return factory(User::class)->create([
                                        'name'  => 'mock dois',
                                        'email' => 'teste2@mock.com.py'
                                    ])->id;
                                }
        ]);
    }

    private function criarOperacaoFalsa($account = '4321', $corretora = 'corretoraDemoDois', $ticket='tick124')
    {
        return [
            'account'        => $account,
            'corretoranome'  => $corretora,
            'ticket'         => $ticket,
            'precoentrada'   => 50001.12345,
            'precosaida'     => 0.10159,
            'lotes'          => 2.00,
            'tipo'           => 'buy',
            'comissao'       => 101,
            'impostos'       => 21.1,
            'swap'           => 3001.81,
            'resultadobruto' => 1000001.00,
            'resultado'      => 1.30,
            'moeda_id'       => $this->moeda_service->getBySiglaOrCreate('USD')->id,
            'instrumento_id' => $this->instrumento_service->getBySigla('xauusd')->id,
            'conta_corretora_id' => $this->conta_service->getByCodigoOrCreate('ID001', $this->instrumento_service->getBySigla('xauusd')->corretora, $this->moeda_service->getBySiglaOrCreate('USD'))->id,
            'usuario_id'     => $this->usuario_auth->id,
        ];
    }

    private function loginComUsuarioFake()
    {
        $this->usuario_auth = factory(User::class)->create();

        $this->be($this->usuario_auth);
    }

}
