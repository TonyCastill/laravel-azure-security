<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\PasswordSecurityValidator;

class PasswordSecurityValidatorTest extends TestCase
{
    public function test_valid_password_returns_true()
    {
        $validator = new PasswordSecurityValidator();

        $this->assertTrue(
            $validator->validate("Seguro123!")
        );
    }

    public function test_short_password_returns_false()
    {
        $validator = new PasswordSecurityValidator();

        $this->assertFalse(
            $validator->validate("Ab1!")
        );
    }

    public function test_password_without_uppercase_returns_false()
    {
        $validator = new PasswordSecurityValidator();

        $this->assertFalse(
            $validator->validate("segura123!")
        );
    }

    public function test_password_without_number_returns_false()
    {
        $validator = new PasswordSecurityValidator();

        $this->assertFalse(
            $validator->validate("Segura!!!")
        );
    }

    public function test_password_without_symbol_returns_false()
    {
        $validator = new PasswordSecurityValidator();

        $this->assertFalse(
            $validator->validate("Seguro123")
        );
    }
}
