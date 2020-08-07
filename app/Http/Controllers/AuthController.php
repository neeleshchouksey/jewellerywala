<?php
namespace App\Http\Controllers;

use Auth;
use App\Http\Requests;
use App\User;
use Socialite;

class AuthController extends Controller
{
    public function login()
    {
        if(Auth::user()){
            if(Auth::user()->user_type==0){
                return redirect("admin/dashboard");
            }elseif (Auth::user()->user_type==1){
                return redirect("seller/dashboard");
            }else{
                return redirect("/");
            }
        }else{
            return view('admin.login');
        }
    }

    public function postLogin(Requests\LoginRequest $request)
    {
        if (User::login($request)) {
            flash()->success('Welcome to Jewellerywala.');
            if (Auth::user()->isAdmin()) {
                return redirect()->to('/admin/dashboard');
            } else {
                return redirect()->to('/admin/login');
            }
        }
        flash()->error('Invalid Login Credentials');
        
        return redirect()->back();
    }

    public function logOut()
    {
        $user_type = Auth::user()->user_type;
        dd($user_type);
        Auth::logout();
        if($user_type == 0){
            return redirect('/admin/login');
        }elseif($user_type == 1){
            return redirect("/seller/login-register");
        }else{
            return redirect("/login-register");
        }

    }

    /**
     * Redirect the user to the authentication page.
     *
     * @return Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
    public function handleProviderCallback($provider)
    {
        $provider_user = Socialite::driver($provider)->user();
        $user = $this->findUserByProviderOrCreate($provider, $provider_user);
        auth()->login($user);
        flash()->success('Welcome to Laraspace.');

        return redirect()->to('/admin');
    }

    private function findUserByProviderOrCreate($provider, $provider_user)
    {
        $user = User::where($provider . '_id', $provider_user->token)
            ->orWhere('email', $provider_user->email)
            ->first();
        if (!$user) {
            $user = User::create([
                'name' => $provider_user->name,
                'email' => $provider_user->email,
                $provider . '_id' => $provider_user->token
            ]);
        } else {
            // Update the token on each login request
            $user[$provider . '_id'] = $provider_user->token;
            $user->save();
        }

        return $user;
    }
}
