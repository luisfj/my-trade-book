<?php

namespace Tests\Unit\Models;

use App\Models\Instrumento;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InstrumentoTest extends TestCase
{
    use  DatabaseTransactions;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testCreateInstrumento()
    {
        $instrumento = factory(Instrumento::class)->create([
            'nome'  => 'instrumentoDemo',
            'sigla' => 'demo'
        ]);
        $this->assertNotNull($instrumento);
        $this->assertEquals($instrumento->nome, 'instrumentoDemo');
        $this->assertEquals($instrumento->sigla, 'demo');
        $this->assertDatabaseHas('instrumentos', [
            'nome' => 'instrumentoDemo',
            'sigla' => 'demo'
        ]);
    }
}
