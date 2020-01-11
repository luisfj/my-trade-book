<?php

namespace Tests\Unit\Services\Trade;

use App\Models\ContaCorretora;
use App\Models\Corretora;
use App\Models\Moeda;
use App\Models\User;
use App\Services\Trade\ContaCorretoraService;
use App\Services\Trade\CorretoraService;
use App\Services\Trade\MoedaService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ContaCorretoraServiceTest extends TestCase
{
    use DatabaseTransactions;

    private $corretora_service;
    private $moeda_service;
    private $repository;
    private $usuario_auth;

    private $service;

    private $conta_adicionada;

    protected function setUp():void
    {
        parent::setUp();
        $this->corretora_service = new CorretoraService(new Corretora());
        $this->moeda_service = new MoedaService(new Moeda());
        $this->repository = new ContaCorretora();
        $this->service = new ContaCorretoraService($this->repository, $this->moeda_service, $this->corretora_service);

        $this->loginComUsuarioFake();
        $this->conta_adicionada = $this->popularBaseComTresContasCorretoras();
    }

    public function test_Create_Incluir_Uma_Nova_Conta_Corretora()
    {
        $dados = $this->criarContaCorretoraDemonstracao();
        $this->service->create($dados);
        $this->assertDatabaseHas('conta_corretoras', [
            'identificador' => 'ID000',
            'usuario_id' => $this->usuario_auth->id
        ]);

        $todos = $this->repository->all();
        $this->assertCount(4, $todos);
    }

    public function test_Update_Uma_Conta_Corretora_Que_Nao_Existe_Deve_Gerar_Excecao_ModelNotFoundException()
    {
        $this->expectException(ModelNotFoundException::class);

        $dados = $this->criarContaCorretoraDemonstracao();
        $this->service->update($dados, 15);

        $this->fail('Deveria ter gerado uma exceção de id nao encontrado ao buscar uma conta com id que nao existe');
    }

    public function test_Update_Uma_Conta_Corretora()
    {
        $dados = $this->criarContaCorretoraDemonstracao('teste0123');

        $this->service->update($dados, $this->conta_adicionada->id);

        $this->assertDatabaseMissing('conta_corretoras', [
            'identificador' => 'ID001',
            'usuario_id' => $this->usuario_auth->id
        ]);

        $this->assertDatabaseHas('conta_corretoras', [
            'identificador' => 'teste0123',
            'usuario_id' => $this->usuario_auth->id
        ]);

        $todos = $this->repository->all();
        $this->assertCount(3, $todos);
    }

    public function test_Update_Uma_Conta_Corretora_De_Outro_Usuario_Deve_Gerar_Excecao()
    {
        $conta_outro_usuario = $this->adicionarContaDeOutroUsuario();

        $this->expectException(ModelNotFoundException::class);

        $this->assertDatabaseHas('conta_corretoras', [
            'identificador' => 'ID109',
            'usuario_id' => $conta_outro_usuario->usuario_id
        ]);

        $todos = $this->repository->all();
        $this->assertCount(4, $todos);

        $dados = $this->criarContaCorretoraDemonstracao('teste0123');

        $this->service->update($dados, $conta_outro_usuario->id);

        $this->fail('Não gerou exceção e atualizou uma conta de outro usuario');
    }

    public function test_Delete_Uma_Conta_Corretora_Que_Nao_Existe_Deve_Gerar_Excecao_ModelNotFoundException()
    {
        $this->expectException(ModelNotFoundException::class);

        $this->service->delete(15);

        $this->fail('Deveria ter gerado uma exceção de id nao encontrado ao buscar uma conta com id que nao existe');
    }

    public function test_Delete_Uma_Conta_Corretora()
    {
        $this->service->delete($this->conta_adicionada->id);

        $this->assertDatabaseMissing('conta_corretoras', [
            'identificador' => 'ID001',
            'usuario_id' => $this->usuario_auth->id
        ]);

        $todos = $this->repository->all();
        $this->assertCount(2, $todos);
    }

    public function test_Delete_Uma_Conta_Corretora_De_Outro_Usuario_Deve_Retornar_Erro()
    {
        $conta_outro_usuario = $this->adicionarContaDeOutroUsuario();

        $this->expectException(ModelNotFoundException::class);

        $this->assertDatabaseHas('conta_corretoras', [
            'identificador' => 'ID109',
            'usuario_id' => $conta_outro_usuario->usuario_id
        ]);

        $todos = $this->repository->all();
        $this->assertCount(4, $todos);

        $this->service->delete($conta_outro_usuario->id);

        $this->fail('Deletou uma conta de outro usuario');
    }

    public function test_GetAllByUser_Esperando_Tres_De_Quatro_Registros_Que_Existem_No_Banco()
    {
        $conta_outro_usuario = $this->adicionarContaDeOutroUsuario();

        $todos = $this->repository->all();
        $this->assertCount(4, $todos);

        $todos = $this->service->getAllByUser();
        $this->assertCount(3, $todos);

        foreach ($todos as $conta) {
            $this->assertNotEquals($conta_outro_usuario->id, $conta->id);
        }
    }

    public function test_GetById_De_Id_Inexistente_Deve_Gerar_Erro_De_ModelNotFound()
    {
        $this->expectException(ModelNotFoundException::class);

        $this->service->getById(15);

        $this->fail('Deveria ter gerado uma exceção de id nao encontrado ao buscar uma conta com id que nao existe');
    }

    public function test_GetById_Deve_Retornar_A_Conta()
    {
        $this->adicionarContaDeOutroUsuario();

        $conta = $this->service->getById($this->conta_adicionada->id);

        $this->assertNotNull($conta);
        $this->assertEquals($conta->id , $this->conta_adicionada->id);
    }

    public function test_GetById_Com_Id_De_Conta_De_Outro_Usuario_Deve_Gerar_Excecao_ModelNotFound()
    {
        $conta_outro_usuario = $this->adicionarContaDeOutroUsuario();

        $this->expectException(ModelNotFoundException::class);

        $this->service->getById($conta_outro_usuario->id);

        $this->fail('Deveria ter gerado uma exceção de id nao encontrado ao buscar uma conta com id que nao existe');
    }

    public function test_GetByIdOrFirst_De_Id_Inexistente_Deve_Retornar_Qualquer_Conta_Do_Usuario_Logado()
    {
        $this->adicionarContaDeOutroUsuario();
        $conta = $this->service->getByIdOrFirst(15);

        $this->assertNotNull($conta);
        $this->assertNotEquals(15, $conta->id);
        $this->assertEquals($this->usuario_auth->id, $conta->usuario_id);
    }

    public function test_GetByIdOrFirst_De_Id_Existente_Deve_Retornar_A_Conta_Com_O_Id_Informado()
    {
        $this->adicionarContaDeOutroUsuario();
        $conta = $this->service->getByIdOrFirst($this->conta_adicionada->id);

        $this->assertNotNull($conta);
        $this->assertEquals($this->conta_adicionada->id, $conta->id);
        $this->assertEquals($this->usuario_auth->id, $conta->usuario_id);
    }

    public function test_GetByIdOrFirst_Com_Id_De_Conta_De_Outro_Usuario_Deve_Retornar_Qualquer_Conta_Do_Usuario_Logado()
    {
        $conta_outro_usuario = $this->adicionarContaDeOutroUsuario();

        $conta = $this->service->getByIdOrFirst($conta_outro_usuario->id);

        $this->assertNotNull($conta);
        $this->assertNotEquals($conta_outro_usuario->id, $conta->id);
        $this->assertEquals($this->usuario_auth->id, $conta->usuario_id);
    }

    public function test_GetByIdOrFirst_Com_Usuario_Logado_Sem_Nenhuma_Conta_Deve_Retornar_Conta_Null()
    {
        $usuario_demonstracao = factory(User::class)->create([
            'name'              => 'testeMockNovo',
            'email'             => 'teste_mock_vazio@mockteste.com.xy',
        ]);

        $this->be($usuario_demonstracao);
        $conta = $this->service->getByIdOrFirst($this->conta_adicionada->id);

        $this->assertNull($conta);
    }

    public function test_getByCodigoOrCreate_Com_Codigo_Que_Nao_Existe_Deve_Criar_E_Retornar_Uma_Nova_Conta()
    {
        $todos = ContaCorretora::all();
        $this->assertCount(3, $todos);

        $novo = $this->service->getByCodigoOrCreate('novo', 'corretora teste', 'USD');

        $this->assertNotNull($novo);
        $this->assertEquals('novo', $novo->identificador);
        $this->assertEquals('Corretora Teste', $novo->corretora->nome);
        $this->assertEquals('USD', $novo->moeda->sigla);

        $this->assertDatabaseHas('conta_corretoras', [
            'identificador' => 'novo',
            'usuario_id' => $this->usuario_auth->id
        ]);

        $todos = ContaCorretora::all();
        $this->assertCount(4, $todos);
    }

    public function test_getByCodigoOrCreate_Com_Codigo_De_Conta_De_Outro_Usuario_Deve_Criar_E_Retornar_Uma_Nova_Conta()
    {
        $conta_outro_usuario = $this->adicionarContaDeOutroUsuario();
        $todos = ContaCorretora::all();
        $this->assertCount(4, $todos);

        $novo = $this->service->getByCodigoOrCreate($conta_outro_usuario->identificador,
                $conta_outro_usuario->corretora->nome, $conta_outro_usuario->moeda->sigla);

        $this->assertNotNull($novo);
        $this->assertEquals($conta_outro_usuario->identificador, $novo->identificador);
        $this->assertEquals($conta_outro_usuario->corretora->nome, $novo->corretora->nome);
        $this->assertEquals($conta_outro_usuario->moeda->sigla, $novo->moeda->sigla);
        $this->assertEquals($this->usuario_auth->id, $novo->usuario_id);
        $this->assertNotEquals($conta_outro_usuario->id, $novo->id);
        $this->assertNotEquals($conta_outro_usuario->usuario_id, $novo->usuario_id);

        $this->assertDatabaseHas('conta_corretoras', [
            'identificador' => $conta_outro_usuario->identificador,
            'usuario_id' => $this->usuario_auth->id
        ]);

        $todos = ContaCorretora::all();
        $this->assertCount(5, $todos);
    }

    public function test_getByCodigoOrCreate_Com_Codigo_Que_Ja_Existe_Deve_Retornar_A_Conta()
    {
        $this->adicionarContaDeOutroUsuario();
        $todos = ContaCorretora::all();
        $this->assertCount(4, $todos);

        $conta = $this->service->getByCodigoOrCreate($this->conta_adicionada->identificador,
                $this->conta_adicionada->corretora->nome, $this->conta_adicionada->moeda->sigla);

        $this->assertNotNull($conta);
        $this->assertEquals($this->usuario_auth->id, $conta->usuario_id);
        $this->assertEquals($this->conta_adicionada->id, $conta->id);

        $todos = ContaCorretora::all();
        $this->assertCount(4, $todos);
    }

    public function test_AtualizarSaldoContaPorTransferencia_Com_Valor_Positivo_Deve_Somar_Nas_Entradas_E_No_Saldo()
    {
        $this->adicionarDezDeValorAContaAdicionada();

        $this->assertEquals(10, $this->conta_adicionada->entradas);
        $this->assertEquals(-10, $this->conta_adicionada->saidas);
        $this->assertEquals(0, $this->conta_adicionada->saldo);

        $valor = 25.05;
        $this->service->atualizarSaldoContaPorTransferencia($this->conta_adicionada, $valor);

        $this->conta_adicionada = $this->service->getById($this->conta_adicionada->id);
        $this->assertEquals(35.05, $this->conta_adicionada->entradas);
        $this->assertEquals(-10, $this->conta_adicionada->saidas);
        $this->assertEquals(25.05, $this->conta_adicionada->saldo);
    }

    public function test_AtualizarSaldoContaPorTransferencia_Com_Valor_Positivo_Com_Virgula_Deve_Somar_Nas_Entradas_E_No_Saldo()
    {
        $this->adicionarDezDeValorAContaAdicionada();

        $this->assertEquals(10, $this->conta_adicionada->entradas);
        $this->assertEquals(-10, $this->conta_adicionada->saidas);
        $this->assertEquals(0, $this->conta_adicionada->saldo);

        $valor = '25,05';
        $this->service->atualizarSaldoContaPorTransferencia($this->conta_adicionada, $valor);

        $this->conta_adicionada = $this->service->getById($this->conta_adicionada->id);
        $this->assertEquals(35.05, $this->conta_adicionada->entradas);
        $this->assertEquals(-10, $this->conta_adicionada->saidas);
        $this->assertEquals(25.05, $this->conta_adicionada->saldo);
    }

    public function test_AtualizarSaldoContaPorTransferencia_Com_Valor_Negativo_Deve_Somar_Nas_Saidas_E_Subtrair_No_Saldo()
    {
        $this->adicionarDezDeValorAContaAdicionada();

        $this->assertEquals( 10, $this->conta_adicionada->entradas);
        $this->assertEquals(-10, $this->conta_adicionada->saidas);
        $this->assertEquals(  0, $this->conta_adicionada->saldo);

        $valor = -25.05;
        $this->service->atualizarSaldoContaPorTransferencia($this->conta_adicionada, $valor);

        $this->conta_adicionada = $this->service->getById($this->conta_adicionada->id);
        $this->assertEquals(    10, $this->conta_adicionada->entradas);
        $this->assertEquals(-35.05, $this->conta_adicionada->saidas);
        $this->assertEquals(-25.05, $this->conta_adicionada->saldo);
    }

    public function test_AtualizarSaldoContaPorTransferencia_Com_Valor_Negativo_Com_Virgula_Deve_Somar_Nas_Saidas_E_Subtrair_No_Saldo()
    {
        $this->adicionarDezDeValorAContaAdicionada();

        $this->assertEquals( 10, $this->conta_adicionada->entradas);
        $this->assertEquals(-10, $this->conta_adicionada->saidas);
        $this->assertEquals(  0, $this->conta_adicionada->saldo);

        $valor = '-25,05';
        $this->service->atualizarSaldoContaPorTransferencia($this->conta_adicionada, $valor);

        $this->conta_adicionada = $this->service->getById($this->conta_adicionada->id);
        $this->assertEquals(    10, $this->conta_adicionada->entradas);
        $this->assertEquals(-35.05, $this->conta_adicionada->saidas);
        $this->assertEquals(-25.05, $this->conta_adicionada->saldo);
    }

    public function test_AtualizarSaldoContaPorEstornoTransferencia_Com_Valor_Positivo_Deve_Subtrair_Nas_Entradas_E_No_Saldo()
    {
        $this->adicionarDezDeValorAContaAdicionada();

        $this->assertEquals(10, $this->conta_adicionada->entradas);
        $this->assertEquals(-10, $this->conta_adicionada->saidas);
        $this->assertEquals(0, $this->conta_adicionada->saldo);

        $valor = 5.05;
        $this->service->atualizarSaldoContaPorEstornoDeTransferencia($this->conta_adicionada, $valor);

        $this->conta_adicionada = $this->service->getById($this->conta_adicionada->id);
        $this->assertEquals(4.95, $this->conta_adicionada->entradas);
        $this->assertEquals(-10, $this->conta_adicionada->saidas);
        $this->assertEquals(-5.05, $this->conta_adicionada->saldo);
    }

    public function test_AtualizarSaldoContaPorTransferencia_Com_Valor_Positivo_Com_Virgula_Deve_Subtrair_Nas_Entradas_E_No_Saldo()
    {
        $this->adicionarDezDeValorAContaAdicionada();

        $this->assertEquals(10, $this->conta_adicionada->entradas);
        $this->assertEquals(-10, $this->conta_adicionada->saidas);
        $this->assertEquals(0, $this->conta_adicionada->saldo);

        $valor = '5,05';
        $this->service->atualizarSaldoContaPorEstornoDeTransferencia($this->conta_adicionada, $valor);

        $this->conta_adicionada = $this->service->getById($this->conta_adicionada->id);
        $this->assertEquals(4.95, $this->conta_adicionada->entradas);
        $this->assertEquals(-10, $this->conta_adicionada->saidas);
        $this->assertEquals(-5.05, $this->conta_adicionada->saldo);
    }

    public function test_AtualizarSaldoContaPorTransferencia_Com_Valor_Negativo_Deve_Subtrair_Nas_Saidas_E_Somar_No_Saldo()
    {
        $this->adicionarDezDeValorAContaAdicionada();

        $this->assertEquals( 10, $this->conta_adicionada->entradas);
        $this->assertEquals(-10, $this->conta_adicionada->saidas);
        $this->assertEquals(  0, $this->conta_adicionada->saldo);

        $valor = -5.05;
        $this->service->atualizarSaldoContaPorEstornoDeTransferencia($this->conta_adicionada, $valor);

        $this->conta_adicionada = $this->service->getById($this->conta_adicionada->id);
        $this->assertEquals(   10, $this->conta_adicionada->entradas);
        $this->assertEquals(-4.95, $this->conta_adicionada->saidas);
        $this->assertEquals( 5.05, $this->conta_adicionada->saldo);
    }

    public function test_AtualizarSaldoContaPorTransferencia_Com_Valor_Negativo_Com_Virgula_Deve_Subtrair_Nas_Saidas_E_Somar_No_Saldo()
    {
        $this->adicionarDezDeValorAContaAdicionada();

        $this->assertEquals( 10, $this->conta_adicionada->entradas);
        $this->assertEquals(-10, $this->conta_adicionada->saidas);
        $this->assertEquals(  0, $this->conta_adicionada->saldo);

        $valor = '-5,05';
        $this->service->atualizarSaldoContaPorEstornoDeTransferencia($this->conta_adicionada, $valor);

        $this->conta_adicionada = $this->service->getById($this->conta_adicionada->id);
        $this->assertEquals(   10, $this->conta_adicionada->entradas);
        $this->assertEquals(-4.95, $this->conta_adicionada->saidas);
        $this->assertEquals( 5.05, $this->conta_adicionada->saldo);
    }

    public function test_atualizarSaldoContaPorOperacoes_Adicionando_Saldo_E_Operacoes_Positivos_Deve_Somar_Aos_Valores()
    {
        $this->adicionarDezDeValorAContaAdicionada();
        $this->assertEquals( 10, $this->conta_adicionada->operacoes_abertas);
        $this->assertEquals( 10, $this->conta_adicionada->operacoes_fechadas);
        $this->assertEquals( 10, $this->conta_adicionada->entradas);
        $this->assertEquals(-10, $this->conta_adicionada->saidas);
        $this->assertEquals(  0, $this->conta_adicionada->saldo);

        $valorOperacoes     = 5.05;
        $operacoesAbertas   = 3;
        $operacoesFechadas  = 2;

        $this->service->atualizarSaldoContaPorOperacoes(
            $this->conta_adicionada, $valorOperacoes, $operacoesAbertas, $operacoesFechadas
        );

        $conta = $this->service->getById($this->conta_adicionada->id);

        $this->assertNotNull($conta);
        $this->assertEquals(  13, $conta->operacoes_abertas);
        $this->assertEquals(  12, $conta->operacoes_fechadas);
        $this->assertEquals(  10, $conta->entradas);
        $this->assertEquals( -10, $conta->saidas);
        $this->assertEquals(5.05, $conta->saldo);
    }

    public function test_atualizarSaldoContaPorOperacoes_Adicionando_Saldo_E_Operacoes_Negativos_Deve_Subtrair_Dos_Valores()
    {
        $this->adicionarDezDeValorAContaAdicionada();
        $this->assertEquals( 10, $this->conta_adicionada->operacoes_abertas);
        $this->assertEquals( 10, $this->conta_adicionada->operacoes_fechadas);
        $this->assertEquals( 10, $this->conta_adicionada->entradas);
        $this->assertEquals(-10, $this->conta_adicionada->saidas);
        $this->assertEquals(  0, $this->conta_adicionada->saldo);

        $valorOperacoes     = -5.05;
        $operacoesAbertas   = -3;
        $operacoesFechadas  = -2;

        $this->service->atualizarSaldoContaPorOperacoes(
            $this->conta_adicionada, $valorOperacoes, $operacoesAbertas, $operacoesFechadas
        );

        $conta = $this->service->getById($this->conta_adicionada->id);

        $this->assertNotNull($conta);
        $this->assertEquals(    7, $conta->operacoes_abertas);
        $this->assertEquals(    8, $conta->operacoes_fechadas);
        $this->assertEquals(   10, $conta->entradas);
        $this->assertEquals(  -10, $conta->saidas);
        $this->assertEquals(-5.05, $conta->saldo);
    }

    public function test_atualizarSaldoContaPorOperacoes_Adicionando_Saldo_E_Operacoes_Positivos_Com_Virgula_Deve_Somar_Aos_Valores()
    {
        $this->adicionarDezDeValorAContaAdicionada();
        $this->assertEquals( 10, $this->conta_adicionada->operacoes_abertas);
        $this->assertEquals( 10, $this->conta_adicionada->operacoes_fechadas);
        $this->assertEquals( 10, $this->conta_adicionada->entradas);
        $this->assertEquals(-10, $this->conta_adicionada->saidas);
        $this->assertEquals(  0, $this->conta_adicionada->saldo);

        $valorOperacoes     = '5.050,05';
        $operacoesAbertas   = 3.2;
        $operacoesFechadas  = '2,3';

        $this->service->atualizarSaldoContaPorOperacoes(
            $this->conta_adicionada, $valorOperacoes, $operacoesAbertas, $operacoesFechadas
        );

        $conta = $this->service->getById($this->conta_adicionada->id);

        $this->assertNotNull($conta);
        $this->assertEquals(     13, $conta->operacoes_abertas);
        $this->assertEquals(     12, $conta->operacoes_fechadas);
        $this->assertEquals(     10, $conta->entradas);
        $this->assertEquals(    -10, $conta->saidas);
        $this->assertEquals(5050.05, $conta->saldo);
    }

    public function test_atualizarSaldoContaPorOperacoes_Adicionando_Saldo_E_Operacoes_Negativos_Com_Virgula_Deve_Subtrair_Dos_Valores()
    {
        $this->adicionarDezDeValorAContaAdicionada();
        $this->assertEquals( 10, $this->conta_adicionada->operacoes_abertas);
        $this->assertEquals( 10, $this->conta_adicionada->operacoes_fechadas);
        $this->assertEquals( 10, $this->conta_adicionada->entradas);
        $this->assertEquals(-10, $this->conta_adicionada->saidas);
        $this->assertEquals(  0, $this->conta_adicionada->saldo);

        $valorOperacoes     = '-5.050,05';
        $operacoesAbertas   = -3.2;
        $operacoesFechadas  = '-2,3';

        $this->service->atualizarSaldoContaPorOperacoes(
            $this->conta_adicionada, $valorOperacoes, $operacoesAbertas, $operacoesFechadas
        );

        $conta = $this->service->getById($this->conta_adicionada->id);

        $this->assertNotNull($conta);
        $this->assertEquals(       7, $conta->operacoes_abertas);
        $this->assertEquals(       8, $conta->operacoes_fechadas);
        $this->assertEquals(      10, $conta->entradas);
        $this->assertEquals(     -10, $conta->saidas);
        $this->assertEquals(-5050.05, $conta->saldo);
    }

    public function test_selectBoxList_Sem_Filtro()
    {
        $this->adicionarContaDeOutroUsuario();

        $valores = $this->service->selectBoxList();

        $this->assertNotNull($valores);
        $this->assertCount(3, $valores);
        $this->assertArrayHasKey($this->conta_adicionada->id, $valores);
    }

    public function test_selectBoxList_Com_Filtro()
    {
        $this->adicionarContaDeOutroUsuario();

        $valores = $this->service->selectBoxList($this->conta_adicionada->id);

        $this->assertNotNull($valores);
        $this->assertCount(2, $valores);
        $this->assertArrayNotHasKey($this->conta_adicionada->id, $valores);
    }


    /* Methodos Auxiliares */
    public function loginComUsuarioFake()
    {
        $this->usuario_auth = factory(User::class)->create();

        $this->be($this->usuario_auth);
    }

    public function criarContaCorretoraDemonstracao($identificador = 'ID000')
    {
        $corretora = Corretora::first();
        if(!$corretora)
            $corretora = factory(Corretora::class)->create();
        $moeda = Moeda::first();
        if(!$moeda)
            $moeda = factory(Moeda::class)->create();

        return [
            'identificador' => $identificador,
            'corretora_id' => $corretora->id,
            'moeda_id' => $moeda->id
        ];
    }

    private function popularBaseComTresContasCorretoras()
    {
        factory(ContaCorretora::class)->create(['identificador' => 'ID008']);
        $nova_conta = factory(ContaCorretora::class)->create();
        factory(ContaCorretora::class)->create(['identificador' => 'ID009']);

        return $nova_conta;
    }

    private function adicionarContaDeOutroUsuario()
    {
        return factory(ContaCorretora::class)->create([
            'identificador' => 'ID109',
            'usuario_id'    => factory(User::class)->create([
                                'name'              => 'testeMockSegundo',
                                'email'             => 'teste_mock_segundo@mockteste.com.xy',
                            ])->id
            ]);
    }

    private function adicionarDezDeValorAContaAdicionada()
    {
        $this->conta_adicionada->update([
            'operacoes_abertas' => 10,
            'operacoes_fechadas'=> 10,
            'saldo'             => 0,
            'entradas'          => 10,
            'saidas'            => -10
        ]);
    }
}
