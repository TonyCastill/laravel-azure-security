<?php

namespace Tests\Unit;

use App\Services\JWTService;
use Tests\TestCase;

class jwtTest extends TestCase
{
    private JWTService $jwtService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->jwtService = new JWTService;
    }

    public function test_generates_valid_jwt(): void
    {
        $payload = ['user_id' => 123, 'email' => 'test@example.com'];
        $result = $this->jwtService->generateJWT($payload);

        $this->assertFalse($result['error']);
        $this->assertArrayHasKey('token', $result['result']);
        $this->assertStringContainsString('.', $result['result']['token']);
    }

    public function test_validates_correct_jwt(): void
    {
        $payload = ['user_id' => 123];
        $generated = $this->jwtService->generateJWT($payload);

        $validated = $this->jwtService->validateJWT($generated['result']['token']);

        $this->assertFalse($validated['error']);
        $this->assertEquals(123, $validated['result']['payload']['user_id']);
    }

    public function test_rejects_empty_payload(): void
    {
        $result = $this->jwtService->generateJWT([]);

        $this->assertTrue($result['error']);
        $this->assertNull($result['result']);
    }

    public function test_rejects_tampered_token(): void
    {
        $payload = ['user_id' => 123];
        $generated = $this->jwtService->generateJWT($payload);

        $token = $generated['result']['token'];
        $tamperedToken = substr($token, 0, -5).'XXXXX';

        $validated = $this->jwtService->validateJWT($tamperedToken);

        $this->assertTrue($validated['error']);
    }

    public function test_rejects_invalid_format(): void
    {
        $result = $this->jwtService->validateJWT('invalid.token');

        $this->assertTrue($result['error']);
    }

    public function test_includes_expiration_time(): void
    {
        $result = $this->jwtService->generateJWT(['user_id' => 1], 30);

        $this->assertArrayHasKey('expires_at', $result['result']);
        $this->assertArrayHasKey('exp', $result['result']['payload']);
    }

    public function test_detects_expired_token(): void
    {
        $jwtService = new JWTService;
        $reflection = new \ReflectionClass($jwtService);
        $base64Method = $reflection->getMethod('base64UrlEncode');
        $base64Method->setAccessible(true);

        $payload = [
            'user_id' => 123,
            'iat' => time() - 7200,
            'exp' => time() - 3600,
        ];

        $header = ['typ' => 'JWT', 'alg' => 'HS256'];
        $headerEncoded = $base64Method->invoke($jwtService, json_encode($header));
        $payloadEncoded = $base64Method->invoke($jwtService, json_encode($payload));

        $signature = hash_hmac(
            'sha256',
            $headerEncoded.'.'.$payloadEncoded,
            'mi_super_secreto_key_2024',
            true
        );
        $signatureEncoded = $base64Method->invoke($jwtService, $signature);

        $expiredToken = $headerEncoded.'.'.$payloadEncoded.'.'.$signatureEncoded;

        $result = $this->jwtService->validateJWT($expiredToken);

        $this->assertTrue($result['error']);
    }

    public function test_preserves_custom_payload_data(): void
    {
        $customData = [
            'user_id' => 999,
            'email' => 'tucas@inegi.gob.mx',
            'role' => 'admin',
        ];

        $result = $this->jwtService->generateJWT($customData);
        $validated = $this->jwtService->validateJWT($result['result']['token']);

        $this->assertEquals(999, $validated['result']['payload']['user_id']);
        $this->assertEquals('tucas@inegi.gob.mx', $validated['result']['payload']['email']);
    }
}
