<?php

use App\Models\PerfilInvestidor;
use Illuminate\Database\Seeder;

class PerfilInvestidorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PerfilInvestidor::create([
            'nome'      => 'Day Trader (Scalper)',
            'descricao'     => 'Busco operações rápidas.'
        ]);

        PerfilInvestidor::create([
            'nome'      => 'Day Trader',
            'descricao'     => 'Realizo operações que iniciam e terminam no dia.'
        ]);

        PerfilInvestidor::create([
            'nome'      => 'Swing Trader',
            'descricao'     => 'Realizo operações que duram mais que um dia.'
        ]);

        PerfilInvestidor::create([
            'nome'      => 'Position Trader',
        ]);

        PerfilInvestidor::create([
            'nome'      => 'Buy and Hold',
        ]);
    }
}
