<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class TrackPageView
{
    /**
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next) : Response
    {
        if (
            auth()->guest() &&
            'GET' === $request->method() &&
            ! $request->hasHeader('X-Livewire') &&
            ! Str::startsWith($request->route()->uri, 'horizon/') &&
            ! Str::startsWith($request->route()->uri, 'nova/') &&
            ! Str::startsWith($request->route()->uri, 'recommends/') &&
            ! Str::startsWith($request->route()->uri, 'telescope/') &&
            config('services.pirsch.access_key')
        ) {
            Http::withToken(config('services.pirsch.access_key'))
                ->retry(3)
                ->post('https://api.pirsch.io/api/v1/hit', [
                    'url' => $request->fullUrl(),
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'accept_language' => $request->header('Accept-Language'),
                    'referrer' => $request->header('Referer'),
                ])
                ->throw();
        }

        return $next($request);
    }
}