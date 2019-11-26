<?php

namespace App\Http\Controllers\Posts;

use App\Models\Post;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\PostNotification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class PostController extends Controller
{
    private $post;
    private $users;

    public function __construct(Post $post, User $user)
    {
        $this->post = $post;
        $this->users = $user;
    }

    public function index(){
        $posts = $this->post->orderBy('created_at', 'desc')->paginate(10);

        return view('modulos.admin.posts.index', compact('posts'));
    }

    public function show($id){
        $post = $this->post->with('opcoesEnquete')->find($id);

        return view('modulos.admin.posts.show', compact('post'));
    }

    public function update(Request $request, $id){
        $nome = $request->nome;
        $detalhamento = $request->detalhamento;
        $opcoes = $request->opcao;

        $dados = $request->all();

        array_splice($dados, 0, 2);
        if($nome){
            $indexNome = array_search('nome', array_keys($dados));
            array_splice($dados, $indexNome, 1);
            $indexDet = array_search('detalhamento', array_keys($dados));
            array_splice($dados, $indexDet, 1);
            $indexDet = array_search('opcao', array_keys($dados));
            array_splice($dados, $indexDet, 1);
        }

        try {
            DB::beginTransaction();

            $post = $this->post->find($id);

            $post->update($dados);
            $tipo = $dados['tipo'];

            if($tipo == 'E' && !$nome){
                throw new Exception("Quando for enquete, deve ter ao menos 1 opção cadastrada!");
            }

            if($tipo == 'E'){
                //remove o que foi excluido
                $post->opcoesEnquete()->whereNotIn('id', $opcoes)->delete();

                foreach($nome as $key => $n ) {
                    $opc = $post->opcoesEnquete()->find($opcoes[$key]);
                    if($opc){
                        $opc->update([
                            'nome' => $n,
                            'detalhamento' => $detalhamento[$key],
                        ]);
                    } else {
                        $post->opcoesEnquete()->create([
                            'nome' => $n,
                            'detalhamento' => $detalhamento[$key],
                        ]);
                    }
                }
            }

            DB::commit();

            session()->flash('success', [
                'messages' => 'Post/Enquete atualizado com sucesso!',
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            session()->flash('error', [
                'messages' => $th->getMessage(),
            ]);
        }

        return redirect()->route('posts.show', $id);
    }

    public function create(Request $request){
        $nome = $request->nome;
        $detalhamento = $request->detalhamento;

        $dados = $request->all();

        array_splice($dados, 0, 1);
        if($nome){
            $indexNome = array_search('nome', array_keys($dados));
            array_splice($dados, $indexNome, 1);
            $indexDet = array_search('detalhamento', array_keys($dados));
            array_splice($dados, $indexDet, 1);
        }
        try {
            DB::beginTransaction();

            $post = Auth::user()->posts()->create($dados);
            $tipo = $dados['tipo'];

            if($tipo == 'E' && !$nome){
                throw new Exception("Quando for enquete, deve ter ao menos 1 opção cadastrada!");
            }

            if($tipo == 'E'){
                foreach($nome as $key => $n ) {
                    $post->opcoesEnquete()->create([
                        'nome' => $n,
                        'detalhamento' => $detalhamento[$key],
                    ]);
                }
            }
            /* será util no update else {
                $post->opcoesEnquete()->delete();
            }*/

            DB::commit();

            session()->flash('success', [
                'messages' => 'Post/Enquete criado com sucesso!',
            ]);

            $users = $this->users->all();
            Notification::send($users, new PostNotification($post));
        } catch (\Throwable $th) {
            DB::rollback();
            session()->flash('error', [
                'messages' => $th->getMessage(),
            ]);
        }

        return redirect()->route('posts.index');
    }
}
