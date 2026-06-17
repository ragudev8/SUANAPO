<?php

namespace App\Http\Middleware;

use App\Services\AuditService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuditLog
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->user()) {
            app(AuditService::class)->registrar(
                $request->user()->id,
                $request->isMethod('get') ? 'view' : 'modify',
                $request->route()?->getName(),
            );
        }

        return $response;
    }
}
