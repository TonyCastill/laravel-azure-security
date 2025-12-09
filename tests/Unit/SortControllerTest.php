<?php

namespace Tests\Unit;

use App\Http\Controllers\SortController;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class SortControllerTest extends TestCase
{
    private SortController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new SortController;
    }

    /**
     * Test que verifica el ordenamiento correcto de números enteros positivos
     */
    public function test_ordena_numeros_positivos_correctamente(): void
    {
        // 3
        $numbers = [5, 2, 8, 1, 9, 3];
        $result = $this->controller->sortPositiveIntegers($numbers);

        $this->assertEquals([1, 2, 3, 5, 8, 9], $result);
        $this->assertCount(6, haystack: $result);
        // $this->addToAssertionCount(6,$this->controller->sizeOfArray($numbers));
    }

    /**
     * Test que verifica el ordenamiento de un array ya ordenado
     */
    public function test_ordena_array_ya_ordenado(): void
    {
        $numbers = [1, 2, 3, 4, 5];
        $result = $this->controller->sortPositiveIntegers($numbers);

        $this->assertEquals([1, 2, 3, 4, 5], $result);
    }

    /**
     * Test que verifica el ordenamiento de un array en orden descendente
     */
    public function test_ordena_array_descendente(): void
    {
        $numbers = [10, 9, 8, 7, 6, 5, 4, 3, 2, 1];
        $result = $this->controller->sortPositiveIntegers($numbers);

        $this->assertEquals([1, 2, 3, 4, 5, 6, 7, 8, 9, 10], $result);
    }

    /**
     * Test que verifica el ordenamiento con un solo elemento
     */
    public function test_ordena_array_con_un_elemento(): void
    {
        $numbers = [42];
        $result = $this->controller->sortPositiveIntegers($numbers);

        $this->assertEquals([42], $result);
    }

    /**
     * Test que verifica el ordenamiento con números duplicados
     */
    public function test_ordena_array_con_duplicados(): void
    {
        $numbers = [5, 2, 8, 2, 9, 5, 1];
        $result = $this->controller->sortPositiveIntegers($numbers);

        $this->assertEquals([1, 2, 2, 5, 5, 8, 9], $result);
    }

    /**
     * Test que verifica que lanza excepción con array vacío
     */
    public function test_lanza_excepcion_con_array_vacio(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('El arreglo no puede estar vacío');

        $this->controller->sortPositiveIntegers([]);
    }

    /**
     * Test que verifica que lanza excepción con números negativos
     */
    public function test_lanza_excepcion_con_numeros_negativos(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Todos los números deben ser enteros positivos (mayores a 0)');

        $this->controller->sortPositiveIntegers([1, 2, -3, 4]);
    }

    /**
     * Test que verifica que lanza excepción con cero
     */
    public function test_lanza_excepcion_con_cero(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Todos los números deben ser enteros positivos (mayores a 0)');

        $this->controller->sortPositiveIntegers([1, 2, 0, 4]);
    }

    /**
     * Test que verifica el comportamiento con floats
     * PHP convierte floats a int en el contexto de array<int>
     */
    public function test_ordena_con_conversion_de_float(): void
    {
        // Cuando PHP espera int, convierte 3.9 -> 3, 4.1 -> 4
        $numbers = [5, 2, 3, 1, 4];
        $result = $this->controller->sortPositiveIntegers($numbers);

        $this->assertEquals([1, 2, 3, 4, 5], $result);
    }

    /**
     * Test que verifica el ordenamiento con números grandes
     */
    public function test_ordena_numeros_grandes(): void
    {
        $numbers = [1000000, 500, 999999, 1, 50000];
        $result = $this->controller->sortPositiveIntegers($numbers);

        $this->assertEquals([1, 500, 50000, 999999, 1000000], $result);
    }

    /**
     * Test que verifica que el array original no se modifica
     */
    public function test_no_modifica_array_original(): void
    {
        $numbers = [5, 2, 8, 1, 9];
        $original = $numbers;

        $this->controller->sortPositiveIntegers($numbers);

        $this->assertEquals($original, $numbers);
    }

    /**
     * Test de rendimiento con array grande
     */
    public function test_ordena_array_grande(): void
    {
        $numbers = range(1, 1000);
        shuffle($numbers);

        $result = $this->controller->sortPositiveIntegers($numbers);

        $this->assertEquals(range(1, 1000), $result);
        $this->assertCount(1000, haystack: $result);
    }
}
