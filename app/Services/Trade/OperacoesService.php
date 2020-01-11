<?php

namespace App\Services\Trade;

use App\Models\Operacoes;
use Exception;
use Illuminate\Support\Facades\Auth;

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

    public function importarOperacoes($corretora, $cabecalho, $transferencias, $openTrades, $closedTrades)
    {
        $conta       = '';
        $alavancagem = '';

        $conta_obj = $this->importarCabecalho($corretora, $cabecalho);

        $depadd = $this->importarDepositos($transferencias, $conta_obj);
        $depositosAdicionados = $depadd['adicionados'];
        $valorDepositos       = $depadd['valor'];

        $tradesFechados = $this->importarTradesFechados($closedTrades, $conta, $corretora, $alavancagem, $conta_obj);
        $operacoesAdicionadas = $tradesFechados['operacoesAdicionadas'];
        $operacoesAbertas     = $tradesFechados['operacoesAbertas'];
        $operacoesFechadas    = $tradesFechados['operacoesFechadas'];
        $valorOperacoes       = $tradesFechados['valorOperacoes'];

        $tradesAbertos = $this->importarTradesAbertos($openTrades, $conta, $corretora, $alavancagem, $conta_obj);
        $operacoesAdicionadas = $operacoesAdicionadas + $tradesAbertos['operacoesAdicionadas'];
        $operacoesAbertas     = $operacoesAbertas + $tradesAbertos['operacoesAbertas'];


        //atualizar o saldo da conta
        $valorDepositos = number_format($valorDepositos, 2);
        $valorOperacoes = number_format($valorOperacoes, 2);

        $this->contaService->atualizarSaldoContaPorOperacoes($conta_obj, $valorOperacoes, $operacoesAbertas, $operacoesFechadas);

        $success = "Importação concluida! Foram adicionados " . $operacoesAdicionadas . ' operações e ' . $depositosAdicionados . ' transferencias.';

        return $success;
    }

    public function importarTradesAbertos($openTrades, $conta, $corretora, $alavancagem, $conta_obj)
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
                        'account'        => $conta,
                        'corretoranome'  => $corretora,
                        'alavancagem'    => $alavancagem,
                        'ticket'         => $ticket,
                        'abertura'       => $abertura,
                        'precoentrada'   => $preco_entrada,
                        'tipo'           => $tipo,
                        'lotes'          => $contratos,
                        'moeda_id'       => $conta_obj->moeda->id,
                        'instrumento_id' => $instrumento_obj->id,
                        'conta_corretora_id' => $conta_obj->id,
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

    public function importarTradesFechados($closedTrades, $conta, $corretora, $alavancagem, $conta_obj)
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
            $pontos        = (int) $n['pontos'];
            $tempo_operacao_dias  = (int) $n['tempo_operacao_dias'];
            $tempo_operacao_horas = $n['tempo_operacao_horas'];

            $instrumento_obj = $this->instrumentoService->getBySiglaOrCreate($instrumento);
            $operacao_obj = Auth::user()->operacoes()->where('ticket', $ticket)->where('conta_corretora_id', $conta_obj->id)->first();
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
                        ]);
                    $operacoesAdicionadas = $operacoesAdicionadas + 1;
                    $operacoesFechadas    = $operacoesFechadas + 1;
                    $valorOperacoes       = $valorOperacoes + $resultado;
                }
            } else {
                Auth::user()->operacoes()->create(
                    [
                        'account'        => $conta,
                        'corretoranome'  => $corretora,
                        'alavancagem'    => $alavancagem,
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

    public function importarDepositos($transferencias, $conta_obj)
    {
        $depositosAdicionados = 0;
        $valorDepositos = 0;

        foreach($transferencias as $key => $n) {
            $ticket = $n['ticket'];
            $data = $n['data'];
            $codigo = $n['codigo'];
            $valor = (double) $n['valor'];

            if($this->depositoService->adicionarSeNaoExistir($ticket, $data, $codigo, $valor, $conta_obj)){
                $depositosAdicionados = $depositosAdicionados + 1;
                $valorDepositos = $valorDepositos + $valor;
            }
        }
        return ['adicionados' => $depositosAdicionados, 'valor' => $valorDepositos];
    }
}
