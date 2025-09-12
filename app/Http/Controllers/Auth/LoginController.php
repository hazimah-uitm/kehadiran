<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\EmailVerificationToken;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Support\Str;

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

            if (is_null($user->email_verified_at)) {
                return false;
            }

            return $this->guard()->attempt(
                $credentials,
                $request->filled('remember')
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

        if ($user && is_null($user->email_verified_at)) {
            return redirect()->back()
                ->withInput($request->only($this->username(), 'remember'))
                ->withErrors([
                    $this->username() => 'Emel anda belum disahkan. Sila semak inbox anda untuk pautan pengesahan.',
                ]);
        }

        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors([
                $this->username() => trans('auth.failed'),
            ]);
    }

    public function showForm()
    {
        return view('auth.firsttimelogin');
    }

    public function sendLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Emel anda tidak didaftarkan dalam sistem. Sila hubungi moderator (Hazimah - +6082678118).'
            ]);
        }

        if ($user->email_verified_at) {
            return back()->withErrors([
                'email' => 'Akaun anda telah disahkan. Sila log masuk seperti biasa.'
            ]);
        }

        // Create reset token and send notification
        // $token = Password::broker()->createToken($user);
        // $user->notify(new ResetPasswordNotification($token, true));

        $token = Str::random(40);

        EmailVerificationToken::updateOrCreate(
            ['user_id' => $user->id],
            ['token' => $token]
        );

        $user->notify(new EmailVerificationNotification($user, $token));

        return back()->with('status', 'Pautan pengesahan emel telah dihantar semula ke emel anda. Sila semak inbox anda.');
    }
}
