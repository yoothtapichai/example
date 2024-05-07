<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Log_login;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $input = $request->all();

        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (auth()->attempt(array('email' => $input['email'], 'password' => $input['password']))) {
            // dd($input['email']);
            $this->addlog($input['email'], "login success",'success');
            if (auth()->user()->type == 'admin') {
                return redirect()->route('noti');
            } else {
                return redirect()->route('noti');
            }
        } else {
            $this->addlog($input['email'], "Email-Address And Password Are Wrong.",'failed');
            return redirect()->route('login')
                ->with('error', 'Email-Address And Password Are Wrong.');
        }
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }



    // Google callback
    public function handleGoogleCallback()
    {
        $user = Socialite::driver('google')->user();

        $this->_registerOrLoginUser($user, 'google');

        // Return home after login
        return redirect()->route('leave.index');
    }


    // Facebook login
    public function redirectToFacebook()
    {

        return Socialite::driver('facebook')->redirect();
    }


    // Facebook callback
    public function handleFacebookCallback()
    {
        $user = Socialite::driver('facebook')->user();

        $this->_registerOrLoginUser($user, 'facebook');

        // Return home after login
        return redirect()->route('leave.index');
    }


    protected function _registerOrLoginUser($data, $provider)
    {
        // dd($data);
        $user = User::where('email', '=', $data->email)->first();
        if (!$user) {

            $user = new User();
            $user->name = $data->name;
            $user->email = $data->email;
            $user->provider = $provider;
            $user->provider_id = $data->id;
            $user->avatar = $data->avatar;
            $user->save();
            $this->addlog($data->email, "login success",'success');
            Auth::login($user);
        } else {

            // $this->addlog($data->email, "Email address is already in use.");
        }
    }

    function addlog($email, $msg,$status)
    {

   
        DB::table('log_login')->insert([
            'email' => $email,
            'user_location' => request()->userAgent(), // Access user agent directly
            'login_status' => $status, // Assuming a fixed value
            'login_time' => now(),
            'ip_address' => request()->ip(),
            'msg' => $msg,
        ]);
    }
}
