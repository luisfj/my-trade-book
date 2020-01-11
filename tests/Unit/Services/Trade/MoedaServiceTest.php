<?php

namespace Tests\Unit\Services\Trade;

use App\Models\Moeda;
use App\Services\Trade\MoedaService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class MoedaServiceTest extends TestCase
{
    use DatabaseTransactions;

    private $moeda_service;

    protected function setUp():void
    {
        parent::setUp();
        $this->moeda_service = new MoedaService(new Moeda());
    }

    public function testBuscarPorSiglaMoedaQueNaoExisteEmBaseLimpaEntaoDeveCriala()
    {
        $this->assertDatabaseMissing('moedas', [
            'sigla' => 'mock_moeda'
        ]);

        $moeda = $this->moeda_service->getBySiglaOrCreate('mock_moeda');

        $this->assertNotNull($moeda);

        $this->assertDatabaseHas('moedas', [
            'id' => $moeda->id,
            'sigla' => 'mock_moeda'
        ]);
    }

    public function testBuscarPorSiglaMoedaQueNaoExisteEmBasePopuladaEntaoDeveCriala()
    {
        factory(Moeda::class)->create();
        $todas_moedas = Moeda::get();
        $this->assertCount(1, $todas_moedas, 'Tabela moeda não contem o numero de registros adicionado');

        $this->assertDatabaseMissing('moedas', [
            'sigla' => 'mock_moeda'
        ]);

        $moeda = $this->moeda_service->getBySiglaOrCreate('mock_moeda');

        $this->assertNotNull($moeda);

        $this->assertDatabaseHas('moedas', [
            'id' => $moeda->id,
            'sigla' => 'mock_moeda'
        ]);

        $todas_moedas = Moeda::get();
        $this->assertCount(2, $todas_moedas, 'Tabela moeda não contem o numero de registros adicionado');
    }

    public function testBuscarPorSiglaMoedaQueJaExisteDeveRetornalaENaoCriarNova()
    {
        $todas_moedas = Moeda::get();

        $this->assertDatabaseMissing('moedas', [
            'sigla' => 'USD',
            'nome'  => 'DOLAR'
        ]);
        $this->assertCount(0, $todas_moedas, 'Tabela moeda não esta vazia');

        factory(Moeda::class)->create(['sigla' => 'mock_teste']);
        $nova_moeda = factory(Moeda::class)->create();
        factory(Moeda::class)->create(['sigla' => 'ultimo']);

        $moeda = $this->moeda_service->getBySiglaOrCreate('USD');

        $this->assertNotNull($moeda);
        $this->assertEquals($nova_moeda->id, $moeda->id);

        $this->assertDatabaseHas('moedas', [
            'id' => $moeda->id,
            'sigla' => 'USD'
        ]);
        $todas_moedas = Moeda::get();
        $this->assertCount(3, $todas_moedas, 'Tabela moeda não contem o numero de registros adicionado');
    }
}
