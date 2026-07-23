<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected function attemptLogin(Request $request)
    {
        Log::info('Login Request', [
            'email' => $request->email,
        ]);

        $credentials = $request->only($this->username(), 'password');

        $result = $this->guard()->attempt($credentials, $request->boolean('remember'));

        Log::info('Login Result', [
            'success' => $result,
            'user' => Auth::id(),
        ]);

        if ($result) {
            $user = $this->guard()->user();

            // Membership check only for normal users (role bukan admin)
            if (($user?->role ?? null) !== 'admin') {
                if (!$user?->hasPremiumAccess()) {
                    $this->guard()->logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    throw new \Illuminate\Auth\AuthenticationException('Akun Anda sedang menunggu persetujuan Administrator.');
                }
            }

            if (($user?->role ?? null) === 'admin') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('admin.login')
                    ->withErrors(['email' => 'Akun Administrator harus login melalui halaman Admin.']);
            }

            return true;
        }

        Log::warning('Login Failed');


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
