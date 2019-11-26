<?php

namespace App\Http\Controllers\Notifications;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    private $posts;

    public function __construct(Post $post)
    {
        $this->posts = $post;
    }

    public function notifications(Request $request)
    {
        $notifications = $request->user()->unreadNotifications;

        return response()->json(compact('notifications'));
    }

    public function markAsRead(Request $request)
    {
        try {
            $notification = $request->user()
                        ->notifications()
                        ->where('id', $request->id)
                        ->first();
            if($notification)
                $notification->markAsRead();
            $success = 'Notificação lida!';

            return response()->json(compact('success'));
        } catch (\Throwable $th) {
            $error = $th->getMessage();
            return response()->json(compact('error'));
        }
    }

    public function markAllAsRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();
    }

    public function painelNotificacoes(Request $request){
        $notifications = $request->user()->notifications()
                    ->orderBy(DB::raw('ISNULL(read_at)'), 'DESC')
                    ->orderBy('read_at', 'desc')
                    ->orderBy('created_at', 'desc')->paginate(10);

        return view('modulos.comunicacao.painelDeNotificacoes', compact('notifications'));
    }

    public function getPostFromNotification(Request $request, $idnotification){
        $notificacao = $request->user()->notifications()->find($idnotification);
        if(!$notificacao){
            $erro = 'Esta notificação não foi encontrada';
            return response()->json(compact('erro'));
        }
        $post = $this->posts->with('opcoesEnquete')->with('votosUsuario.opcao')->find($notificacao->data['post']['id']);

        if(!$request->user()->is_admin()){
            if(!$post->exibir){
                $erro = 'Este post não é acessivel';
                $notification = $request->user()->notifications().find($request->idnotificacao)->delete();
                return response()->json(compact('erro'));
            }
        }
        return response()->json(compact('post'));
    }
}
