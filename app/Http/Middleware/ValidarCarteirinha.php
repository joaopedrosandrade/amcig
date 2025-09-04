<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Controllers\CarteirinhaController;

class ValidarCarteirinha
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->query('token');
        
        if ($token) {
            $controller = new CarteirinhaController();
            if (!$controller->validarToken($token)) {
                abort(403, 'Token de carteirinha inválido ou expirado');
            }
        }
        
        return $next($request);
    }
}
