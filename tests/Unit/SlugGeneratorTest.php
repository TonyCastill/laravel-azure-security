<?php

namespace Tests\Unit;

use App\Utils\SlugGenerator;
use PHPUnit\Framework\TestCase;

class SlugGeneratorTest extends TestCase
{
    /**
     * Prueba que genera correctamente un slug básico
     */
    public function test_generates_basic_slug(): void
    {
        $result = SlugGenerator::generate('Hello World');
        $this->assertEquals('hello-world', $result);
    }

    /**
     * Prueba que convierte a minúsculas correctamente
     */
    public function test_converts_to_lowercase(): void
    {
        $result = SlugGenerator::generate('HELLO WORLD');
        $this->assertEquals('hello-world', $result);
    }

    /**
     * Prueba que remueve acentos correctamente
     */
    public function test_removes_accents(): void
    {
        $result = SlugGenerator::generate('Café Español');
        $this->assertEquals('cafe-espanol', $result);
    }

    /**
     * Prueba que remueve caracteres especiales
     */
    public function test_removes_special_characters(): void
    {
        $result = SlugGenerator::generate('Hello@World#Test!');
        $this->assertEquals('helloworldtest', $result);
    }

    /**
     * Prueba que reemplaza espacios múltiples
     */
    public function test_replaces_multiple_spaces(): void
    {
        $result = SlugGenerator::generate('Hello    World    Test');
        $this->assertEquals('hello-world-test', $result);
    }

    /**
     * Prueba que reemplaza guiones múltiples
     */
    public function test_replaces_multiple_dashes(): void
    {
        $result = SlugGenerator::generate('Hello---World');
        $this->assertEquals('hello-world', $result);
    }

    /**
     * Prueba que remueve espacios y guiones al inicio y final
     */
    public function test_trims_separators(): void
    {
        $result = SlugGenerator::generate('  Hello World  ');
        $this->assertEquals('hello-world', $result);
    }

    /**
     * Prueba con separador personalizado
     */
    public function test_generates_with_custom_separator(): void
    {
        $result = SlugGenerator::generate('Hello World', '_');
        $this->assertEquals('hello_world', $result);
    }

    /**
     * Prueba que respeta la longitud máxima
     */
    public function test_respects_max_length(): void
    {
        $result = SlugGenerator::generate('This is a very long text that should be truncated', '_', 15);
        $this->assertLessThanOrEqual(15, strlen($result));
        $this->assertEquals('this_is_a_very', $result);
    }

    /**
     * Prueba que maneja números correctamente
     */
    public function test_keeps_numbers(): void
    {
        $result = SlugGenerator::generate('Article 2024 Part 3');
        $this->assertEquals('article-2024-part-3', $result);
    }

    /**
     * Prueba que lanza excepción con texto vacío
     */
    public function test_throws_exception_for_empty_text(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        SlugGenerator::generate('');
    }

    /**
     * Prueba que lanza excepción con solo espacios
     */
    public function test_throws_exception_for_only_spaces(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        SlugGenerator::generate('   ');
    }

    /**
     * Prueba que lanza excepción con maxLength inválido
     */
    public function test_throws_exception_for_invalid_max_length(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        SlugGenerator::generate('Hello World', '-', 0);
    }

    /**
     * Prueba validación de slug válido
     */
    public function test_validates_valid_slug(): void
    {
        $this->assertTrue(SlugGenerator::isValid('hello-world'));
    }

    /**
     * Prueba validación rechaza slug con mayúsculas
     */
    public function test_validation_rejects_uppercase(): void
    {
        $this->assertFalse(SlugGenerator::isValid('Hello-World'));
    }

    /**
     * Prueba validación rechaza slug con caracteres especiales
     */
    public function test_validation_rejects_special_characters(): void
    {
        $this->assertFalse(SlugGenerator::isValid('hello@world'));
    }

    /**
     * Prueba validación rechaza slug que empieza con separador
     */
    public function test_validation_rejects_leading_separator(): void
    {
        $this->assertFalse(SlugGenerator::isValid('-hello-world'));
    }

    /**
     * Prueba validación rechaza slug que termina con separador
     */
    public function test_validation_rejects_trailing_separator(): void
    {
        $this->assertFalse(SlugGenerator::isValid('hello-world-'));
    }

    /**
     * Prueba validación rechaza slug con separadores múltiples
     */
    public function test_validation_rejects_multiple_separators(): void
    {
        $this->assertFalse(SlugGenerator::isValid('hello--world'));
    }

    /**
     * Prueba validación rechaza slug vacío
     */
    public function test_validation_rejects_empty_slug(): void
    {
        $this->assertFalse(SlugGenerator::isValid(''));
    }

    /**
     * Prueba validación con separador personalizado
     */
    public function test_validation_with_custom_separator(): void
    {
        $this->assertTrue(SlugGenerator::isValid('hello_world', '_'));
        $this->assertFalse(SlugGenerator::isValid('hello-world', '_'));
    }

    /**
     * Prueba que el slug generado es siempre válido
     */
    public function test_generated_slug_is_always_valid(): void
    {
        $testCases = [
            'Simple Text',
            'UPPERCASE TEXT',
            'Café Español',
            'Special!@#$%Characters',
            'Multiple   Spaces',
            'Mixed-Case-With-Dashes',
            '123 Numbers 456',
            'Ñoño Español',
        ];

        foreach ($testCases as $text) {
            $slug = SlugGenerator::generate($text);
            $this->assertTrue(SlugGenerator::isValid($slug), "Generated slug '{$slug}' is not valid");
        }
    }
}
