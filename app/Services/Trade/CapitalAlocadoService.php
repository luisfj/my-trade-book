<?php

namespace App\Services\Trade;

use App\Helpers\ValoresHelper;
use App\Models\CapitalAlocado;
use App\Models\DepositoEmConta;
use App\Models\Operacoes;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CapitalAlocadoService
{
    private $repository;
    private $operacoesRepository;
    private $repositoryTransferencia;
    private $contaCorretoraService;

    public function __construct(CapitalAlocado $repository, DepositoEmConta $repositoryTransferencia,
            ContaCorretoraService $contaCorretoraService, Operacoes $operacoesRepository)
    {
        $this->repository = $repository;
        $this->operacoesRepository = $operacoesRepository;
        $this->repositoryTransferencia = $repositoryTransferencia;
        $this->contaCorretoraService = $contaCorretoraService;
    }

    public function create($dados)
    {
        Auth::user()->capitalAlocado()->create($dados);
    }

    public function update($dados, $id)
    {
        $transacao = $this->getById($id);
        $transacao->update($dados);
        return $transacao;
    }

    public function delete($id)
    {
        $transacao = $this->getById($id);
        $transacao->delete();
    }

    public function getById($id)
    {
        $dep =  $this->repository->with('transferencias')->where('usuario_id', Auth::user()->id)->findOrFail($id);

        return $dep;
    }

    public function getByUser()
    {
        $dep = $this->repository->with('transferencias')->with('contasComposicao')
            ->where('usuario_id', Auth::user()->id)->get();
        return $dep;
    }

    public function getByDepositoOuSaquePeloId($id){
        $trans = $this->repositoryTransferencia->with('capitalAlocado')->findOrFail($id);
        if($trans == null || $trans->capitalAlocado->usuario_id != Auth::user()->id)
            throw new Exception('Nenhuma transferencia com o id informado cadastrada!');
        return $trans;
    }

    public function depositarOuSacar($dados)
    {
        $dados['valor'] =  ValoresHelper::converterStringParaValor($dados['valor']);
        $conta_obj = $this->getById($dados['capitalAlocado_id']);
        $conta_obj->transferencias()->create($dados);
    }

    public function atualizaDepositoOuSaque($dados, $transacao_id){
        $transf_obj = $this->getByDepositoOuSaquePeloId($transacao_id);
        $dados['valor'] =  ValoresHelper::converterStringParaValor($dados['valor']);

        $transf_obj->update($dados);
    }

    public function removerDepositoOuSaque($transacao_id){
        $deposito_obj = $this->getByDepositoOuSaquePeloId($transacao_id);
        if($deposito_obj->conta_id != null){
            $deposito_obj->capitalAlocado_id = null;
            $deposito_obj->update();
        } else {
            $deposito_obj->delete();
        }
    }

    public function addConta($dados){
        $conta = $this->contaCorretoraService->getById($dados['conta_id']);
        $capital = $this->getById($dados['capitalAlocado_id']);
        $conta->capitalAlocado_id = $capital->id;
        $conta->update();
    }

    public function removeConta($dados){
        $conta = $this->contaCorretoraService->getById($dados['conta_id']);
        $conta->capitalAlocado_id = null;
        $conta->update();
    }

    public function getMesAMesApartirDaData($dt = '2019-01-01')
    {
        return DB::raw('select z.Date, MONTH(z.Date) as mes, YEAR(z.Date) as ano
        from
        (
            select curdate() - INTERVAL (a.a + (10 * b.a) + (100 * c.a)) Month as Date
            from (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as a
            cross join (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as b
            cross join (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as c
        ) as z
        where z.Date >= \'' . $dt . '\'
            OR (YEAR(z.Date) = YEAR(\'' . $dt . '\') AND MONTH(z.Date) = MONTH(\'' . $dt . '\'))
        group by year(z.Date), month(z.Date)');
    }

    public function dataMaisBaixaPorCapitalAlocado($capitalAlocadoIds)
    {
        $corretoras = $this->contaCorretoraService->buscarPorCapitalAlocadoQuery($capitalAlocadoIds);
        $corrIds = $corretoras->select('id')->get()->toArray();
        $transCorrMinDate = $this->repositoryTransferencia->whereIn('conta_id', $corrIds)->min('data');

        $minOperDate = $this->operacoesRepository
                ->whereIn('conta_corretora_id', $corrIds)
                ->min('fechamento');

        $minCap = $this->repositoryTransferencia->whereIn('capitalAlocado_id', $capitalAlocadoIds)->min('data');

        if($minOperDate > $transCorrMinDate)
            $minOperDate = $transCorrMinDate;
        if($minOperDate > $minCap)
            $minOperDate = $minCap;
        return $minOperDate;
    }

    public function getEvolucaoDeSaldoAnualPorContaCorretoraDeCapitalAlocadoQuery()
    {
        $capitaisIds = $this->repository->where('capital_alocados.usuario_id', Auth::user()->id)->select('id')->get()->toArray();

        $dataMaisAntiga = $this->dataMaisBaixaPorCapitalAlocado($capitaisIds);

        $sel = $this->getMesAMesApartirDaData($dataMaisAntiga);
        $tb = DB::table( DB::raw("({$sel}) as z") );

        return $this->repository
            ->join('conta_corretoras as contaCor', 'contaCor.capitalAlocado_id', 'capital_alocados.id')
            ->join('corretoras as corretora', 'contaCor.corretora_id', 'corretora.id')
            ->joinSub($tb, 'tb_mes', function ($join) {
                $join->on('tb_mes.ano', '=', 'tb_mes.ano');
            })
            ->leftJoin('operacoes', function ($join) {
                $join->on('operacoes.conta_corretora_id', 'contaCor.id')
                ->whereRaw('tb_mes.mes = MONTH(operacoes.fechamento) AND tb_mes.ano = YEAR(operacoes.fechamento)');

            })
            ->leftJoin('moedas', 'capital_alocados.moeda_id', 'moedas.id')
            ->where('capital_alocados.usuario_id', Auth::user()->id)
            ->selectRaw('
                SUM(COALESCE(operacoes.resultado, 0)) as resultado,
                tb_mes.mes as mes,
                tb_mes.ano as ano,
                contaCor.id as conta_corretora_id,
                contaCor.identificador as conta_corretora_identificador,
                corretora.nome as corretora_nome,
                capital_alocados.id as capital_alocado_id,
                capital_alocados.nome,
                moedas.sigla as sigla ')

            ->selectSub(function ($query) {
                $query
                ->selectRaw('sum(oper.resultado) as saldo_anterior')
                ->from('operacoes as oper')
                ->whereRaw('oper.conta_corretora_id = contaCor.id')
                ->whereRaw('( (YEAR(oper.fechamento) < tb_mes.ano ) OR
                                ( YEAR(oper.fechamento) = tb_mes.ano AND
                                  MONTH(oper.fechamento) < tb_mes.mes) )');
                }, 'resultado_anterior')

            ->selectSub(function ($query) {
                $query
                ->selectRaw('sum(COALESCE(depositos.valor, 0)) as saldo_atual')
                ->from('deposito_em_contas as depositos')
                ->whereRaw('depositos.conta_id = contaCor.id')
                ->whereRaw('( (YEAR(depositos.data) < tb_mes.ano) OR
                                ( YEAR(depositos.data) = tb_mes.ano AND
                                  MONTH(depositos.data) < tb_mes.mes) )');
                }, 'transferencias_anterior')
            ->selectSub(function ($query) {
                $query
                ->selectRaw('sum(COALESCE(depositos.valor, 0)) as saldo_atual')
                ->from('deposito_em_contas as depositos')
                ->whereRaw('depositos.conta_id = contaCor.id')
                ->whereRaw('depositos.capitalAlocado_id IS NULL')
                ->whereRaw('( (YEAR(depositos.data) < tb_mes.ano) OR
                                (YEAR(depositos.data) = tb_mes.ano AND
                                    MONTH(depositos.data) < tb_mes.mes) )');
                }, 'depositos_menos_saques_anterior')
            ->selectSub(function ($query) {
                $query
                ->selectRaw('sum(COALESCE(depositos.valor, 0)) as transferencias_mes')
                ->from('deposito_em_contas as depositos')
                ->whereRaw('depositos.conta_id = contaCor.id')
                ->whereRaw('depositos.capitalAlocado_id IS NOT NULL')
                ->whereRaw('( (YEAR(depositos.data) = tb_mes.ano AND
                                    MONTH(depositos.data) = tb_mes.mes) )');
                }, 'transferencias_mes')
            ->selectSub(function ($query) {
                $query
                ->selectRaw('sum(IF(COALESCE(depositos.valor, 0) > 0, depositos.valor, 0) ) as depositos_conta_mes')
                ->from('deposito_em_contas as depositos')
                ->whereRaw('depositos.conta_id = contaCor.id')
                ->whereRaw('depositos.capitalAlocado_id IS NULL')
                ->whereRaw('(YEAR(depositos.data) = tb_mes.ano AND
                                    MONTH(depositos.data) = tb_mes.mes) ');
                }, 'depositos_conta_mes')
            ->selectSub(function ($query) {
                    $query
                    ->selectRaw('sum(IF(COALESCE(depositos.valor, 0) < 0, depositos.valor, 0) ) as saques_conta_mes')
                    ->from('deposito_em_contas as depositos')
                    ->whereRaw('depositos.conta_id = contaCor.id')
                    ->whereRaw('depositos.capitalAlocado_id IS NULL')
                    ->whereRaw('(YEAR(depositos.data) = tb_mes.ano AND
                                        MONTH(depositos.data) = tb_mes.mes) ');
                    }, 'saques_conta_mes')


            ->selectSub(function ($query) {
                $query
                ->selectRaw('sum(IF(depositos.conta_id is null, COALESCE(depositos.valor, 0), COALESCE(depositos.valor, 0)*-1) ) as tranferencias_cap_aloc_anterior')
                ->from('deposito_em_contas as depositos')
                ->whereRaw('depositos.capitalAlocado_id = capital_alocados.id')
                ->whereRaw('( (YEAR(depositos.data) < tb_mes.ano) OR
                                (YEAR(depositos.data) = tb_mes.ano AND
                                    MONTH(depositos.data) < tb_mes.mes) )');
                }, 'tranferencias_cap_aloc_anterior')
            ->selectSub(function ($query) {
                $query
                ->selectRaw('sum(IF(depositos.conta_id is null, COALESCE(depositos.valor, 0), COALESCE(depositos.valor, 0)*-1) ) as transferencias_cap_aloc_mes')
                ->from('deposito_em_contas as depositos')
                ->whereRaw('depositos.capitalAlocado_id = capital_alocados.id')
                ->whereRaw('depositos.conta_id IS NOT NULL')
                ->whereRaw('( (YEAR(depositos.data) = tb_mes.ano AND
                                    MONTH(depositos.data) = tb_mes.mes) )');
                }, 'transferencias_cap_aloc_mes')
            ->selectSub(function ($query) {
                $query
                ->selectRaw('sum(IF(COALESCE(depositos.valor, 0) > 0, depositos.valor, 0) ) as depositos_cap_aloc_mes')
                ->from('deposito_em_contas as depositos')
                ->whereRaw('depositos.capitalAlocado_id = capital_alocados.id')
                ->whereRaw('depositos.conta_id IS NULL')
                ->whereRaw('(YEAR(depositos.data) = tb_mes.ano AND
                                    MONTH(depositos.data) = tb_mes.mes) ');
                }, 'depositos_cap_aloc_mes')
            ->selectSub(function ($query) {
                    $query
                    ->selectRaw('sum(IF(COALESCE(depositos.valor, 0) < 0, depositos.valor, 0) ) as saques_cap_aloc_mes')
                    ->from('deposito_em_contas as depositos')
                    ->whereRaw('depositos.capitalAlocado_id = capital_alocados.id')
                    ->whereRaw('depositos.conta_id IS NULL')
                    ->whereRaw('(YEAR(depositos.data) = tb_mes.ano AND
                                        MONTH(depositos.data) = tb_mes.mes) ');
                    }, 'saques_cap_aloc_mes')
            ->selectSub(function ($query) {
                $query
                ->selectRaw('sum(IF(COALESCE(depositos.valor, 0) > 0, depositos.valor, 0) ) as depositos_cap_aloc_mes')
                ->from('deposito_em_contas as depositos')
                ->whereRaw('depositos.capitalAlocado_id = capital_alocados.id')
                ->whereRaw('depositos.conta_id IS NULL')
                ->whereRaw('( (YEAR(depositos.data) < tb_mes.ano) OR
                                (YEAR(depositos.data) = tb_mes.ano AND
                                    MONTH(depositos.data) < tb_mes.mes) )');
                }, 'depositos_cap_aloc_anterior')
            ->selectSub(function ($query) {
                    $query
                    ->selectRaw('sum(IF(COALESCE(depositos.valor, 0) < 0, depositos.valor, 0) ) as saques_cap_aloc_mes')
                    ->from('deposito_em_contas as depositos')
                    ->whereRaw('depositos.capitalAlocado_id = capital_alocados.id')
                    ->whereRaw('depositos.conta_id IS NULL')
                    ->whereRaw('( (YEAR(depositos.data) < tb_mes.ano) OR
                                (YEAR(depositos.data) = tb_mes.ano AND
                                    MONTH(depositos.data) < tb_mes.mes) )');
                    }, 'saques_cap_aloc_anterior')

            ->orderBy('tb_mes.ano')
            ->orderBy('tb_mes.mes')
            ->orderBy('contaCor.identificador')
            ->groupby('tb_mes.mes','tb_mes.ano', 'contaCor.id', 'capital_alocados.id', 'capital_alocados.nome', 'moedas.sigla',
                'contaCor.identificador', 'corretora.nome', 'transferencias_mes', 'depositos_conta_mes', 'saques_conta_mes', 'resultado_anterior',
                'transferencias_anterior', 'tranferencias_cap_aloc_anterior', 'transferencias_cap_aloc_mes', 'depositos_cap_aloc_mes', 'saques_cap_aloc_mes',
                'saques_cap_aloc_anterior', 'depositos_cap_aloc_anterior', 'depositos_menos_saques_anterior');

    }

    public function getSaldoAtualDeCapitalAlocado()
    {
        $cons = $this->repository
            ->leftJoin('conta_corretoras as contaCor', 'contaCor.capitalAlocado_id', 'capital_alocados.id')
            ->leftJoin('operacoes', 'operacoes.conta_corretora_id', 'contaCor.id')
            ->leftJoin('moedas', 'capital_alocados.moeda_id', 'moedas.id')
            ->whereRaw('capital_alocados.usuario_id = ' . Auth::user()->id)
            ->selectRaw('
                SUM(COALESCE(operacoes.resultado, 0)) as resultado_operacoes,
                capital_alocados.id as capital_alocado_id,
                capital_alocados.nome,
                moedas.sigla as sigla ')

            ->selectSub(function ($query) {
                $query
                ->selectRaw('sum(IF(COALESCE(depositos.valor, 0) > 0, depositos.valor, 0) ) as depositos')
                ->from('deposito_em_contas as depositos')
                ->whereRaw('(depositos.conta_id = contaCor.id and depositos.capitalAlocado_id IS NULL)');
                }, 'depositos')
            ->selectSub(function ($query) {
                $query
                ->selectRaw('sum(IF(COALESCE(depositos.valor, 0) < 0, depositos.valor, 0) ) as saques')
                ->from('deposito_em_contas as depositos')
                ->whereRaw('(depositos.conta_id = contaCor.id and depositos.capitalAlocado_id IS NULL)');
                }, 'saques')

            ->orderBy('capital_alocados.nome')
            ->groupby('capital_alocados.id', 'capital_alocados.nome', 'depositos', 'saques', 'moedas.sigla');

            //return DB::table( DB::raw("({$query->toSql()}) as sub") ) ->get();
            return DB::table( DB::raw("({$cons->toSql()}) as consulta") )
            //return DB::table($query, 'consulta')
            ->selectSub(function ($query) {
                $query
                ->selectRaw('sum(IF(COALESCE(depositos.valor, 0) > 0, depositos.valor, 0) ) as depositos')
                ->from('deposito_em_contas as depositos')
                ->whereRaw('(depositos.conta_id IS NULL and depositos.capitalAlocado_id = consulta.capital_alocado_id)');
                }, 'depositos_conta_externa')
            ->selectSub(function ($query) {
                    $query
                    ->selectRaw('sum(IF(COALESCE(depositos.valor, 0) < 0, depositos.valor, 0) ) as saques')
                    ->from('deposito_em_contas as depositos')
                    ->whereRaw('(depositos.conta_id IS NULL and depositos.capitalAlocado_id = consulta.capital_alocado_id)');
                    }, 'saques_conta_externa')
            ->selectRaw('consulta.capital_alocado_id, consulta.nome, sum(resultado_operacoes) as resultado_operacoes,
                    sum(consulta.depositos) as depositos, sum(consulta.saques) as saques, consulta.sigla
            ')->groupby('consulta.capital_alocado_id', 'consulta.nome', 'depositos_conta_externa', 'saques_conta_externa', 'consulta.sigla')
            ->get();

    }


}
