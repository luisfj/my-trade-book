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
        PerfilInvestidor::firstOrCreate([
            'nome'      => 'Day Trader (Scalper)',
            'descricao'     => 'Busco operações rápidas.'
        ]);

        PerfilInvestidor::firstOrCreate([
            'nome'      => 'Day Trader',
            'descricao'     => 'Realizo operações que iniciam e terminam no dia.'
        ]);

        PerfilInvestidor::firstOrCreate([
            'nome'      => 'Swing Trader',
            'descricao'     => 'Realizo operações que duram mais que um dia.'
        ]);

        PerfilInvestidor::firstOrCreate([
            'nome'      => 'Position Trader',
        ]);

        PerfilInvestidor::firstOrCreate([
            'nome'      => 'Buy and Hold',
        ]);
    }
}
