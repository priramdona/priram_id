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
    protected function credentials(Request $request)
    {
        $phoneNumber = $request->get('phone_number');

        // Mengonversi nomor telepon ke format E.164 Indonesia
        $formattedPhone = PhoneHelper::formatToE164Indonesia($phoneNumber);
        dd($formattedPhone,  $request->get('password'));
        return [
            'phone_number' => $formattedPhone,
            'password' => $request->get('password'),
        ];
    }

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

    protected function authenticated(Request $request, $user) {
        // if ($user->is_active != 1) {
        //     Auth::logout();

        //     return back()->with([
        //         'account_deactivated' => 'Your account is deactivated! Please contact with Super Admin.'
        //     ]);
        // }

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
