<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\PhoneHelper;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    // protected function credentials(Request $request)
    // {
    //     $phoneNumber = $request->get('phone_number');

    //     // Mengonversi nomor telepon ke format E.164 Indonesia
    //     $formattedPhone = PhoneHelper::formatToE164Indonesia($phoneNumber);

    //     return [
    //         'phone_number' => $formattedPhone,
    //         'password' => $request->get('password'),
    //     ];
    // }
    // public function username()
    // {
    //     return 'phone_number';
    // }
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

    protected function sendFailedLoginResponse(Request $request)
    {
        $errors = [$this->username() => trans('auth.failed')];

        // Check if the email exists
        if (!Auth::validate([$this->username() => $request->{$this->username()}, 'password' => 'invalid'])) {
            $errors = [$this->username() => 'Email atau Kata sandi Salah.'];
        } else {
            $errors = ['password' => 'Email atau Kata sandi Salah.'];
        }

        // Return back with error
        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->with('login_error', implode('<br>', $errors));
    }

    protected function authenticated(Request $request, $user) {
        if ($user->is_active != 1) {
            Auth::logout();

            return back()->with([
                'account_deactivated' => 'Your account is deactivated! Please contact with Super Admin.'
            ]);
        }
        if (is_null($user->email_verified_at)) {
            return redirect()->route('otp.show'); // Redirect ke halaman OTP jika belum verifikasi
        }


        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
