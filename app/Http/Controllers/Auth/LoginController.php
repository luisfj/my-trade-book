<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use GuzzleHttp\Client;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    function redirectPath(){
        if($this->guard()->user()->contasCorretora()->count() > 0){
            return '/home';
        }
        return '/contas-corretora';
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function logout(\Illuminate\Http\Request $request)
    {
        if(auth()->check() && auth()->user()->facebook_id && auth()->user()->token_facebook) {
            try {
                $user = auth()->user();

                $client = new Client();

                $res = $client->request('DELETE', ('https://graph.facebook.com/v6.0/me/permissions?access_token=' . Auth::user()->token_facebook), []);
/*
                if ($res->getStatusCode() == 200) { // 200 OK
                    $response_data = $res->getBody()->getContents();
                    dd($response_data);
                }
  dd($res->getBody());*/

                Auth::user()->remember_token = '';
                Auth::user()->token_facebook = '';
                Auth::user()->update();
              } catch(\Exception $e) {

              }
        }
        Auth::guard()->logout();
        $request->session()->invalidate();

        return redirect('/');
    }

    /**
     * Redirect the user to the Google authentication page.
    *
    * @return \Illuminate\Http\Response
    */
    public function redirectToGoogleProvider()
    {
        //return \Socialite::driver('google')->redirect();
        return \Socialite::with('Google')->with(["prompt" => "select_account"])->stateless()->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleGoogleProviderCallback()
    {
        try {
            //$user = \Socialite::driver('google')->user();
            $user = \Socialite::with('google')->stateless()->user();
        } catch (\Exception $e) {
            return redirect('/login');
        }
        // only allow people with @company.com to login
       /* if(explode("@", $user->email)[1] !== 'company.com'){
            return redirect()->to('/');
        }*/
        // check if they're an existing user
        $existingUser = User::where('email', $user->email)->first();
        if($existingUser){
            // log them in
            auth()->login($existingUser, true);
        } else {
            // create a new user
            $newUser                  = new User;
            $newUser->name            = $user->name;
            $newUser->email           = $user->email;
            $newUser->google_id       = $user->id;
            $newUser->avatar          = $user->avatar;
            $newUser->avatar_original = $user->avatar_original;
            $newUser->save();
            $existingUser = $newUser;
            auth()->login($newUser, true);
        }
        if($existingUser->contasCorretora()->count() > 0){
            return redirect()->to('/home');
        }
        return redirect()->to('/contas-corretora');
    }

    public function redirectToFacebookProvider()
    {
        return \Socialite::with('facebook')->stateless()->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleFacebookProviderCallback()
    {
        try {
            $user = \Socialite::with('facebook')->stateless()->user();
        } catch (\Exception $e) {
            return redirect('/login');
        }
        // check if they're an existing user
        $existingUser = User::where('email', $user->email)->first();
        if($existingUser){
            $existingUser->token_facebook = $user->token;
            $existingUser->update();
            // log them in
            auth()->login($existingUser, true);
        } else {
            // create a new user
            $newUser                  = new User;
            $newUser->name            = $user->name;
            $newUser->email           = $user->email;
            $newUser->facebook_id     = $user->id;
            $newUser->avatar          = $user->avatar;
            $newUser->avatar_original = $user->avatar_original;
            $newUser->token_facebook  = $user->token;

            $newUser->save();
            $existingUser = $newUser;
            auth()->login($newUser, true);
        }

        if($existingUser->contasCorretora()->count() > 0){
            return redirect()->to('/home');
        }
        return redirect()->to('/contas-corretora');
    }

}
