<?php

namespace App\Services\BalancoMensal;

use App\Helpers\DatasHelper;
use App\Models\ContaFechamento;
use App\Models\FechamentoMes;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class FechamentoMesService
{
    private $repository;

    public function __construct(FechamentoMes $repository)
    {
        $this->repository       = $repository;
    }

    public function create($dados)
    {
        $dados['usuario_id'] = Auth::user()->id;
        $mes_ano = DatasHelper::converterMesAnoParaData($dados['mes_ano']);
        $dados['mes_ano'] = $mes_ano;
        $this->repository->create($dados);
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
        return $this->repository->where('usuario_id', Auth::user()->id)->get();
    }

    public function getById($id)
    {
        return $this->repository->with('conta_fechamento')->where('usuario_id', Auth::user()->id)->findOrFail($id);
    }

    public function getByIdOrNull($id)
    {
        return $this->repository->with('conta_fechamento')->where('usuario_id', Auth::user()->id)->find($id);
    }

    public function getByIdOrFirst($conta_id)
    {
        $conta = $this->repository->where('usuario_id', Auth::user()->id)->find($conta_id);
        if($conta)
            return $conta;
        return $this->getAllByUser()->first();
    }

    public function getFechamentosDoMes($mes_ano)
    {
        $mes_ano = DatasHelper::converterMesAnoParaData($mes_ano);

        return $this->repository->with('conta_fechamento')
                ->where('usuario_id', Auth::user()->id)
                ->whereDate('mes_ano', $mes_ano)
                ->get();
    }

    public function deleteDoMesIdDiferenteDe($mes_ano, $ids)
    {
        $mes_ano = DatasHelper::converterMesAnoParaData($mes_ano);

        $this->repository
                ->where('usuario_id', Auth::user()->id)
                ->whereDate('mes_ano', $mes_ano)
                ->whereNotIn('id', $ids)->delete();
    }

    public function getSaldoAnterior($mes_ano)
    {
        $mes_ano = DatasHelper::converterMesAnoParaData($mes_ano);

        return $this->repository->with('conta_fechamento')
                ->where('usuario_id', Auth::user()->id)
                ->whereDate('mes_ano', '<', $mes_ano)
                ->get()->sum('resultado_mes');
    }

    public function getDadosGraficoEvolucaoSaldo($filtros)
    {
        $tipoSelecionado = $filtros['tipoSelecionado'];

        if($tipoSelecionado === 'mensal'){
            $dados = $this->repository
                //->selectRaw('fechamento_mes.mes_ano, sum(fechamento_mes.resultado_mes) as resultado_mes, sum(fechamento_mes.receitas) as receitas, sum(fechamento_mes.despesas) as despesas, (select sum(f.resultado_mes) from fechamento_mes f where f.usuario_id = fechamento_mes.usuario_id and YEAR(f.mes_ano) <= fechamento_mes.mes_ano) as saldo')
                ->selectRaw('mes_ano, sum(resultado_mes) as resultado_mes, sum(receitas) as receitas, sum(despesas) as despesas, sum(resultado_mes) as saldo')
                ->where('usuario_id', Auth::user()->id)
                ->when($filtros,
                        function ($query, $filtros) {
                            return $this->wherePeriodo($query, $filtros);
                        })
                ->groupBy('mes_ano')->orderBy('mes_ano')
                ->get();

            $retorno = [['Mês', 'Resultado', 'Receitas', 'Despesas', 'Saldo'],];

            if(!count($dados)){
                array_push($retorno, ['Sem Dados', 0, 0, 0, 0]);
                return $retorno;
            }

            foreach ($dados as $key => $res) {
                array_push($retorno,
                    [DatasHelper::converterDataParaMesTracoAno_String($res->mes_ano), (float)$res->resultado_mes, (float)$res->receitas, (float)$res->despesas, (float)$res->saldo]
                );
            }

            return $retorno;
        } else if($tipoSelecionado === 'anual'){
            $dados = $this->repository
                ->selectRaw('sum(resultado_mes) as resultado_mes, sum(receitas) as receitas, sum(despesas) as despesas, YEAR(mes_ano) ano, sum(resultado_mes) as saldo')
                ->where('usuario_id', Auth::user()->id)
                ->when($filtros,
                        function ($query, $filtros) {
                            return $this->wherePeriodo($query, $filtros);
                        })
                ->groupBy('ano')->orderBy('ano')
                ->get();

            $retorno = [['Ano', 'Resultado', 'Receitas', 'Despesas', 'Saldo'],];

            if(!count($dados)){
                array_push($retorno, ['Sem Dados', 0, 0, 0, 0]);
                return $retorno;
            }

            foreach ($dados as $key => $res) {
                array_push($retorno,
                    ['' . $res->ano, (float)$res->resultado_mes, (float)$res->receitas, (float)$res->despesas, (float)$res->saldo]
                );
            }

            return $retorno;
        }
    }

    public function getDadosGridFechamentoFiltrado($filtros)
    {
        $contaSelecionado = !$filtros['contaSelecionado'] || $filtros['contaSelecionado'] == 'todas' ? null : $filtros['contaSelecionado'];

        $dados = $this->repository->with('conta_fechamento')
            ->where('usuario_id', Auth::user()->id)
            ->when($filtros,
                    function ($query, $filtros) {
                        return $this->wherePeriodo($query, $filtros);
                    })
            ->when($contaSelecionado, function($query, $contaSelecionado){
                return $query->where('conta_fechamento_id', $contaSelecionado);
            })
            ->orderBy('mes_ano', 'desc')->orderBy('conta_fechamento_id')
            ->get();

        return $dados;
    }

    public function getDadosGraficoFiltrado($filtros)
    {
        $tipoSelecionado = $filtros['tipoSelecionado'];

        if($tipoSelecionado === 'mensal'){
            $dados = $this->repository
                ->selectRaw('mes_ano, sum(resultado_mes) as resultado_mes, sum(receitas) as receitas, sum(despesas) as despesas')
                ->where('usuario_id', Auth::user()->id)
                ->when($filtros,
                        function ($query, $filtros) {
                            return $this->wherePeriodo($query, $filtros);
                        })
                ->groupBy('mes_ano')->orderBy('mes_ano')
                ->get();

            $retorno = [['Mês', 'Resultado', 'Receitas', 'Despesas'],];

            if(!count($dados)){
                array_push($retorno, ['Sem Dados', 0, 0, 0]);
                return $retorno;
            }

            foreach ($dados as $key => $res) {
                array_push($retorno,
                    [DatasHelper::converterDataParaMesTracoAno_String($res->mes_ano), (float)$res->resultado_mes, (float)$res->receitas, (float)$res->despesas]
                );
            }

            return $retorno;
        } else if($tipoSelecionado === 'anual'){
            $dados = $this->repository
                ->selectRaw('sum(resultado_mes) as resultado_mes, sum(receitas) as receitas, sum(despesas) as despesas, YEAR(mes_ano) ano')
                ->where('usuario_id', Auth::user()->id)
                ->when($filtros,
                        function ($query, $filtros) {
                            return $this->wherePeriodo($query, $filtros);
                        })
                ->groupBy('ano')->orderBy('ano')
                ->get();

            $retorno = [['Ano', 'Resultado', 'Receitas', 'Despesas'],];

            if(!count($dados)){
                array_push($retorno, ['Sem Dados', 0, 0, 0]);
                return $retorno;
            }

            foreach ($dados as $key => $res) {
                array_push($retorno,
                    ['' . $res->ano, (float)$res->resultado_mes, (float)$res->receitas, (float)$res->despesas]
                );
            }

            return $retorno;
        } else if($tipoSelecionado === 'mensal/ano'){
            $dados = $this->repository
                ->selectRaw('mes_ano, sum(resultado_mes) as resultado_mes, MONTH(mes_ano) as mes, YEAR(mes_ano) ano')
                ->where('usuario_id', Auth::user()->id)
                ->when($filtros,
                        function ($query, $filtros) {
                            return $this->wherePeriodo($query, $filtros);
                        })
                ->groupBy('mes_ano', 'mes', 'ano')->orderBy('mes')->orderBy('ano')
                ->get();

            $cabecalho = ['Mês'];

            if(!count($dados))
                return [['Mês', ''], ['Sem Dados', 0]];
            $retorno = [];
            $linha = [];
            $mes = '';
            foreach ($dados as $key => $res) {
                if (!in_array('' . $res->ano, $cabecalho)) {
                    array_push($cabecalho, '' . $res->ano);
                }
                if($mes != $res->mes){
                    if($linha)
                        array_push($retorno, $linha);
                    $linha = [DatasHelper::converterDataParaMes_String($res->mes_ano)];
                    $mes = $res->mes;
                }
                $linha['' . $res->ano] = (float)$res->resultado_mes;
            }
            array_push($retorno, $linha);

            $saida = [];
            array_push($saida, $cabecalho);

            foreach ($retorno as $key => $ret) {
                $mes_lin = [$ret[0]];
                foreach ($cabecalho as $key => $cab) {
                    if($cab != 'Mês'){
                        array_push($mes_lin, array_key_exists($cab, $ret) ? $ret[$cab] : null );
                    }
                }
                array_push($saida, $mes_lin);
            }

            return $saida;
            /*return [
                ['Mês', '2018', '2019', '2020'],
                ['Jan', -100, 200, 200],
                ['Fev', 1170, 460, 250],
                ['Mar', 60, -120, null],
                ['Abr', -100, 40, null],
                ['Mai', 110, 460, null],
                ['Jun', 660, 120, null],
                ['Jul', -1000, 400, null],
                ['Ago', -170, 460, null],
                ['Set', 60, 10, null],
                ['Out', 200, 40, null],
                ['Nov', 110, 40, null],
                ['Dez', -600, 112, null]
            ];*/
        }
    }

    private function wherePeriodo($query, $filtros){
        //'mes_ano', '2019'
        $periodoSelecionado = $filtros['periodoSelecionado'];
        $dataInicial = $filtros['dataInicial'];
        $dataFinal = $filtros['dataFinal'];

        $hoje = Carbon::now();
        $hoje->day = 1;

        switch ($periodoSelecionado) {
            case '12_meses':
                $data_filtro = $hoje->subMonth(12);
                $query = $query->whereYear('mes_ano', '>=', $data_filtro->year)->whereMonth('mes_ano', '>', $data_filtro->month);
                break;
            case '6_meses':
                $data_filtro = $hoje->subMonth(6);
                $query = $query->whereYear('mes_ano', '>=', $data_filtro->year)->whereMonth('mes_ano', '>', $data_filtro->month);
                break;
            case 'ano_atual':
                $query = $query->whereYear('mes_ano', '=', $hoje->year);
                break;
            case 'ano_anterior':
                $data_filtro = $hoje->subYear(1);
                $query = $query->whereYear('mes_ano', '=', $data_filtro->year);
                break;
            case 'personalizado':
                if($dataInicial)
                    $query = $query->whereDate('mes_ano', '>=', $dataInicial);
                if($dataFinal)
                    $query = $query->whereDate('mes_ano', '<=', $dataFinal);
                break;
        }
        return $query;
    }
}
