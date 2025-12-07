<?php

namespace App\Services;

class PasswordValidator
{
    /**
     * Valida si una contraseña cumple con criterios de seguridad
     *
     * @param string $password La contraseña a validar
     * @return array{valid: bool, score: int, strength: string, errors: array<int, string>, message: string}
     */
    public function validate(string $password): array
    {
        $errors = [];
        $score = 0;

        // Criterio 1: Longitud mínima de 8 caracteres
        if (strlen($password) < 8) {
            $errors[] = 'La contraseña debe tener al menos 8 caracteres';
        } else {
            $score += 25;
        }

        // Criterio 2: Debe contener al menos una letra mayúscula
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = 'Debe contener al menos una letra mayúscula';
        } else {
            $score += 25;
        }

        // Criterio 3: Debe contener al menos una letra minúscula
        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = 'Debe contener al menos una letra minúscula';
        } else {
            $score += 25;
        }

        // Criterio 4: Debe contener al menos un número
        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = 'Debe contener al menos un número';
        } else {
            $score += 15;
        }

        // Criterio 5: Debe contener al menos un carácter especial
        if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
            $errors[] = 'Debe contener al menos un carácter especial (!@#$%^&*...)';
        } else {
            $score += 10;
        }

        $isValid = empty($errors);

        return [
            'valid' => $isValid,
            'score' => $score,
            'strength' => $this->getStrength($score),
            'errors' => $errors,
            'message' => $isValid ? 'Contraseña válida y segura' : 'La contraseña no cumple con los requisitos de seguridad',
        ];
    }

    /**
     * Determina el nivel de fortaleza basado en el puntaje
     *
     * @param int $score Puntaje obtenido
     * @return string Nivel de fortaleza
     */
    private function getStrength(int $score): string
    {
        if ($score >= 100) {
            return 'muy fuerte';
        } elseif ($score >= 75) {
            return 'fuerte';
        } elseif ($score >= 50) {
            return 'moderada';
        }

        return 'débil';
    }

    /**
     * Genera una contraseña segura aleatoria
     *
     * @param int $length Longitud de la contraseña (mínimo 8)
     * @return string Contraseña generada
     */
    public function generate(int $length = 12): string
    {
        if ($length < 8) {
            $length = 8;
        }

        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $special = '!@#$%^&*()';

        // Asegurar que tenga al menos uno de cada tipo
        $password = '';
        $password .= $uppercase[random_int(0, strlen($uppercase) - 1)];
        $password .= $lowercase[random_int(0, strlen($lowercase) - 1)];
        $password .= $numbers[random_int(0, strlen($numbers) - 1)];
        $password .= $special[random_int(0, strlen($special) - 1)];

        // Completar el resto de la longitud
        $allChars = $uppercase . $lowercase . $numbers . $special;
        for ($i = 4; $i < $length; $i++) {
            $password .= $allChars[random_int(0, strlen($allChars) - 1)];
        }

        // Mezclar los caracteres
        return str_shuffle($password);
    }
}
