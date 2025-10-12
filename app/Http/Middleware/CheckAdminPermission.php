<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckAdminPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $menuKey
     * @param  string  $action
     * @return mixed
     */
    public function handle($request, Closure $next, $menuKey = null, $action = 'view')
    {
        // Verifica se está autenticado como admin
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }

        $admin = Auth::guard('admin')->user();

        // Verifica se o admin está ativo
        if (!$admin->isActive()) {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login')
                ->with('error', 'Sua conta foi desativada. Entre em contato com o administrador.');
        }

        // Se não foi especificado um menu_key, apenas verifica se está autenticado
        if (!$menuKey) {
            return $next($request);
        }

        // Verifica se o admin tem permissão para o menu e ação especificados
        if (!$admin->hasPermission($menuKey, $action)) {
            // Se for uma requisição AJAX, retorna JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'message' => 'Acesso negado. Você não tem permissão para realizar esta ação.',
                    'error' => 'Forbidden'
                ], 403);
            }

            // Caso contrário, retorna página 403
            abort(403, 'Acesso negado. Você não tem permissão para acessar este módulo.');
        }

        return $next($request);
    }
}
