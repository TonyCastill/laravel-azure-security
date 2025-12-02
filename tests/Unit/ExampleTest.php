<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_that_true_is_true(): void
    {
        $this->assertTrue(true);
    }

    public function test_addition_result(): void
    {
        $controller = new \App\Http\Controllers\OperationsController;
        $result = $controller->addition(2, 3);
        $this->assertIsInt($result);
        $this->assertNotNull($result);
        $this->assertEquals(5, $result);
    }
}
