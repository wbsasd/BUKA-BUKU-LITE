<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected function attemptLogin(Request $request)
    {
        $credentials = $request->only($this->username(), 'password');

        if ($this->guard()->attempt($credentials, $request->boolean('remember'))) {
            $user = $this->guard()->user();

            // Membership check only for normal users (role bukan admin)
            if (($user?->role ?? null) !== 'admin') {
                if (($user?->membership_status ?? null) !== 'active') {
                    $this->guard()->logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    throw new \Illuminate\Auth\AuthenticationException('Akun Anda sedang menunggu persetujuan Administrator.');
                }
            }

            return true;
        }

        return false;
    }

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

    /**
     * Where to redirect users after login.

     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}
