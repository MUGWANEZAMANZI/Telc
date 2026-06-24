<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = 'en';

        if ($user = $request->user()) {
            $locale = $user->locale ?? 'en';
        } elseif ($sessionLocale = session('locale')) {
            $locale = $sessionLocale;
        }

        if (in_array($locale, ['en', 'fr', 'es'], true)) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}
