<?php

namespace Tests\Unit\Models;

use App\Models\ContaCorretora;
use App\Models\Corretora;
use App\Models\Moeda;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ContaCorretoraTest extends TestCase
{
    use DatabaseTransactions;

    private $usuario_falso;
    private $moeda_falsa;
    private $corretora_falsa;
    private $conta_falsa;

    protected function setUp():void
    {
        parent::setUp();
        $this->usuario_falso    = factory(User::class)->create();
        $this->moeda_falsa      = factory(Moeda::class)->create();
        $this->corretora_falsa  = factory(Corretora::class)->create();
        $this->conta_falsa      = factory(ContaCorretora::class)->create([
                                    'entradas'      => 2.00,
                                    'saidas'        => 3.01,
                                    'saldo'         => 1200.98,
                                ]);
    }
    /**
     */
    public function testAdicionarConta()
    {
        //fwrite(STDERR, print_r($conta->saidas, TRUE).' / ');

        $this->assertNotNull($this->conta_falsa);
        $this->assertEquals($this->usuario_falso->id, $this->conta_falsa->usuario_id);
        $this->assertEquals($this->moeda_falsa->id, $this->conta_falsa->moeda_id);
        $this->assertEquals($this->corretora_falsa->id, $this->conta_falsa->corretora_id);
        $this->assertDatabaseHas('conta_corretoras', [
            'id' => $this->conta_falsa->id,
            'usuario_id' => $this->conta_falsa->usuario_id,
            'moeda_id' => $this->conta_falsa->moeda_id,
            'corretora_id' => $this->conta_falsa->corretora_id
        ]);
    }

    public function testContaVerificaValoresSalvos()
    {
        $this->assertNotNull($this->conta_falsa);
        $this->assertEquals(1200.98, $this->conta_falsa->saldo);
        $this->assertEquals(3.01, $this->conta_falsa->saidas);
        $this->assertEquals(2.00, $this->conta_falsa->entradas);
    }

    public function testContaVerificaValoresFormatados()
    {
        $this->assertNotNull($this->conta_falsa);
        $this->assertEquals('US$ 1.200,98', $this->conta_falsa->saldo_formatado);
        $this->assertEquals('US$ 3,01', $this->conta_falsa->saidas_formatado);
        $this->assertEquals('US$ 2,00', $this->conta_falsa->entradas_formatado);
    }

    public function testContaSetarValoresComVirgula()
    {
        $this->conta_falsa->saldo = '1.200,98';
        $this->conta_falsa->saidas = '3,01';
        $this->conta_falsa->entradas = '2,00';

        $this->assertEquals(1200.98, $this->conta_falsa->saldo);
        $this->assertEquals(3.01, $this->conta_falsa->saidas);
        $this->assertEquals(2.00, $this->conta_falsa->entradas);

        $this->assertEquals('US$ 1.200,98', $this->conta_falsa->saldo_formatado);
        $this->assertEquals('US$ 3,01', $this->conta_falsa->saidas_formatado);
        $this->assertEquals('US$ 2,00', $this->conta_falsa->entradas_formatado);
    }

}
