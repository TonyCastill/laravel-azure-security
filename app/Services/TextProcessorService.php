<?php

namespace App\Services;

class TextProcessorService
{
    /**
     * Cuenta el número de palabras en un texto
     *
     * @param  string  $text  Texto a analizar
     * @return int Número de palabras
     */
    public function countWords(string $text): int
    {
        $text = trim($text);

        if (empty($text)) {
            return 0;
        }

        return count(preg_split('/\s+/', $text));
    }

    /**
     * Invierte un texto
     *
     * @param  string  $text  Texto a invertir
     * @return string Texto invertido
     */
    public function reverseText(string $text): string
    {
        return strrev($text);
    }

    /**
     * Capitaliza la primera letra de cada palabra
     *
     * @param  string  $text  Texto a capitalizar
     * @return string Texto capitalizado
     */
    public function capitalizeWords(string $text): string
    {
        return mb_convert_case($text, MB_CASE_TITLE, 'UTF-8');
    }

    /**
     * Elimina caracteres especiales, dejando solo letras, números y espacios
     *
     * @param  string  $text  Texto a limpiar
     * @return string Texto sin caracteres especiales
     */
    public function removeSpecialCharacters(string $text): string
    {
        return preg_replace('/[^A-Za-z0-9\s]/', '', $text);
    }

    /**
     * Genera un slug válido para URL desde un texto
     *
     * @param  string  $text  Texto a convertir
     * @return string Slug generado
     */
    public function generateSlug(string $text): string
    {
        // Convertir a minúsculas
        $slug = mb_strtolower($text, 'UTF-8');

        // Reemplazar espacios y caracteres especiales por guiones
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);

        // Eliminar guiones al inicio y final
        $slug = trim($slug, '-');

        return $slug;
    }

    /**
     * Calcula el porcentaje de vocales en un texto
     *
     * @param  string  $text  Texto a analizar
     * @return float Porcentaje de vocales
     */
    public function calculateVowelPercentage(string $text): float
    {
        $text = strtolower($text);
        $totalLetters = preg_match_all('/[a-z]/', $text);

        if ($totalLetters === 0) {
            return 0.0;
        }

        $vowels = preg_match_all('/[aeiou]/', $text);

        return round(($vowels / $totalLetters) * 100, 2);
    }
}
