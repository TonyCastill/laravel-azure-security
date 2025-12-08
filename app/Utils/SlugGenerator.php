<?php

namespace App\Utils;

class SlugGenerator
{
    /**
     * Genera un slug URL-friendly a partir de un texto
     * 
     * @param string $text Texto a convertir en slug
     * @param string $separator Separador entre palabras (default: '-')
     * @param int $maxLength Longitud máxima del slug (default: 255)
     * @return string Slug generado
     * @throws \InvalidArgumentException Si el texto está vacío
     */
    public static function generate(
        string $text,
        string $separator = '-',
        int $maxLength = 255
    ): string {
        if (empty(trim($text))) {
            throw new \InvalidArgumentException('El texto no puede estar vacío');
        }

        if ($maxLength < 1) {
            throw new \InvalidArgumentException('La longitud máxima debe ser mayor a 0');
        }

        // Convertir a minúsculas
        $slug = mb_strtolower($text, 'UTF-8');

        // Reemplazar caracteres acentuados
        $slug = self::removeAccents($slug);

        // Mantener solo caracteres alfanuméricos y espacios
        $slug = preg_replace('/[^a-z0-9\s-]/u', '', $slug);

        // Reemplazar espacios y múltiples guiones con un solo separador
        $slug = preg_replace('/[\s-]+/', $separator, $slug);

        // Remover separadores del inicio y final
        $slug = trim($slug, $separator);

        // Limitar a la longitud máxima
        if (strlen($slug) > $maxLength) {
            $slug = substr($slug, 0, $maxLength);
            $slug = rtrim($slug, $separator);
        }

        return $slug;
    }

    /**
     * Valida si un string es un slug válido
     * 
     * @param string $slug Slug a validar
     * @param string $separator Separador esperado (default: '-')
     * @return bool True si es un slug válido
     */
    public static function isValid(string $slug, string $separator = '-'): bool
    {
        if (empty($slug)) {
            return false;
        }

        // Un slug válido solo contiene minúsculas, números y el separador
        $pattern = '/^[a-z0-9' . preg_quote($separator, '/') . ']+$/';
        
        if (!preg_match($pattern, $slug)) {
            return false;
        }

        // No debe empezar ni terminar con el separador
        if (strpos($slug, $separator) === 0 || strrpos($slug, $separator) === strlen($slug) - 1) {
            return false;
        }

        // No debe tener separadores múltiples consecutivos
        if (strpos($slug, $separator . $separator) !== false) {
            return false;
        }

        return true;
    }

    /**
     * Remueve acentos de caracteres latinos
     * 
     * @param string $text Texto con posibles acentos
     * @return string Texto sin acentos
     */
    private static function removeAccents(string $text): string
    {
        $accents = [
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
            'à' => 'a', 'è' => 'e', 'ì' => 'i', 'ò' => 'o', 'ù' => 'u',
            'ä' => 'a', 'ë' => 'e', 'ï' => 'i', 'ö' => 'o', 'ü' => 'u',
            'â' => 'a', 'ê' => 'e', 'î' => 'i', 'ô' => 'o', 'û' => 'u',
            'ã' => 'a', 'õ' => 'o', 'ñ' => 'n', 'ç' => 'c',
        ];

        return strtr($text, $accents);
    }
}