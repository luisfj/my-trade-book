<?php

namespace Tests\Unit\Models;

use App\Models\ContaCorretora;
use App\Models\Corretora;
use App\Models\DepositoEmConta;
use App\Models\Moeda;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class DepositoEmContaTest extends TestCase
{
    use DatabaseTransactions;

    private $conta_falsa;
    private $transferencia_falsa;

    protected function setUp():void
    {
        parent::setUp();

        $this->conta_falsa      = factory(ContaCorretora::class)->create();
        $this->transferencia_falsa = factory(DepositoEmConta::class)->create([
                                                'ticket' => '123456789',
                                                'codigo_transacao' => 'COD_TRANS_123',
                                                'valor' => 20000.00,
                                            ]);


    }

    public function testAlterarContraparteEAtualizarNoBanco(){
        $contraparte_falsa = factory(ContaCorretora::class)->create();
        $this->transferencia_falsa->update([
            'contraparte_id' => $contraparte_falsa->id
        ]);

        $this->assertNotNull($contraparte_falsa);
        $this->assertEquals($contraparte_falsa->id, $this->transferencia_falsa->contraparte_id);

        $this->assertDatabaseHas('deposito_em_contas', [
            'id' => $this->transferencia_falsa->id,
            'conta_id' => $this->transferencia_falsa->conta_id,
            'valor' => 20000.00,
            'ticket' => $this->transferencia_falsa->ticket,
            'codigo_transacao' => $this->transferencia_falsa->codigo_transacao,
            'contraparte_id' => $contraparte_falsa->id,
        ]);
    }

    public function testTransferenciaAdicionada(){
        $this->assertNotNull($this->transferencia_falsa);
        $this->assertEquals($this->conta_falsa->id, $this->transferencia_falsa->conta_id);

        $this->assertDatabaseHas('deposito_em_contas', [
            'id' => $this->transferencia_falsa->id,
            'conta_id' => $this->transferencia_falsa->conta_id,
            'valor' => 20000.00,
            'ticket' => $this->transferencia_falsa->ticket,
            'codigo_transacao' => $this->transferencia_falsa->codigo_transacao,
        ]);
    }

    public function testTransferenciaVerificaValorSalvo()
    {
        $this->assertNotNull($this->transferencia_falsa);
        $this->assertEquals(20000.00, $this->transferencia_falsa->valor);
    }

    public function testTransferenciaVerificaValorFormatados()
    {
        $this->assertNotNull($this->transferencia_falsa);
        $this->assertEquals('20.000,00', $this->transferencia_falsa->valor_formatado);
    }

    public function testTransferenciaSetarValorComVirgula()
    {
        $this->transferencia_falsa->valor = '1.200,98';
        $this->assertEquals(1200.98, $this->transferencia_falsa->valor);
        $this->assertEquals('1.200,98', $this->transferencia_falsa->valor_formatado);

        $this->transferencia_falsa->valor = '5,0';
        $this->assertEquals(5.00, $this->transferencia_falsa->valor);
        $this->assertEquals('5,00', $this->transferencia_falsa->valor_formatado);
    }

}

