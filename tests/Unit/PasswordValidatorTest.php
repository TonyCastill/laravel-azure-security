<?php

namespace Tests\Unit;

use App\Services\PasswordValidator;
use PHPUnit\Framework\TestCase;

class PasswordValidatorTest extends TestCase
{
    private PasswordValidator $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new PasswordValidator();
    }

    /** @test */
    public function it_validates_a_strong_password_successfully()
    {
        $result = $this->validator->validate('Abcd123!@#');

        $this->assertTrue($result['valid']);
        $this->assertEquals('Contraseña válida y segura', $result['message']);
        $this->assertGreaterThanOrEqual(90, $result['score']);
        $this->assertEmpty($result['errors']);
    }

    /** @test */
    public function it_rejects_password_shorter_than_8_characters()
    {
        $result = $this->validator->validate('Ab1!');

        $this->assertFalse($result['valid']);
        $this->assertContains('La contraseña debe tener al menos 8 caracteres', $result['errors']);
    }

    /** @test */
    public function it_rejects_password_without_uppercase_letter()
    {
        $result = $this->validator->validate('abcd1234!@#');

        $this->assertFalse($result['valid']);
        $this->assertContains('Debe contener al menos una letra mayúscula', $result['errors']);
    }

    /** @test */
    public function it_rejects_password_without_lowercase_letter()
    {
        $result = $this->validator->validate('ABCD1234!@#');

        $this->assertFalse($result['valid']);
        $this->assertContains('Debe contener al menos una letra minúscula', $result['errors']);
    }

    /** @test */
    public function it_rejects_password_without_number()
    {
        $result = $this->validator->validate('Abcdefgh!@#');

        $this->assertFalse($result['valid']);
        $this->assertContains('Debe contener al menos un número', $result['errors']);
    }

    /** @test */
    public function it_rejects_password_without_special_character()
    {
        $result = $this->validator->validate('Abcdefgh123');

        $this->assertFalse($result['valid']);
        $this->assertContains('Debe contener al menos un carácter especial (!@#$%^&*...)', $result['errors']);
    }

    /** @test */
    public function it_returns_multiple_errors_for_weak_password()
    {
        $result = $this->validator->validate('abc');

        $this->assertFalse($result['valid']);
        $this->assertCount(4, $result['errors']);
    }

    /** @test */
    public function it_calculates_correct_strength_levels()
    {
        // Débil (menos de 50 puntos)
        $result = $this->validator->validate('abcdefg');
        $this->assertEquals('débil', $result['strength']);

        // Moderada (50-74 puntos)
        $result = $this->validator->validate('abcd1234');
        $this->assertEquals('moderada', $result['strength']);

        // Fuerte (75-99 puntos)
        $result = $this->validator->validate('Abcd1234');
        $this->assertEquals('fuerte', $result['strength']);

        // Muy fuerte (100 puntos)
        $result = $this->validator->validate('Abcd123!');
        $this->assertEquals('muy fuerte', $result['strength']);
    }

    /** @test */
    public function it_generates_valid_password_with_default_length()
    {
        $password = $this->validator->generate();

        $this->assertEquals(12, strlen($password));

        $result = $this->validator->validate($password);
        $this->assertTrue($result['valid']);
    }

    /** @test */
    public function it_generates_valid_password_with_custom_length()
    {
        $password = $this->validator->generate(16);

        $this->assertEquals(16, strlen($password));

        $result = $this->validator->validate($password);
        $this->assertTrue($result['valid']);
    }

    /** @test */
    public function it_generates_minimum_8_characters_when_shorter_length_requested()
    {
        $password = $this->validator->generate(5);

        $this->assertEquals(8, strlen($password));
    }
}
