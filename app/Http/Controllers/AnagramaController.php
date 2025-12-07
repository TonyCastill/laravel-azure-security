<?php

namespace App\Http\Controllers;

class Anagrama
{
    public static function sonAnagramas(string $str1, string $str2): bool
    {
        $arr1 = str_split(strtolower($str1));
        $arr2 = str_split(strtolower($str2));
        
        sort($arr1);
        sort($arr2);
        
        return $arr1 === $arr2;
    }
}