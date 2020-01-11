<?php

namespace Tests\Unit\Helpers;

use App\Helpers\ValoresHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ValoresHelperTest extends TestCase
{//fwrite(STDERR, print_r($conta->saidas, TRUE).' / ');
    public function test_converterStringParaValor_Converter_Valores_Com_Virgula()
    {
        $valor1 = '1.200,98';
        $valor2 = '3,01';
        $valor3 = '2,00';

        $valor_convertido1 = ValoresHelper::converterStringParaValor($valor1);
        $valor_convertido2 = ValoresHelper::converterStringParaValor($valor2);
        $valor_convertido3 = ValoresHelper::converterStringParaValor($valor3);

        $this->assertEquals('1200.98',  $valor_convertido1);
        $this->assertEquals(   '3.01',  $valor_convertido2);
        $this->assertEquals(   '2.00',  $valor_convertido3);
    }

    public function test_converterStringParaValor_Converter_Valores_Com_Ponto()
    {
        $valor1 = '1200.98';
        $valor2 = '3.01';
        $valor3 = '2.00';

        $valor_convertido1 = ValoresHelper::converterStringParaValor($valor1);
        $valor_convertido2 = ValoresHelper::converterStringParaValor($valor2);
        $valor_convertido3 = ValoresHelper::converterStringParaValor($valor3);

        $this->assertEquals('1200.98',  $valor_convertido1);
        $this->assertEquals(   '3.01',  $valor_convertido2);
        $this->assertEquals(   '2.00',  $valor_convertido3);
    }

    public function test_converterValorParaPadrao()
    {
        $valor1 = 100000.98;
        $valor2 = 1200.98;
        $valor3 = 3.01;
        $valor4 = 2.00;

        $valor_convertido1 = ValoresHelper::converterValorParaPadrao($valor1);
        $valor_convertido2 = ValoresHelper::converterValorParaPadrao($valor2);
        $valor_convertido3 = ValoresHelper::converterValorParaPadrao($valor3);
        $valor_convertido4 = ValoresHelper::converterValorParaPadrao($valor4);

        $this->assertEquals('100.000,98',  $valor_convertido1);
        $this->assertEquals(  '1.200,98',  $valor_convertido2);
        $this->assertEquals(      '3,01',  $valor_convertido3);
        $this->assertEquals(      '2,00',  $valor_convertido4);
    }

    public function test_converterValorDesconsideraCasasDecimais()
    {
        $valor1 = 100000.98;
        $valor2 = 2.00;
        $valor3 = '1200,98';
        $valor4 = '3,01';

        $valor_convertido1 = ValoresHelper::converterValorDesconsideraCasasDecimais($valor1);
        $valor_convertido2 = ValoresHelper::converterValorDesconsideraCasasDecimais($valor2);
        $valor_convertido3 = ValoresHelper::converterValorDesconsideraCasasDecimais($valor3);
        $valor_convertido4 = ValoresHelper::converterValorDesconsideraCasasDecimais($valor4);

        $this->assertEquals('100000,98',  $valor_convertido1);
        $this->assertEquals(        '2',  $valor_convertido2);
        $this->assertEquals(  '1200,98',  $valor_convertido3);
        $this->assertEquals(     '3,01',  $valor_convertido4);
    }

    public function test_converterStringParaInteiro_Converter_Valores_Com_Virgula()
    {
        $valor1 = '1.200,98';
        $valor2 = '-3,01';
        $valor3 = '2,00';

        $valor_convertido1 = ValoresHelper::converterStringParaInteiro($valor1);
        $valor_convertido2 = ValoresHelper::converterStringParaInteiro($valor2);
        $valor_convertido3 = ValoresHelper::converterStringParaInteiro($valor3);

        $this->assertEquals(1200,  $valor_convertido1);
        $this->assertEquals(  -3,  $valor_convertido2);
        $this->assertEquals(   2,  $valor_convertido3);
    }

    public function test_converterStringParaInteiro_Converter_Valores_Com_Ponto()
    {
        $valor1 = '1200.98';
        $valor2 = '-3.01';
        $valor3 = '2.00';

        $valor_convertido1 = ValoresHelper::converterStringParaInteiro($valor1);
        $valor_convertido2 = ValoresHelper::converterStringParaInteiro($valor2);
        $valor_convertido3 = ValoresHelper::converterStringParaInteiro($valor3);

        $this->assertEquals('1200',  $valor_convertido1);
        $this->assertEquals(  '-3',  $valor_convertido2);
        $this->assertEquals(   '2',  $valor_convertido3);
    }

    public function test_converterStringParaInteiro_Converter_Valores_Inteiros_Positivos()
    {
        $valor1 = '1200';
        $valor2 = 3;

        $valor_convertido1 = ValoresHelper::converterStringParaInteiro($valor1);
        $valor_convertido2 = ValoresHelper::converterStringParaInteiro($valor2);

        $this->assertEquals('1200',  $valor_convertido1);
        $this->assertEquals(   '3',  $valor_convertido2);
    }

    public function test_converterStringParaInteiro_Converter_Valores_Inteiros_Negativos()
    {
        $valor1 = '-1200';
        $valor2 = -3;

        $valor_convertido1 = ValoresHelper::converterStringParaInteiro($valor1);
        $valor_convertido2 = ValoresHelper::converterStringParaInteiro($valor2);

        $this->assertEquals(-1200,  $valor_convertido1);
        $this->assertEquals(   '-3',  $valor_convertido2);
    }

}
