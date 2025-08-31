<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectGuestsToCentralLoginMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Filament::auth()->check()) {
            return $next($request);
        }

        $path = $request->path();

        // Se a URL começar com /admin, redireciona para o login do admin
        if (str_starts_with($path, 'admin')) {
            // A menos que já seja a própria página de login
            if ($path === 'admin/login') {
                return $next($request);
            }
            return redirect()->to('/admin/login');
        }

        // Se a URL começar com /aluno, redireciona para o login do aluno
        if (str_starts_with($path, 'aluno')) {
            // A menos que já seja a própria página de login
            if ($path === 'aluno/login') {
                return $next($request);
            }
            return redirect()->to('/aluno/login');
        }

        // Para qualquer outra rota não autenticada, deixa passar (pode ser a home, etc)
        return $next($request);
    }
}