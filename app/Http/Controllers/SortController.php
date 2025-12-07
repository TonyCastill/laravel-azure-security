<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use InvalidArgumentException;

class SortController extends Controller
{
    /**
     * Ordena un arreglo de números enteros positivos
     *
     * @param array $numbers Array de números enteros positivos
     * @return array Array ordenado de menor a mayor
     * @throws InvalidArgumentException Si el array está vacío o contiene valores no válidos
     */
    public function sortPositiveIntegers(array $numbers): array
    {
        // Validar que el array no esté vacío
        if (empty($numbers)) {
            throw new InvalidArgumentException('El arreglo no puede estar vacío');
        }

        // Validar que todos los elementos sean enteros positivos
        foreach ($numbers as $number) {
            if (!is_int($number)) {
                throw new InvalidArgumentException('Todos los elementos deben ser números enteros');
            }
            
            if ($number <= 0) {
                throw new InvalidArgumentException('Todos los números deben ser enteros positivos (mayores a 0)');
            }
        }

        // Ordenar el array
        $sortedNumbers = $numbers;
        sort($sortedNumbers, SORT_NUMERIC);

        return $sortedNumbers;
    }

    /**
     * Endpoint HTTP para ordenar números
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function sort(Request $request): JsonResponse
    {
        try {
            $numbers = $request->input('numbers', []);
            
            $sorted = $this->sortPositiveIntegers($numbers);
            
            return response()->json([
                'success' => true,
                'original' => $numbers,
                'sorted' => $sorted
            ], 200);
            
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
