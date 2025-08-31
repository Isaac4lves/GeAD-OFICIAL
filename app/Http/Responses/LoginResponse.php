<?php

declare(strict_types=1);

namespace App\Http\Responses;

use App\Enums\RoleType;
use App\Models\Aluno;
use Filament\Facades\Filament;
use Filament\Contracts\Auth\LoginResponse as LoginResponseContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        $user = Filament::auth()->user();

        // Se o usuário for um Aluno, redireciona para o painel do aluno
        if ($user instanceof Aluno) {
            return redirect()->to('/aluno');
        }

        // Se o usuário tiver o método hasRole (ou seja, é um User)
        if (method_exists($user, 'hasRole')) {
            if ($user->hasRole(RoleType::ADMIN->value)) {
                return redirect()->to('/admin');
            }

            if ($user->hasRole(RoleType::USER->value)) {
                return redirect()->to('/user');
            }
        }

        // Fallback para a rota home se nenhum caso corresponder
        return redirect()->route('home');
    }
}