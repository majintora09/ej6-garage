<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCarProfileExists
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && ! $request->user()->carProfiles()->exists()) {
            return redirect()->route('garage.setup');
        }

        return $next($request);
    }
}
