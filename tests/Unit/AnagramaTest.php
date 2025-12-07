<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Utils\Anagrama;

class AnagramaTest extends TestCase
{
    public function test_son_anagramas()
    {
        $this->assertTrue(Anagrama::sonAnagramas('listen', 'silent'));
        $this->assertTrue(Anagrama::sonAnagramas('amor', 'roma'));
        $this->assertFalse(Anagrama::sonAnagramas('hello', 'world'));
    }
}   