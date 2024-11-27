<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmailOtp;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class OtpVerificationController extends Controller
{
    public function show()
    {
        return view('auth.verify-otp');
    }

    public function sendOtp()
    {
        $user = Auth::user();
        $now = Carbon::now();

        $otpRecord = EmailOtp::firstOrNew(['user_id' => $user->id]);
        $remainingSeconds = $now->diffInSeconds($otpRecord->can_request_otp_at);

        // Cek apakah sudah bisa request lagi
        if ($otpRecord->can_request_otp_at && $now->lessThan($otpRecord->can_request_otp_at)) {
            return response()->json([
                'message' => "Harap tunggu $remainingSeconds detik sebelum meminta OTP lagi.",
                'remaining_time' => $remainingSeconds,
            ], 429);
        }

        // Generate OTP baru
        $otp = random_int(100000, 999999);
        $expiredAt = $now->copy()->addMinute();
        $attempt = $otpRecord->attempt_otp + 1;

        // Atur waktu request berikutnya berdasarkan jumlah attempt
        $nextRequestDelay = match ($attempt) {
            1 => 1,
            2 => 2,
            default => 5,
        };

        $otpRecord->fill([
            'otp' => $otp,
            'expired_otp' => $expiredAt,
            'can_request_otp_at' => $now->addMinutes($nextRequestDelay),
            'attempt_otp' => $attempt
        ])->save();

        // // Kirim email
        // Mail::raw("Kode OTP Anda adalah: $otp", function ($message) use ($user) {
        //     $message->to($user->email)
        //             ->subject('Verifikasi OTP');
        // });

            // Kirim email menggunakan view Blade
    Mail::send('auth.emails.otp', ['user' => $user, 'otp' => $otp], function ($message) use ($user) {
        $message->to($user->email)
                ->subject('Kode Verifikasi OTP Anda');
    });

        return response()->json([
            'message' => 'Kode OTP telah dikirim ke email ' . $user->email,
            'remaining_time' => $remainingSeconds,
        ],200);
    }

    public function verifyOtp(Request $request)
    {
        // Menggabungkan input OTP menjadi satu string
        $otp = implode('', $request->input('otp'));

        $otpRecord = EmailOtp::where('user_id', Auth::id())->first();

        // Mengecek jika jumlah karakter OTP tidak sama dengan 6
        if (strlen($otp) !== 6) {
            // Mengembalikan error jika OTP tidak memiliki 6 digit
            return back()->withErrors(['otp' => 'OTP harus terdiri dari 6 digit.']);
        }

        if (!$otpRecord || $otpRecord->otp !== $otp || now()->greaterThan($otpRecord->expired_otp)) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid atau telah kedaluwarsa.']);
        }

        Auth::user()->update(['email_verified_at' => now()]);
        return redirect()->route('home');
    }
}
