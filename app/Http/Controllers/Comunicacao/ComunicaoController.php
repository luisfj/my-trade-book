<?php

namespace App\Http\Controllers\Comunicacao;

use App\Http\Controllers\Controller;
use App\Models\Bugs;
use \Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComunicaoController extends Controller
{
    private $bugs_tb;

    public function __construct(Bugs $bugs_tb)
    {
        $this->bugs_tb = $bugs_tb;
    }

    public function index(){
        if(!Auth::user()->is_admin())
            $bugs = Auth::user()->bugs()->orderBy('data_verificacao', 'desc')->with('messages.autor')->paginate(10);
        else
            $bugs = $this->bugs_tb
                ->orderBy(DB::raw('ISNULL(data_verificacao)'), 'DESC')
                ->orderBy('data_verificacao', 'desc')
                ->orderBy('data_resolucao', 'desc')
                ->orderBy('created_at', 'desc')->with('messages.autor')->paginate(10);

        return view('modulos.comunicacao.painelDeComunicacao', compact('bugs'));
    }
}
