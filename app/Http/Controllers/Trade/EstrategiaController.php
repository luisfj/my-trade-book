<?php

namespace App\Http\Controllers\Trade;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Models\Estrategia;
use App\Services\Trade\EstrategiaService;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Integer;

class EstrategiaController extends Controller
{
    private $service;
    private $repository;

    public function __construct(Estrategia $repository, EstrategiaService $service)
    {
        $this->service = $service;
        $this->repository = $repository;
    }

    public function index()
    {
        $estrategias = $this->repository->where('usuario_id', Auth::user()->id)->paginate(15);
        return view('modulos.trade.listaEstrategias', compact('estrategias'));
    }

    public function remover(int $estrategia, Request $request)
    {
        try {
            DB::beginTransaction();
            $this->service->delete($estrategia);
            DB::commit();
            return redirect()->back()->with('success', [
                'messages' => 'Estratégia removida com sucesso!',
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', [
                'messages' => $th->getMessage(),
            ]);
        }
    }

    public function editar(int $estrategia_id){
        try {
            $estrategia = $this->service->getById($estrategia_id);

            return response()->json(compact(['estrategia']));
        } catch (\Throwable $th) {
            $erro = null;
            $errors = $th;
            if(isset($th->errorInfo) && is_array($th->errorInfo) && count($th->errorInfo) >= 3)
                $erro = $th->errorInfo[2];
            return response()->json(['error' => ($erro ? $erro : $errors)]);
        }
    }

    public function atualizar(Request $request, int $estrategia_id){
        try {
            $compoemCapAlocExt = $request->capExt;
            $dados = $request->except(['_token', '_method']);
            $dados['ativa'] = $request->has('ativa');

            DB::beginTransaction();
            $this->service->update($dados, $estrategia_id);
            DB::commit();

            session()->flash('success', [
                'messages' => 'Estratégia atualizada com sucesso!',
            ]);
            return response()->json(['success' => 'Adicionado']);
        } catch (\Throwable $th) {
            DB::rollback();
            $erro = null;
            $errors = $th;
            if(isset($th->errorInfo) && is_array($th->errorInfo) && count($th->errorInfo) >= 3)
                $erro = $th->errorInfo[2];
            return response()->json(['error' => ($erro ? $erro : $errors)]);
        }
    }

    public function salvar(Request $request){
        try {
            $dados = $request->except(['_token', '_method']);
            DB::beginTransaction();
            $dados['usuario_id'] = Auth::user()->id;
            $dados['ativa'] = $request->has('ativa');
            $estrategia = $this->service->create($dados);
            DB::commit();
            session()->flash('success', [
                'messages' => 'Estratégia adicionada com sucesso!',
            ]);

            return response()->json(['success' => 'Adicionado']);
        } catch (\Throwable $th) {
            DB::rollback();
            $erro = null;
            $errors = $th;
            if(isset($th->errorInfo) && is_array($th->errorInfo) && count($th->errorInfo) >= 3)
                $erro = $th->errorInfo[2];
            return response()->json(['error' => ($erro ? $erro : $errors)]);
        }
    }

    public function estrategiasAtivas(){
        $estrategiasAtivas = $this->service->selectBoxAtivosList();
        return response()->json(compact('estrategiasAtivas'));
    }
}
