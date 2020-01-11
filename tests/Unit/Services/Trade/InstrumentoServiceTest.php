<?php

namespace Tests\Unit\Services\Trade;

use App\Models\Instrumento;
use App\Services\Trade\InstrumentoService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class InstrumentoServiceTest extends TestCase
{
    use DatabaseTransactions;

    private $instrumento_service;

    protected function setUp():void
    {
        parent::setUp();
        $this->instrumento_service = new InstrumentoService(new Instrumento());
    }

    public function testBuscarPorSiglaInstrumentoQueNaoExisteEmBaseLimpaEntaoDeveCriala()
    {
        $this->assertDatabaseMissing('instrumentos', [
            'sigla' => 'mock_instrumento'
        ]);

        $instrumento = $this->instrumento_service->getBySiglaOrCreate('mock_instrumento');

        $this->assertNotNull($instrumento);

        $this->assertDatabaseHas('instrumentos', [
            'id' => $instrumento->id,
            'sigla' => 'mock_instrumento'
        ]);
    }

    public function testBuscarPorSiglaMoedaQueNaoExisteEmBasePopuladaEntaoDeveCriala()
    {
        factory(Instrumento::class)->create();
        $todos_instrumentos = Instrumento::get();
        $this->assertCount(1, $todos_instrumentos, 'Tabela instrumento não contem o numero de registros adicionado');

        $this->assertDatabaseMissing('instrumentos', [
            'sigla' => 'mock_instrumento'
        ]);

        $instrumento = $this->instrumento_service->getBySiglaOrCreate('mock_instrumento');

        $this->assertNotNull($instrumento);

        $this->assertDatabaseHas('instrumentos', [
            'id' => $instrumento->id,
            'sigla' => 'mock_instrumento'
        ]);

        $todos_instrumentos = Instrumento::get();
        $this->assertCount(2, $todos_instrumentos, 'Tabela instrumento não contem o numero de registros adicionado');
    }

    public function testBuscarPorSiglaMoedaQueJaExisteDeveRetornalaENaoCriarNova()
    {
        $todos_instrumentos = Instrumento::get();

        $this->assertDatabaseMissing('instrumentos', [
            'sigla' => 'xauusd',
            'nome'  => 'Ouro'
        ]);
        $this->assertCount(0, $todos_instrumentos, 'Tabela instrumento não esta vazia');

        factory(Instrumento::class)->create(['sigla' => 'mock_teste']);
        $novo_instrumento = factory(Instrumento::class)->create();
        factory(Instrumento::class)->create(['sigla' => 'ultimo']);

        $instrumento = $this->instrumento_service->getBySiglaOrCreate('xauusd');

        $this->assertNotNull($instrumento);
        $this->assertEquals($novo_instrumento->id, $instrumento->id);

        $this->assertDatabaseHas('instrumentos', [
            'id' => $instrumento->id,
            'sigla' => 'xauusd'
        ]);
        $todos_instrumentos = Instrumento::get();
        $this->assertCount(3, $todos_instrumentos, 'Tabela instrumento não contem o numero de registros adicionado');
    }

    public function testBuscarPorIdUmIdInexistenteDeveGerarExcecaoModeNotFound()
    {
        $this->popularBaseComTresInstrumentos();
        $this->expectException(ModelNotFoundException::class);
        $instrumento = $this->instrumento_service->getById(8);

        $this->fail('Não gerou exceção ao buscar um id inexistente');
    }

    public function testBuscarPorIdEmBasePopulada()
    {
        $segundo_instrumento = $this->popularBaseComTresInstrumentos();
        $instrumento = $this->instrumento_service->getById($segundo_instrumento->id);

        $this->assertNotNull($instrumento);
        $this->assertDatabaseHas('instrumentos', [
            'id' => $instrumento->id,
            'sigla' => 'xauusd'
        ]);
    }

    public function testBuscarPorSiglaEmBaseVazia()
    {
        $instrumento = $this->instrumento_service->getBySigla('xauusd');
        $this->assertNull($instrumento);
    }

    public function testBuscarPorSiglaEmBasePopulada()
    {
        $this->popularBaseComTresInstrumentos();
        $instrumento = $this->instrumento_service->getBySigla('xauusd');

        $this->assertNotNull($instrumento);
        $this->assertDatabaseHas('instrumentos', [
            'id' => $instrumento->id,
            'sigla' => 'xauusd'
        ]);
    }

    public function testBuscarPorNomeEmBaseVazia()
    {
        $instrumento = $this->instrumento_service->getByNome('Ouro');
        $this->assertNull($instrumento);
    }

    public function testBuscarPorNomeEmBasePopulada()
    {
        $this->popularBaseComTresInstrumentos();
        $instrumento = $this->instrumento_service->getByNome('Ouro');

        $this->assertNotNull($instrumento);
        $this->assertDatabaseHas('instrumentos', [
            'id' => $instrumento->id,
            'nome' => 'Ouro'
        ]);
    }

    public function testBuscarTodosOsInstrumentos()
    {
        $todos = $this->instrumento_service->getAll();
        $this->assertEmpty($todos);

        $this->popularBaseComTresInstrumentos();

        $todos = $this->instrumento_service->getAll();
        $this->assertCount(3, $todos);
    }

    public function testCreateDeNovoInstrumento()
    {
        $todos = $this->instrumento_service->getAll();
        $this->assertEmpty($todos);

        $novo_json = $this->construirJsonInstrumento();

        $novo = $this->instrumento_service->create($novo_json);

        $this->assertNotNull($novo);
        $this->assertDatabaseHas('instrumentos', [
            'id'    => $novo->id,
            'sigla' => 'json',
            'nome'  => 'json nome'
        ]);
    }

    public function testCreateDeInstrumentoQueJaExisteDeveRetornarOExistente()
    {
        $ouro_xauusd = $this->popularBaseComTresInstrumentos();

        $novo_json = $this->construirJsonInstrumento('xauusd', 'Ouro');

        $novo = $this->instrumento_service->create($novo_json);

        $this->assertNotNull($novo);
        $this->assertNotNull($ouro_xauusd);
        $this->assertEquals($ouro_xauusd->id, $novo->id);

        $todos = $this->instrumento_service->getAll();
        $this->assertCount(3, $todos);
    }

    public function testCreateDeInstrumentoQueNaoExisteEmBasePopulada()
    {
        $this->popularBaseComTresInstrumentos();

        $novo_json = $this->construirJsonInstrumento();

        $novo = $this->instrumento_service->create($novo_json);

        $this->assertNotNull($novo);

        $todos = $this->instrumento_service->getAll();
        $this->assertCount(4, $todos);

        $this->assertDatabaseHas('instrumentos', [
            'id'    => $novo->id,
            'sigla' => 'json',
            'nome'  => 'json nome'
        ]);
    }

    public function testUpdateDeIntrumentoQueNaoExisteDeveGerarExcecaoModelNotFound()
    {
        $this->popularBaseComTresInstrumentos();

        $this->expectException(ModelNotFoundException::class);

        $novo_json = $this->construirJsonInstrumento();
        $this->instrumento_service->update($novo_json, 5);

        $this->fail('Não gerou a exceção ao atualizar instrumento inexistente');
    }

    public function testUpdateDeIntrumento()
    {
        $instrumento = $this->popularBaseComTresInstrumentos();

        $this->assertNotNull($instrumento);

        $novo_json = $this->construirJsonInstrumento();
        $this->instrumento_service->update($novo_json, $instrumento->id);

        $this->assertDatabaseMissing('instrumentos', [
            'id'    => $instrumento->id,
            'sigla' => $instrumento->sigla,
            'nome'  => $instrumento->nome
        ]);

        $this->assertDatabaseHas('instrumentos', [
            'id'    => $instrumento->id,
            'sigla' => 'json',
            'nome'  => 'json nome'
        ]);
    }

    public function testDeleteDeIntrumentoQueNaoExisteDeveGerarExcecaoModelNotFound()
    {
        $this->popularBaseComTresInstrumentos();

        $this->expectException(ModelNotFoundException::class);

        $this->instrumento_service->delete(5);

        $this->fail('Não gerou a exceção ao deletar instrumento inexistente');
    }

    public function testDeleteDeIntrumento()
    {
        $instrumento = $this->popularBaseComTresInstrumentos();

        $this->assertNotNull($instrumento);

        $this->instrumento_service->delete($instrumento->id);

        $this->assertDatabaseMissing('instrumentos', [
            'id'    => $instrumento->id,
            'sigla' => $instrumento->sigla,
            'nome'  => $instrumento->nome
        ]);

        $todos = $this->instrumento_service->getAll();
        $this->assertCount(2, $todos);
    }

    /* Funções auxiliares */
    private function popularBaseComTresInstrumentos()
    {
        factory(Instrumento::class)->create(['sigla' => 'mock_teste', 'nome' => 'mock teste']);
        $novo_instrumento = factory(Instrumento::class)->create();
        factory(Instrumento::class)->create(['sigla' => 'ultimo', 'nome' => 'ultimo nome']);

        return $novo_instrumento;
    }

    private function construirJsonInstrumento($sigla = 'json', $nome = 'json nome' ){
        return [
            'sigla' => $sigla,
            'nome'  => $nome
        ];
    }
}
