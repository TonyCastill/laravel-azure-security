<?php

namespace App\Http\Controllers;

class AnagramaController extends Controller
{
    /**
     * @return array{palabra1: string, palabra2: string, son_anagramas: bool}
     */
    public static function sonAnagramas(string $str1, string $str2): array
    {
        $arr1 = str_split(strtolower($str1));
        $arr2 = str_split(strtolower($str2));

        sort($arr1);
        sort($arr2);

        $sonAnagramas = $arr1 === $arr2;

        return [
            'palabra1' => $str1,
            'palabra2' => $str2,
            'son_anagramas' => $sonAnagramas,
        ];
    }
}
