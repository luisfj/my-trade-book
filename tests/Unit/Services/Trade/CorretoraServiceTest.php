<?php

namespace Tests\Unit\Services\Trade;

use App\Models\Corretora;
use App\Models\Moeda;
use App\Services\Trade\CorretoraService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CorretoraServiceTest extends TestCase
{
    use DatabaseTransactions;

    private $corretora_service;
    private $moeda;

    protected function setUp():void
    {
        parent::setUp();
        $this->corretora_service = new CorretoraService(new Corretora());
        $this->moeda = factory(Moeda::class)->create();
        $this->popularBaseComTresCorretoras();
    }

    public function testBuscarPorNomeCorretoraQueNaoExisteEntaoDeveCriala()
    {
        $todos = Corretora::all();
        $this->assertCount(3, $todos);

        $corretora = $this->corretora_service->getByNomeOrCreate('mock_corretora', $this->moeda);

        $this->assertNotNull($corretora);

        $this->assertDatabaseHas('corretoras', [
            'id' => $corretora->id,
            'nome' => 'mock_corretora'
        ]);

        $todos = Corretora::all();
        $this->assertCount(4, $todos);
    }

    public function testBuscarPorNomeCorretoraQueJaExisteDeveRetornalaENaoCriarNova()
    {
        $todas = Corretora::all();

        $this->assertCount(3, $todas, 'Tabela corretora não contem os registros corretos');

        $corretora = $this->corretora_service->getByNomeOrCreate('Corretora Teste', $this->moeda);

        $this->assertNotNull($corretora);

        $this->assertDatabaseHas('corretoras', [
            'id' => $corretora->id,
            'nome' => 'Corretora Teste'
        ]);

        $todas = Corretora::all();
        $this->assertCount(3, $todas, 'Tabela corretora não contem os registros corretos');
    }

    /* Funções auxiliares */
    private function popularBaseComTresCorretoras()
    {
        factory(Corretora::class)->create(['nome' => 'mock teste']);
        $nova_corretora = factory(Corretora::class)->create();
        factory(Corretora::class)->create(['nome' => 'ultimo nome']);

        return $nova_corretora;
    }
}
