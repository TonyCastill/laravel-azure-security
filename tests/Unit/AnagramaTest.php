<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\AnagramaController as Anagrama;

class AnagramaTest extends TestCase
{
    public function test_son_anagramas()
    {
        $resultado1 = Anagrama::sonAnagramas('listen', 'silent');
        echo "\n'{$resultado1['palabra1']}' y '{$resultado1['palabra2']}' son anagramas: " . ($resultado1['son_anagramas'] ? 'Sí' : 'No') . "\n";
        $this->assertTrue($resultado1['son_anagramas']);
        
        $resultado2 = Anagrama::sonAnagramas('amor', 'roma');
        echo "'{$resultado2['palabra1']}' y '{$resultado2['palabra2']}' son anagramas: " . ($resultado2['son_anagramas'] ? 'Sí' : 'No') . "\n";
        $this->assertTrue($resultado2['son_anagramas']);
        
        $resultado3 = Anagrama::sonAnagramas('hello', 'world');
        echo "'{$resultado3['palabra1']}' y '{$resultado3['palabra2']}' son anagramas: " . ($resultado3['son_anagramas'] ? 'Sí' : 'No') . "\n";
        $this->assertFalse($resultado3['son_anagramas']);
    }
}