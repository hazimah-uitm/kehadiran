<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    protected function attemptLogin(Request $request)
    {
        $credentials = $this->credentials($request);
        $user = User::where($this->username(), $credentials[$this->username()])->first();

        if ($user) {
            if ($user->publish_status !== 'Aktif') {
                return false;
            }

            return $this->guard()->attempt(
                $credentials, $request->filled('remember')
            );
        }

        return false;
    }

    public function authenticated(Request $request, $user)
    {
        return redirect()->route('home')->with('success', 'Anda telah berjaya log masuk!');
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda telah berjaya log keluar!');
    }

    public function username()
    {
        return 'staff_id';
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        $user = User::where($this->username(), $request->input($this->username()))->first();

        if ($user && $user->publish_status !== 'Aktif') {
            return redirect()->back()
                ->withInput($request->only($this->username(), 'remember'))
                ->withErrors([
                    $this->username() => 'Akaun anda tidak aktif. Sila hubungi admin sistem.',
                ]);
        }

        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors([
                $this->username() => trans('auth.failed'),
            ]);
    }
}
