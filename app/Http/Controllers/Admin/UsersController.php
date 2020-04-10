<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Artisan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    private $users_tb;

    public function __construct(User $users_tb)
    {
        $this->users_tb = $users_tb;
    }

    public function index(){
        if(!Auth::user()->is_admin())
            throw new Exception("Sem autorização!");
        else
            $users = $this->users_tb
                ->orderBy('role', 'asc')
                ->orderBy('name', 'asc')
                ->orderBy('created_at', 'asc')->paginate(10);

        return view('modulos.admin.listaUsuarios', compact('users'));
    }

    public function updateRole(Request $request){
        if(!Auth::User()->is_super_admin())
            throw new Exception("Não possui autorização!");

        $user = $this->users_tb->find($request->user_id);
        $user->role = $request->new_role;
        $user->save();

        return redirect()->route('users.index');
    }

    public function loginComOutroUsuario(Request $request){
        if(!Auth::User()->is_super_admin())
            throw new Exception("Não possui autorização!");

        $user = $this->users_tb->find($request->user_id);

        Auth::login($user);

        return redirect()->route('home');
    }

    public function rodarMigrations(Request $request)
    {
        if(!Auth::user()->is_admin())
            throw new Exception("Sem autorização!");
        else {
            $exitCode = null;
            try {
                $exitCode = Artisan::call('migrate');

                session()->flash('success', [
                    'messages' => 'migrate:install executado com sucesso!'.$exitCode,
                ]);
            } catch (Exception $e) {
                session()->flash('error', [
                    'messages' => [$e, $exitCode],
                ]);
            }

            return redirect()->route('admin.index.migration');
        }
    }

    public function rollbackMigrations(Request $request)
    {
        if(!Auth::user()->is_admin())
            throw new Exception("Sem autorização!");
        else {
            $exitCode = null;
            try {
                $exitCode = Artisan::call('migrate:rollback');

                session()->flash('success', [
                    'messages' => 'migrate:rollback executando com sucesso!'.$exitCode,
                ]);
            } catch (Exception $e) {
                session()->flash('error', [
                    'messages' => [$e, $exitCode],
                ]);
            }

            return redirect()->route('admin.index.migration');
        }
    }

    public function seedsMigrations(Request $request)
    {
        if(!Auth::user()->is_admin())
            throw new Exception("Sem autorização!");
        else {
            $exitCode = null;
            try {
                $exitCode = Artisan::call('migrate --seed');

                session()->flash('success', [
                    'messages' => 'migrate --seed executando com sucesso!'.$exitCode,
                ]);
            } catch (Exception $e) {
                session()->flash('error', [
                    'messages' => [$e, $exitCode],
                ]);
            }

            return redirect()->route('admin.index.migration');
        }
    }
}
