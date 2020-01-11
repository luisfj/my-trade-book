<?php

namespace Tests\Unit\Models;

use App\Models\ContaCorretora;
use App\Models\Corretora;
use App\Models\Instrumento;
use App\Models\Moeda;
use App\Models\Operacoes;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OperacoesTest extends TestCase
{
    use DatabaseTransactions;

    private $usuario_falso;
    private $moeda_falsa;
    private $corretora_falsa;
    private $operacao_falsa;

    protected function setUp():void
    {
        parent::setUp();
        $this->usuario_falso    = factory(User::class)->create();
        $this->moeda_falsa      = factory(Moeda::class)->create();
        $this->conta_falsa      = factory(ContaCorretora::class)->create();
        $this->operacao_falsa  = factory(Operacoes::class)->create([
            'account'       => '1234',
            'corretoranome' => 'corretoraDemo',
            'ticket' => 'tick123',
            'precoentrada'   => 50000.12345,
            'precosaida'     => 0.00159,
            'lotes'          => 1.03,
            'comissao'       => 100,
            'impostos'       => 20.1,
            'swap'           => 3000.81,
            'resultadobruto' => 1000000.05,
            'resultado'      => 0.30,
        ]);
    }

    public function testAdicionarUmaOperacaoNoBanco()
    {
        $this->assertNotNull($this->operacao_falsa);
        $this->assertNotNull($this->operacao_falsa->instrumento_id);
        $this->assertEquals($this->usuario_falso->id, $this->operacao_falsa->usuario_id);
        $this->assertEquals($this->moeda_falsa->id, $this->operacao_falsa->moeda_id);
        $this->assertEquals($this->conta_falsa->id, $this->operacao_falsa->conta_corretora_id);
        $this->assertDatabaseHas('operacoes', [
            'account' => $this->operacao_falsa->account,
            'corretoranome' => $this->operacao_falsa->corretoranome,
            'ticket' => $this->operacao_falsa->ticket,
            'instrumento_id' => $this->operacao_falsa->instrumento_id,
            'usuario_id' => $this->operacao_falsa->usuario_id,
            'moeda_id' => $this->operacao_falsa->moeda_id,
            'conta_corretora_id' => $this->operacao_falsa->conta_corretora_id,
        ]);
    }

    public function testContaVerificaValoresSalvos()
    {

        $this->assertNotNull($this->operacao_falsa);
        $this->assertEquals(50000.12345, $this->operacao_falsa->precoentrada);
        $this->assertEquals(    0.00159, $this->operacao_falsa->precosaida);
        $this->assertEquals(       1.03, $this->operacao_falsa->lotes);
        $this->assertEquals(     100.00, $this->operacao_falsa->comissao);
        $this->assertEquals(      20.10, $this->operacao_falsa->impostos);
        $this->assertEquals(    3000.81, $this->operacao_falsa->swap);
        $this->assertEquals( 1000000.05, $this->operacao_falsa->resultadobruto);
        $this->assertEquals(       0.30, $this->operacao_falsa->resultado);
    }

    public function testContaVerificaValoresFormatados()
    {
        $this->assertNotNull($this->operacao_falsa);
        $this->assertEquals(  '50000,12345', $this->operacao_falsa->precoentrada_formatado);
        $this->assertEquals(      '0,00159', $this->operacao_falsa->precosaida_formatado);
        $this->assertEquals(         '1,03', $this->operacao_falsa->lotes_formatado);
        $this->assertEquals(       '100,00', $this->operacao_falsa->comissao_formatado);
        $this->assertEquals(        '20,10', $this->operacao_falsa->impostos_formatado);
        $this->assertEquals(     '3.000,81', $this->operacao_falsa->swap_formatado);
        $this->assertEquals( '1.000.000,05', $this->operacao_falsa->resultadobruto_formatado);
        $this->assertEquals(         '0,30', $this->operacao_falsa->resultado_formatado);
    }

    public function testContaSetarValoresComVirgula()
    {
        $this->operacao_falsa->precoentrada = '150085,1850';
        $this->operacao_falsa->precosaida = '0,12345';
        $this->operacao_falsa->lotes = '10,99';
        $this->operacao_falsa->comissao = '1.200,98';
        $this->operacao_falsa->impostos = '0,5';
        $this->operacao_falsa->swap = '0,01';
        $this->operacao_falsa->resultadobruto = '1500000,30';
        $this->operacao_falsa->resultado = '500';

        $this->assertEquals(150085.185, $this->operacao_falsa->precoentrada);
        $this->assertEquals(0.12345,    $this->operacao_falsa->precosaida);
        $this->assertEquals(10.99,    $this->operacao_falsa->lotes);
        $this->assertEquals(1200.98, $this->operacao_falsa->comissao);
        $this->assertEquals(0.5,    $this->operacao_falsa->impostos);
        $this->assertEquals(0.01,    $this->operacao_falsa->swap);
        $this->assertEquals(1500000.30, $this->operacao_falsa->resultadobruto);
        $this->assertEquals(500,    $this->operacao_falsa->resultado);

        $this->assertEquals( '150085,1850', $this->operacao_falsa->precoentrada_formatado);
        $this->assertEquals(     '0,12345', $this->operacao_falsa->precosaida_formatado);
        $this->assertEquals(       '10,99', $this->operacao_falsa->lotes_formatado);
        $this->assertEquals(    '1.200,98', $this->operacao_falsa->comissao_formatado);
        $this->assertEquals(        '0,50', $this->operacao_falsa->impostos_formatado);
        $this->assertEquals(        '0,01', $this->operacao_falsa->swap_formatado);
        $this->assertEquals('1.500.000,30', $this->operacao_falsa->resultadobruto_formatado);
        $this->assertEquals(      '500,00', $this->operacao_falsa->resultado_formatado);
    }
}
