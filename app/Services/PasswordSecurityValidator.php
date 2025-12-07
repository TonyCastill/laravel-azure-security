<?php

namespace App\Services;

class PasswordSecurityValidator
{
    public function validate(string $password): bool
    {
        // Regla 1: mínimo 8 caracteres
        if (strlen($password) < 8) {
            return false;
        }

        // Regla 2: al menos una mayúscula
        if (!preg_match('/[A-Z]/', $password)) {
            return false;
        }

        // Regla 3: al menos un número
        if (!preg_match('/[0-9]/', $password)) {
            return false;
        }

        // Regla 4: al menos un símbolo especial
        if (!preg_match('/[\W]/', $password)) {
            return false;
        }

        return true;
    }
}
