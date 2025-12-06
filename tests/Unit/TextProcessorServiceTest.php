<?php

namespace Tests\Unit;

use App\Services\TextProcessorService;
use Tests\TestCase;

class TextProcessorServiceTest extends TestCase
{
    protected TextProcessorService $textProcessor;

    protected function setUp(): void
    {
        parent::setUp();
        $this->textProcessor = new TextProcessorService;
    }

    // ========== Tests para countWords ==========

    public function test_count_words_returns_correct_count(): void
    {
        $result = $this->textProcessor->countWords('Hola mundo desde Laravel');
        $this->assertEquals(4, $result);
    }

    public function test_count_words_handles_empty_string(): void
    {
        $result = $this->textProcessor->countWords('');
        $this->assertEquals(0, $result);
    }

    public function test_count_words_handles_multiple_spaces(): void
    {
        $result = $this->textProcessor->countWords('Hola    mundo    Laravel');
        $this->assertEquals(3, $result);
    }

    // ========== Tests para reverseText ==========

    public function test_reverse_text_inverts_correctly(): void
    {
        $result = $this->textProcessor->reverseText('Laravel');
        $this->assertEquals('levaraL', $result);
    }

    public function test_reverse_text_handles_empty_string(): void
    {
        $result = $this->textProcessor->reverseText('');
        $this->assertEquals('', $result);
    }

    // ========== Tests para capitalizeWords ==========

    public function test_capitalize_words_capitalizes_correctly(): void
    {
        $result = $this->textProcessor->capitalizeWords('hola mundo laravel');
        $this->assertEquals('Hola Mundo Laravel', $result);
    }

    public function test_capitalize_words_handles_mixed_case(): void
    {
        $result = $this->textProcessor->capitalizeWords('hOlA mUnDo');
        $this->assertEquals('Hola Mundo', $result);
    }

    // ========== Tests para removeSpecialCharacters ==========

    public function test_remove_special_characters_removes_symbols(): void
    {
        $result = $this->textProcessor->removeSpecialCharacters('Hola! ¿Cómo estás?');
        $this->assertEquals('Hola Cmo ests', $result);
    }

    public function test_remove_special_characters_keeps_alphanumeric(): void
    {
        $result = $this->textProcessor->removeSpecialCharacters('Test123 ABC');
        $this->assertEquals('Test123 ABC', $result);
    }

    // ========== Tests para generateSlug ==========

    public function test_generate_slug_creates_valid_slug(): void
    {
        $result = $this->textProcessor->generateSlug('Hola Mundo Laravel');
        $this->assertEquals('hola-mundo-laravel', $result);
    }

    public function test_generate_slug_removes_special_characters(): void
    {
        $result = $this->textProcessor->generateSlug('¡Hola! ¿Mundo?');
        $this->assertEquals('hola-mundo', $result);
    }

    public function test_generate_slug_handles_multiple_spaces(): void
    {
        $result = $this->textProcessor->generateSlug('Hola    Mundo    Laravel');
        $this->assertEquals('hola-mundo-laravel', $result);
    }

    // ========== Tests para calculateVowelPercentage ==========

    public function test_calculate_vowel_percentage_returns_correct_value(): void
    {
        $result = $this->textProcessor->calculateVowelPercentage('aeiou');
        $this->assertEquals(100.0, $result);
    }

    public function test_calculate_vowel_percentage_handles_no_vowels(): void
    {
        $result = $this->textProcessor->calculateVowelPercentage('xyz');
        $this->assertEquals(0.0, $result);
    }

    public function test_calculate_vowel_percentage_handles_mixed_text(): void
    {
        $result = $this->textProcessor->calculateVowelPercentage('Laravel');
        // Laravel tiene 7 letras: L-a-r-a-v-e-l
        // Vocales: a, a, e (3 vocales de 7 letras = 42.86%)
        $this->assertEquals(42.86, $result);
    }

    public function test_calculate_vowel_percentage_handles_empty_string(): void
    {
        $result = $this->textProcessor->calculateVowelPercentage('');
        $this->assertEquals(0.0, $result);
    }
}
