<?php

namespace App\Services\Trade;

use App\Models\Operacoes;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OperacoesService
{
    private $repository;
    private $depositoService;
    private $contaService;
    private $instrumentoService;

    public function __construct(Operacoes $repository, DepositoEmContaService $depositoService,
                ContaCorretoraService $contaService, InstrumentoService $instrumentoService)
    {
        $this->repository         = $repository;
        $this->depositoService    = $depositoService;
        $this->contaService       = $contaService;
        $this->instrumentoService = $instrumentoService;
    }

    public function create($dados)
    {
        $operacao = $this->getByTicket($dados['ticket']);
        if($operacao){
            $this->update($dados, $operacao->id);
        } else {
            $dados['usuario_id'] = Auth::user()->id;

            $this->repository->create($dados);
        }
    }

    public function update($dados, $id)
    {
        $conta = $this->getById($id);
        $conta->update($dados);
    }

    public function delete($id)
    {
        $conta = $this->getById($id);
        $conta->delete();
    }

    public function getAllByUser()
    {
        return $this->repository->with('instrumento')->with('moeda')->with('contaCorretora')->where('usuario_id', Auth::user()->id)->get();
    }

    public function getById($id)
    {
        return $this->repository->with('instrumento')->with('moeda')->with('contaCorretora')->where('usuario_id', Auth::user()->id)->findOrFail($id);
    }

    public function getByTicket($ticket)
    {
        return $this->repository->where('usuario_id', Auth::user()->id)->where('ticket', $ticket)->first();
    }

    public function getDataPrimeiraOperacao($contaCorretora)
    {
        return $this->repository
            ->where('usuario_id', Auth::user()->id)
            ->where('conta_corretora_id', $contaCorretora)
            ->min('abertura');
    }

    public function getResultadoDaConta($contaCorretora)
    {
        return
            $this->repository
                ->where('usuario_id', Auth::user()->id)
                ->where('conta_corretora_id', $contaCorretora)
                ->sum('resultado');
    }

    public function getDadosAvancadosConta($contaCorretora)
    {
        return
            $this->repository
                ->where('usuario_id', Auth::user()->id)
                ->where('conta_corretora_id', $contaCorretora)
                ->selectRaw('COUNT(id) as nrOperacoes, count(IF(resultado >= 0, 1, NULL)) as nrGains,
                        SUM(IF(resultado >= 0, resultado, 0)) as totalGainsValor, SUM(IF(resultado < 0, resultado, 0)) as totalLossesValor,
                        sum( (pips * REPLACE(CAST(lotes AS CHAR), ".", "") ) ) as totalPontos,
                        AVG(IF(pips >= 0, pips, NULL)) as mediaGainPontos, AVG(IF(resultado >= 0, resultado, NULL)) as mediaGainValor,
                        AVG(IF(pips < 0, pips, NULL)) as mediaLossPontos, AVG(IF(resultado < 0, resultado, NULL)) as mediaLossValor,
                        SUM(comissao + impostos + swap) as comissoesImpostos,
                        avg( TIME_TO_SEC(time(tempo_operacao_dias * time("24:00:00"))) + TIME_TO_SEC(tempo_operacao_horas) ) as mediaTempoOperacaoSec')->first();
    }

    public function getDadosAvancadosContaPorAtivo($contaCorretora)
    {
        return
            $this->repository->with('instrumento')
                ->where('usuario_id', Auth::user()->id)
                ->where('conta_corretora_id', $contaCorretora)
                ->groupby('instrumento_id')
                ->orderByDesc('nrOperacoes')
                ->selectRaw('instrumento_id, COUNT(id) as nrOperacoes,
                        count(IF(resultado >= 0, 1, NULL)) as nrGains,
                        count(IF(resultado < 0, 1, NULL)) as nrLosses,
                        sum( (pips * REPLACE(CAST(lotes AS CHAR), ".", "") ) ) as totalPontos,
                        sum(resultado) as totalValor')->get();
    }

    public function getPiorOperacaoValor($contaCorretora)
    {
        return
            $this->repository
                ->where('usuario_id', Auth::user()->id)
                ->where('conta_corretora_id', $contaCorretora)
                ->orderBy('resultado')
                ->selectRaw('resultado, DATE_FORMAT(fechamento, "%d/%m/%y") as data ')->first();
    }

    public function getMelhorOperacaoValor($contaCorretora)
    {
        return
            $this->repository
                ->where('usuario_id', Auth::user()->id)
                ->where('conta_corretora_id', $contaCorretora)
                ->orderByDesc('resultado')
                ->selectRaw('resultado, DATE_FORMAT(fechamento, "%d/%m/%y") as data ')->first();
    }

    public function getPiorOperacaoPontos($contaCorretora)
    {
        return
            $this->repository
                ->where('usuario_id', Auth::user()->id)
                ->where('conta_corretora_id', $contaCorretora)
                ->orderBy('pips')
                ->selectRaw('pips, DATE_FORMAT(fechamento, "%d/%m/%y") as data ')->first();
    }

    public function getMelhorOperacaoPontos($contaCorretora)
    {
        return
            $this->repository
                ->where('usuario_id', Auth::user()->id)
                ->where('conta_corretora_id', $contaCorretora)
                ->orderByDesc('pips')
                ->selectRaw('pips, DATE_FORMAT(fechamento, "%d/%m/%y") as data ')->first();
    }

    public function getMaiorSaldoDiario($contaCorretora)
    {
        return
            $this->repository
                ->where('usuario_id', Auth::user()->id)
                ->where('conta_corretora_id', $contaCorretora)
                ->selectRaw('SUM(resultado) as resultado, DAY(fechamento) as dia, MONTH(fechamento) as mes, YEAR(fechamento) as ano')
                ->selectSub(function ($query) use($contaCorretora)  {//DB::table('operacoes', 'oper')
                    $query
                    ->selectRaw('sum(oper.resultado) as saldo_atual')
                    ->from('operacoes as oper')
                    ->where('oper.usuario_id', Auth::user()->id)
                    ->where('oper.conta_corretora_id', $contaCorretora)
                    ->whereRaw('( ( YEAR(oper.fechamento) < YEAR(operacoes.fechamento) ) OR
                                    ( YEAR(oper.fechamento) = YEAR(operacoes.fechamento) AND
                                        MONTH(oper.fechamento) < MONTH(operacoes.fechamento) ) OR
                                        ( YEAR(oper.fechamento) = YEAR(operacoes.fechamento) AND
                                        MONTH(oper.fechamento) = MONTH(operacoes.fechamento) AND
                                        DAY(oper.fechamento) <= DAY(operacoes.fechamento) )
                                        )');
                    }, 'saldo_atual')
                ->orderByDesc('saldo_atual')
                ->groupby('ano', 'mes', 'dia', 'saldo_atual')
                ->first();
    }

    public function getMesesOperados()
    {
        return $this->repository->where('usuario_id', Auth::user()->id)->whereNotNull('fechamento')
                ->selectRaw('CONCAT(MONTH(fechamento),\'-\',YEAR(fechamento)) as mes_ano, MONTH(fechamento) as mes, YEAR(fechamento) as ano')->distinct()
                ->orderByDesc('fechamento')->get();
    }

    public function getAnosOperados()
    {
        return $this->repository->where('usuario_id', Auth::user()->id)
                ->whereNotNull('fechamento')
                ->selectRaw('YEAR(fechamento) as ano')->distinct()
                ->orderByDesc('fechamento')->get();
    }

    public function getAtivosOperados()
    {
        return $this->repository->with('instrumento')->where('usuario_id', Auth::user()->id)->whereNotNull('fechamento')
                ->select('instrumento_id')->distinct()->get();
    }

    public function getCorretorasOperadas()
    {
        return $this->repository->with('contaCorretora.corretora')->where('usuario_id', Auth::user()->id)->whereNotNull('fechamento')
                ->select('conta_corretora_id')->distinct()->get();
    }

    public function getResultadoDiasDaSemana($ativosSelecionado, $corretorasSelecionada, $dataInicial, $dataFinal)
    {
        return $this->repository
                ->where('usuario_id', Auth::user()->id)
                ->where(function ($query) use($ativosSelecionado, $corretorasSelecionada, $dataInicial, $dataFinal) {
                    if($ativosSelecionado != null && count($ativosSelecionado) > 0){
                        $query->whereIn('instrumento_id', $ativosSelecionado);
                    }
                    if($corretorasSelecionada != null && count($corretorasSelecionada) > 0){
                        $query->whereIn('conta_corretora_id', $corretorasSelecionada);
                    }
                    if($dataInicial != null){
                        $query->whereDate('abertura', '>=', $dataInicial);
                    }
                    if($dataFinal != null){
                        $query->whereDate('abertura', '<=', $dataFinal);
                    }
                })
                ->selectRaw('dayname(abertura) as diaDaSemana,  WEEKDAY(abertura) as nrDiaSemana, COUNT(id) as nrOperacoes, COUNT(IF(resultado >= 0, 1, NULL)) as nrGains, COUNT(IF(resultado < 0, 1, NULL)) as nrLosses,
                        SUM(IF(resultado >= 0, resultado, 0)) as totalGainsValor, SUM(IF(resultado < 0, resultado, 0)) as totalLossesValor')
                ->orderBy('nrDiaSemana')
                ->groupby('diaDaSemana', 'nrDiaSemana')
                ->get();
    }

    public function getResultadoPorSemanaDoMes($ativosSelecionado, $corretorasSelecionada, $dataInicial, $dataFinal)
    {
        return $this->repository
                ->where('usuario_id', Auth::user()->id)
                ->where(function ($query) use($ativosSelecionado, $corretorasSelecionada, $dataInicial, $dataFinal) {
                    if($ativosSelecionado != null && count($ativosSelecionado) > 0){
                        $query->whereIn('instrumento_id', $ativosSelecionado);
                    }
                    if($corretorasSelecionada != null && count($corretorasSelecionada) > 0){
                        $query->whereIn('conta_corretora_id', $corretorasSelecionada);
                    }
                    if($dataInicial != null){
                        $query->whereDate('abertura', '>=', $dataInicial);
                    }
                    if($dataFinal != null){
                        $query->whereDate('abertura', '<=', $dataFinal);
                    }
                })
                ->selectRaw('(WEEK(abertura,5) - WEEK(DATE_SUB(abertura, INTERVAL DAYOFMONTH(abertura) - 1 DAY),5) + 1) as diaDaSemana,
                        COUNT(id) as nrOperacoes, COUNT(IF(resultado >= 0, 1, NULL)) as nrGains, COUNT(IF(resultado < 0, 1, NULL)) as nrLosses,
                        SUM(IF(resultado >= 0, resultado, 0)) as totalGainsValor, SUM(IF(resultado < 0, resultado, 0)) as totalLossesValor')
                ->orderBy('diaDaSemana')
                ->groupby('diaDaSemana')
                ->get();
    }

    public function getResultadoHoraDoDia($ativosSelecionado, $corretorasSelecionada, $dataInicial, $dataFinal)
    {
        return $this->repository
                ->where('usuario_id', Auth::user()->id)
                ->where(function ($query) use($ativosSelecionado, $corretorasSelecionada, $dataInicial, $dataFinal) {
                    if($ativosSelecionado != null && count($ativosSelecionado) > 0){
                        $query->whereIn('instrumento_id', $ativosSelecionado);
                    }
                    if($corretorasSelecionada != null && count($corretorasSelecionada) > 0){
                        $query->whereIn('conta_corretora_id', $corretorasSelecionada);
                    }
                    if($dataInicial != null){
                        $query->whereDate('abertura', '>=', $dataInicial);
                    }
                    if($dataFinal != null){
                        $query->whereDate('abertura', '<=', $dataFinal);
                    }
                })
                ->selectRaw('HOUR(abertura) as horaDoDia, COUNT(id) as nrOperacoes, COUNT(IF(resultado >= 0, 1, NULL)) as nrGains, COUNT(IF(resultado < 0, 1, NULL)) as nrLosses,
                        SUM(IF(resultado >= 0, resultado, 0)) as totalGainsValor, SUM(IF(resultado < 0, resultado, 0)) as totalLossesValor')
                ->orderBy('horaDoDia')
                ->groupby('horaDoDia')
                ->get();
    }

    public function getByMesEAno($data, $ativosSelecionado, $corretorasSelecionada)
    {
        if(!$data)
            return null;
        $dataArr = explode('-', $data);
        if(count($dataArr) != 2)
            return null;

        return $this->repository->with('instrumento')->with('moeda')->with('contaCorretora.corretora')
                ->where('usuario_id', Auth::user()->id)
                ->where(function ($query) use($ativosSelecionado, $corretorasSelecionada) {
                    if($ativosSelecionado != null && count($ativosSelecionado) > 0){
                        $query->whereIn('instrumento_id', $ativosSelecionado);
                    }
                    if($corretorasSelecionada != null && count($corretorasSelecionada) > 0){
                        $query->whereIn('conta_corretora_id', $corretorasSelecionada);
                    } else {
                        $query->where('conta_corretora_id', 0);
                    }
                })
                ->whereYear('fechamento', '=', $dataArr[1])
                ->whereMonth('fechamento', '=', $dataArr[0])
                ->orderBy('fechamento')
                ->get();
    }

    public function getEvolucaoDeSaldoAnual($ativosSelecionado, $corretorasSelecionada, $anos)
    {
                return $this->repository
                    ->where('usuario_id', Auth::user()->id)
                    ->where(function ($query) use($ativosSelecionado, $corretorasSelecionada, $anos) {
                        if($ativosSelecionado != null && count($ativosSelecionado) > 0){
                            $query->whereIn('instrumento_id', $ativosSelecionado);
                        }
                        if($corretorasSelecionada != null && count($corretorasSelecionada) > 0){
                            $query->whereIn('conta_corretora_id', $corretorasSelecionada);
                        }
                        if($anos != null && count($anos) > 0){
                            $query->whereIn(DB::raw("year(fechamento)"), $anos);
                        }
                    })
                    ->selectRaw('SUM(resultado) as resultado, MONTH(fechamento) as mes, YEAR(fechamento) as ano')
                    ->selectSub(function ($query) use($ativosSelecionado, $corretorasSelecionada, $anos)  {//DB::table('operacoes', 'oper')
                        $query
                        ->selectRaw('sum(oper.resultado) as saldo_atual')
                        ->from('operacoes as oper')
                        ->where('oper.usuario_id', Auth::user()->id)
                        ->where(function ($query) use($ativosSelecionado, $corretorasSelecionada, $anos) {
                            if($ativosSelecionado != null && count($ativosSelecionado) > 0){
                                $query->whereIn('oper.instrumento_id', $ativosSelecionado);
                            }
                            if($corretorasSelecionada != null && count($corretorasSelecionada) > 0){
                                $query->whereIn('oper.conta_corretora_id', $corretorasSelecionada);
                            }
                            if($anos != null && count($anos) > 0){
                                $query->whereIn(DB::raw("year(oper.fechamento)"), $anos);
                            }
                        })
                        //->whereRaw('YEAR(oper.fechamento) < YEAR(operacoes.fechamento)')
                        ->whereRaw('( (YEAR(oper.fechamento) < YEAR(operacoes.fechamento)) OR
                                        (YEAR(oper.fechamento) = YEAR(operacoes.fechamento) AND
                                            MONTH(oper.fechamento) <= MONTH(operacoes.fechamento)) )')
                        /*->orWhere([
                            [DB::raw('YEAR(oper.fechamento)'), '=', DB::raw('YEAR(operacoes.fechamento)')],
                            [DB::raw('MONTH(oper.fechamento)'), '<=', DB::raw('MONTH(operacoes.fechamento)')],
                        ])*/;
                        }, 'saldo_atual')
                    ->orderByDesc('ano')
                    ->orderBy('mes')
                    ->groupby('ano', 'mes', 'saldo_atual')
                    ->get();
    }

    public function getEvolucaoDeSaldoMensal($ativosSelecionado, $corretorasSelecionada, $mesSelecionado)
    {
        if(!$mesSelecionado)
            return null;
        $dataArr = explode('-', $mesSelecionado);
        if(count($dataArr) != 2)
            return null;

        return $this->repository
            ->where('usuario_id', Auth::user()->id)
            ->whereYear('fechamento', '=', $dataArr[1])
            ->whereMonth('fechamento', '=', $dataArr[0])
            ->where(function ($query) use($ativosSelecionado, $corretorasSelecionada) {
                if($ativosSelecionado != null && count($ativosSelecionado) > 0){
                    $query->whereIn('instrumento_id', $ativosSelecionado);
                }
                if($corretorasSelecionada != null && count($corretorasSelecionada) > 0){
                    $query->whereIn('conta_corretora_id', $corretorasSelecionada);
                }
            })
            ->selectRaw('SUM(resultado) as resultado, DAY(fechamento) as dia, MONTH(fechamento) as mes, YEAR(fechamento) as ano')
            ->selectSub(function ($query) use($ativosSelecionado, $corretorasSelecionada, $dataArr)  {//DB::table('operacoes', 'oper')
                $query
                ->selectRaw('sum(oper.resultado) as saldo_atual')
                ->from('operacoes as oper')
                ->where('oper.usuario_id', Auth::user()->id)
                //->whereYear('oper.fechamento', '=', $dataArr[1])
                //->whereMonth('oper.fechamento', '=', $dataArr[0])
                ->where(function ($query) use($ativosSelecionado, $corretorasSelecionada) {
                    if($ativosSelecionado != null && count($ativosSelecionado) > 0){
                        $query->whereIn('oper.instrumento_id', $ativosSelecionado);
                    }
                    if($corretorasSelecionada != null && count($corretorasSelecionada) > 0){
                        $query->whereIn('oper.conta_corretora_id', $corretorasSelecionada);
                    }
                })
                ->whereRaw('( ( YEAR(oper.fechamento) < YEAR(operacoes.fechamento) ) OR
                                ( YEAR(oper.fechamento) = YEAR(operacoes.fechamento) AND
                                    MONTH(oper.fechamento) < MONTH(operacoes.fechamento) ) OR
                                    ( YEAR(oper.fechamento) = YEAR(operacoes.fechamento) AND
                                    MONTH(oper.fechamento) = MONTH(operacoes.fechamento) AND
                                    DAY(oper.fechamento) <= DAY(operacoes.fechamento) )
                                    )');
                }, 'saldo_atual')
            ->orderByDesc('ano')
            ->orderBy('mes')
            ->groupby('ano', 'mes', 'dia', 'saldo_atual')
            ->get();
    }

    public function importarOperacoes($conta_corretora_id, $transferencias, $openTrades, $closedTrades, $regImport)
    {
        $conta_obj = $this->contaService->getById($conta_corretora_id);

        $depadd = $this->importarDepositos($transferencias, $conta_obj, $regImport);
        $depositosAdicionados = $depadd['adicionados'];
        $valorDepositos       = $depadd['valor'];

        $tradesFechados = $this->importarTradesFechados($closedTrades, $conta_obj, $regImport);
        $operacoesAdicionadas = $tradesFechados['operacoesAdicionadas'];
        $operacoesAbertas     = $tradesFechados['operacoesAbertas'];
        $operacoesFechadas    = $tradesFechados['operacoesFechadas'];
        $valorOperacoes       = $tradesFechados['valorOperacoes'];

        $tradesAbertos = $this->importarTradesAbertos($openTrades, $conta_obj, $regImport);
        $operacoesAdicionadas = $operacoesAdicionadas + $tradesAbertos['operacoesAdicionadas'];
        $operacoesAbertas     = $operacoesAbertas + $tradesAbertos['operacoesAbertas'];


        //atualizar o saldo da conta
        $valorDepositos = number_format($valorDepositos, 2);
        $valorOperacoes = number_format($valorOperacoes, 2);

        $this->contaService->atualizarSaldoContaPorOperacoes($conta_obj, $valorOperacoes, $operacoesAbertas, $operacoesFechadas);

        $success = "Importação concluida! Foram adicionados " . $operacoesAdicionadas . ' operações e ' . $depositosAdicionados . ' transferencias.';

        return $success;
    }

    public function importarTradesAbertos($openTrades, $conta_obj, $regImport)
    {
        $operacoesAdicionadas = 0;
        $operacoesAbertas = 0;

        foreach($openTrades as $key => $n ) {
            $tipo = $n['tipo'];
            $ticket = $n['ticket'];
            $abertura = $n['abertura'];
            $contratos = (double) $n['contratos'];
            $instrumento = $n['instrumento'];
            $preco_entrada = (double) $n['preco_entrada'];

            $instrumento_obj = $this->instrumentoService->getBySiglaOrCreate($instrumento);
            $operacao_obj = Auth::user()->operacoes()->where('ticket', $ticket)->where('conta_corretora_id', $conta_obj->id)->first();
            if(!$operacao_obj){ //se existir vejo se preciso atualizar
                Auth::user()->operacoes()->create(
                    [
                        'account'        => $conta_obj->identificador,
                        'corretoranome'  => $conta_obj->corretora->nome,
                        'ticket'         => $ticket,
                        'abertura'       => $abertura,
                        'precoentrada'   => $preco_entrada,
                        'tipo'           => $tipo,
                        'lotes'          => $contratos,
                        'moeda_id'       => $conta_obj->moeda->id,
                        'instrumento_id' => $instrumento_obj->id,
                        'conta_corretora_id' => $conta_obj->id,
                        'registro_importacao_id' => $regImport->id
                    ]);
                $operacoesAdicionadas = $operacoesAdicionadas + 1;
                $operacoesAbertas     = $operacoesAbertas + 1;
            }
        }
        return [
            'operacoesAdicionadas'  => $operacoesAdicionadas,
            'operacoesAbertas'      => $operacoesAbertas
        ];
    }

    public function validarTradesAbertos($openTrades, $conta_id)
    {
        $conta_obj = $this->contaService->getById($conta_id);
        $tradesAdicionar = [];

        foreach($openTrades as $key => $n ) {
            $tipo = $n['tipo'];
            $ticket = $n['ticket'];
            $abertura = $n['abertura'];
            $contratos = (double) $n['contratos'];
            $preco_entrada = (double) $n['preco_entrada'];

            $operacao_obj = Auth::user()->operacoes()->where('ticket', $ticket)->where('conta_corretora_id', $conta_id)->first();

            if(!$operacao_obj){ //se existir vejo se preciso atualizar
                $operacao_obj = Auth::user()->operacoes()
                    ->where('abertura', $abertura)
                    ->where('lotes', $contratos)
                    ->where('precoentrada' , $preco_entrada)
                    ->where('conta_corretora_id', $conta_id)->first();
                if(!$operacao_obj){
                    $n->conta = $conta_obj;
                    array_push($tradesAdicionar, $n);
                }
            }
        }
        return $tradesAdicionar;
    }

    public function importarTradesFechados($closedTrades, $conta_obj, $regImport)
    {
        $operacoesAdicionadas = 0;
        $operacoesAbertas = 0;
        $operacoesFechadas = 0;
        $valorOperacoes = 0;

        foreach($closedTrades as $key => $n ) {
            $tipo          = $n['tipo'];
            $ticket        = $n['ticket'];
            $abertura      = $n['abertura'];
            $contratos     = (double) $n['contratos'];
            $instrumento   = $n['instrumento'];
            $preco_entrada = (double) $n['preco_entrada'];
            $fechamento    = $n['fechamento'];
            $preco_saida   = (double) $n['preco_saida'];
            $comissao      = (double) $n['comissao'];
            $impostos      = (double) $n['impostos'];
            $swap          = (double) $n['swap'];
            $resultado_bruto = (double) $n['resultado_bruto'];
            $resultado     = (double) $n['resultado'];
            $pontos        = (double) $n['pontos'];
            $tempo_operacao_dias  = (int) $n['tempo_operacao_dias'];
            $tempo_operacao_horas = $n['tempo_operacao_horas'];
            $estrategia_id = ((!$n['estrategia_id'] || $n['estrategia_id'] == 'null') ? null : $n['estrategia_id']);
            $mep           = (double) $n['mep'];
            $men           = (double) $n['men'];

            $instrumento_obj = $this->instrumentoService->getBySiglaOrCreate($instrumento);

            $operacao_obj = Auth::user()->operacoes()
                ->where('ticket', $ticket)
                ->where('conta_corretora_id', $conta_obj->id)
                ->where('abertura', $abertura)
                ->where('instrumento_id', $instrumento_obj->id)
                ->where('precoentrada', $preco_entrada)
                ->first();
            if(!$operacao_obj){
                $operacao_obj = Auth::user()->operacoes()
                    ->where('abertura', $abertura)
                    ->where('conta_corretora_id', $conta_obj->id)
                    ->where('instrumento_id', $instrumento_obj->id)
                    ->where('precoentrada', $preco_entrada)
                    ->first();
            }
            if($operacao_obj){ //se existir vejo se preciso atualizar
                if(!$operacao_obj->fechamento){//se estiver aberta eu atualizo
                    $operacao_obj->update(
                        [
                            'fechamento'     => $fechamento,
                            'precosaida'     => $preco_saida,
                            'comissao'       => $comissao,
                            'impostos'       => $impostos,
                            'swap'           => $swap,
                            'resultadobruto' => $resultado_bruto,
                            'resultado'      => $resultado,
                            'pips'           => $pontos,
                            'tempo_operacao_dias'  => $tempo_operacao_dias,
                            'tempo_operacao_horas' => $tempo_operacao_horas,
                            'mep'                  => $mep,
                            'men'                  => $men,
                            'estrategia_id'        => $estrategia_id,
                            'registro_importacao_id' => $regImport->id
                        ]);
                    $operacoesAdicionadas = $operacoesAdicionadas + 1;
                    $operacoesFechadas    = $operacoesFechadas + 1;
                    $valorOperacoes       = $valorOperacoes + $resultado;
                }
            } else {
                Auth::user()->operacoes()->create(
                    [
                        'account'        => $conta_obj->identificador,
                        'corretoranome'  => $conta_obj->corretora->nome,
                        'ticket'         => $ticket,
                        'abertura'       => $abertura,
                        'fechamento'     => $fechamento,
                        'precoentrada'   => $preco_entrada,
                        'precosaida'     => $preco_saida,
                        'tipo'           => $tipo,
                        'lotes'          => $contratos,
                        'comissao'       => $comissao,
                        'impostos'       => $impostos,
                        'swap'           => $swap,
                        'resultadobruto' => $resultado_bruto,
                        'resultado'      => $resultado,
                        'pips'           => $pontos,
                        'tempo_operacao_dias'  => $tempo_operacao_dias,
                        'tempo_operacao_horas' => $tempo_operacao_horas,
                        'moeda_id'       => $conta_obj->moeda->id,
                        'instrumento_id' => $instrumento_obj->id,
                        'conta_corretora_id' => $conta_obj->id,
                        'mep'            => $mep,
                        'men'            => $men,
                        'estrategia_id'  => $estrategia_id,
                        'registro_importacao_id' => $regImport->id
                    ]);
                $operacoesAdicionadas = $operacoesAdicionadas + 1;
                $operacoesAbertas     = $operacoesAbertas + 1;
                $operacoesFechadas    = $operacoesFechadas + 1;
                $valorOperacoes       = $valorOperacoes + $resultado;
            }
        }
        return [
            'operacoesAdicionadas'  => $operacoesAdicionadas,
            'operacoesAbertas'      => $operacoesAbertas,
            'operacoesFechadas'     => $operacoesFechadas,
            'valorOperacoes'        => $valorOperacoes,
        ];
    }

    public function validarTradesFechados($closedTrades, $conta_id)
    {
        $conta_obj = $this->contaService->getById($conta_id);
        $tradesFechados = [];

        foreach($closedTrades as $key => $n ) {
            $tipo          = $n['tipo'];
            $ticket        = $n['ticket'];
            $abertura      = $n['abertura'];
            $preco_entrada = (double) $n['preco_entrada'];
            $fechamento    = $n['fechamento'];
            $preco_saida   = (double) $n['preco_saida'];

            $operacao_obj = Auth::user()->operacoes()
                ->where('ticket', $ticket)
                ->where('conta_corretora_id', $conta_id)
                ->where('fechamento', $fechamento)
                ->where('precosaida', $preco_saida)
                ->first();
            if(!$operacao_obj){
                $operacao_obj = Auth::user()->operacoes()
                    ->where('abertura', $abertura)
                    ->where('conta_corretora_id', $conta_id)
                    ->where('fechamento', $fechamento)
                    ->where('precosaida', $preco_saida)
                    ->first();
                if(!$operacao_obj){//se estiver aberta eu atualizo
                    $n->conta = $conta_obj;
                    array_push($tradesFechados, $n);
                }
            }
        }
        return $tradesFechados;
    }

    public function importarCabecalho($corretora, $cabecalho)
    {
        $conta_obj = null;

        foreach($cabecalho as $key => $n ) {
            $conta       = $n['conta'];
            $currency    = $n['currency'];
           // $nome        = $n['nome'];
            $alavancagem = $n['alavancagem'];
            $conta_obj   = $this->contaService->getByCodigoOrCreate($conta, $corretora, $currency);
        }

        return $conta_obj;
    }

    public function importarDepositos($transferencias, $conta_obj, $regImport)
    {
        $depositosAdicionados = 0;
        $valorDepositos = 0;

        foreach($transferencias as $key => $n) {
            $tipo = $n['tipo'];
            $ticket = $n['ticket'];
            $data = $n['data'];
            $codigo = $n['codigo'];
            $valor = (double) $n['valor'];
            $capExt = $n['capExt'];

            if($this->depositoService->adicionarSeNaoExistir($tipo, $ticket, $data, $codigo, $valor, $conta_obj, $regImport, $capExt)){
                $depositosAdicionados = $depositosAdicionados + 1;
                $valorDepositos = $valorDepositos + $valor;
            }
        }
        return ['adicionados' => $depositosAdicionados, 'valor' => $valorDepositos];
    }

    public function validarTransferencias($transferencias, $conta_id)
    {
        $conta_obj = $this->contaService->getById($conta_id);
        $transferenciasAdicionar = [];

        foreach($transferencias as $key => $n) {
            $ticket = $n['ticket'];
            $data = $n['data'];
            $valor = (double) $n['valor'];

            $jaExiste = $this->depositoService->verificarSeExisteESimilar($ticket, $data, $valor, $conta_id);

            if(!$jaExiste[0]){
                $n->similar = $jaExiste[1];
                $n->conta   = $conta_obj;
                array_push($transferenciasAdicionar, $n);
            }
        }
        return $transferenciasAdicionar;
    }
}
