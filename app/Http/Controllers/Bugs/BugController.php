<?php

namespace App\Http\Controllers\Bugs;

use App\Models\Bugs;
use App\Http\Controllers\Controller;
use App\Http\Requests\Bugs\BugStoreRequest;
use App\Models\Configuracoes;
use Illuminate\Http\Request;
use \Auth;
use DateTime;
use Exception;

class BugController extends Controller
{
    private $bug;
    private $configuracoes;

    public function __construct(Bugs $bug, Configuracoes $configuracoes)
    {
        $this->configuracoes = $configuracoes;
        $this->bug = $bug;
    }

    public function index(){
        $bugs = Auth::user()->bugs()->paginate(10);

        return response()->json(compact('bugs'));
    }

    public function show($id){
        if(!Auth::user()->is_admin())
            $bug = Auth::user()->bugs()->with('messages.autor')->find($id);
        else
            $bug = $this->bug->with('messages.autor')->find($id);

        return response()->json(compact('bug'));
    }

    public function store(BugStoreRequest $request){
        $bug = $request->user()->bugs()->create($request->all());
        $tipo = $bug->tipo;
        return response()->json($tipo . " relatado com sucesso!");

       // $author = $bug->post->author;
       // $author->notify(new PostCommented($bug));
    }

    public function addMessage(Request $request){
        try {
            //code...
            if(!Auth::user()->is_admin())
                $bug = Auth::user()->bugs()->with('messages.autor')->find($request->bug_id);
            else
                $bug = $this->bug->with('messages.autor')->find($request->bug_id);
            $dtResolvido = $request->is_resolvido ? new DateTime('NOW') : null;

            if($dtResolvido && Auth::user()->is_admin()){
                $bug->data_resolucao = $dtResolvido;
                $bug->save();
            }
            $bug->messages()->create(
                [
                    'descricao' => $request->mensagem,
                    'data_resolucao' => $dtResolvido,
                    'bug_id' => $request->bug_id,
                    'autor_id' => Auth::user()->id,
                ]
            );

            $bug = $this->bug->with(['autor', 'messages.autor'])->find($bug->id);
            $success = "Mensagem adicionada com sucesso!";

            return response()->json(compact(['bug', 'success']));
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function marcarComoVerificada(Request $request){
        try {
            //code...
            if(!Auth::user()->is_admin())
                throw new Exception("Sem autorização");

            $bug = $this->bug->find($request->bug_id);

            $bug->data_verificacao = new DateTime('NOW');
            $bug->save();

            $config = $this->configuracoes->where('user_id', '=', Auth::User()->id)->firstOrCreate(['user_id'=> Auth::User()->id]);

            $bug->messages()->create(
                [
                    'descricao' => $config->descricao_verificar_mensagem,
                    'bug_id' => $request->bug_id,
                    'autor_id' => Auth::user()->id,
                ]
            );

            $bug = $this->bug->with(['autor', 'messages.autor'])->find($bug->id);
            $success = "Verificação ok!";

            return response()->json(compact(['bug', 'success']));
        } catch (\Throwable $th) {
            dd($th);
        }
    }
}
