<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Modules\Setting\Entities\Setting;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            // Ambil locale berdasarkan business_id pengguna
            $locale = Setting::where('business_id', Auth::user()->business_id)
                ->value('locale');

            // Set locale jika ada, atau fallback ke default di config/app.php
            App::setLocale($locale ?? config('app.locale'));
        }

        return $next($request);
    }
}
