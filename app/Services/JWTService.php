<?php

namespace App\Services;

class JWTService
{
    private string $secretKey = 'mi_super_secreto_key_2024';

    /**
     * Genera un JWT con payload personalizado
     *
     * @param  array<string, mixed>  $payload  Datos a incluir en el token
     * @param  int  $expirationMinutes  Tiempo de expiración en minutos
     * @return array{error: bool, message: string, result: array{token: string, expires_at: string, payload: array<string, mixed>}|null}
     */
    public function generateJWT(array $payload, int $expirationMinutes = 60): array
    {
        if (empty($payload)) {
            return [
                'error' => true,
                'message' => 'El payload no puede estar vacío',
                'result' => null,
            ];
        }

        $header = [
            'typ' => 'JWT',
            'alg' => 'HS256',
        ];

        $now = time();
        $payload['iat'] = $now;
        $payload['exp'] = $now + ($expirationMinutes * 60);

        $headerEncoded = $this->base64UrlEncode(json_encode($header));
        $payloadEncoded = $this->base64UrlEncode(json_encode($payload));

        $signature = hash_hmac(
            'sha256',
            $headerEncoded.'.'.$payloadEncoded,
            $this->secretKey,
            true
        );
        $signatureEncoded = $this->base64UrlEncode($signature);

        $jwt = $headerEncoded.'.'.$payloadEncoded.'.'.$signatureEncoded;

        return [
            'error' => false,
            'message' => 'JWT generado exitosamente',
            'result' => [
                'token' => $jwt,
                'expires_at' => date('Y-m-d H:i:s', $payload['exp']),
                'payload' => $payload,
            ],
        ];
    }

    /**
     * Valida y decodifica un JWT
     *
     * @param  string  $jwt  Token a validar
     * @return array{error: bool, message: string, result: array{payload: array<string, mixed>, issued_at?: string, expires_at?: string, expired_at?: string}|null}
     */
    public function validateJWT(string $jwt): array
    {
        $parts = explode('.', $jwt);

        if (count($parts) !== 3) {
            return [
                'error' => true,
                'message' => 'Formato de JWT inválido',
                'result' => null,
            ];
        }

        [$headerEncoded, $payloadEncoded, $signatureEncoded] = $parts;

        $signature = hash_hmac(
            'sha256',
            $headerEncoded.'.'.$payloadEncoded,
            $this->secretKey,
            true
        );
        $expectedSignature = $this->base64UrlEncode($signature);

        if ($signatureEncoded !== $expectedSignature) {
            return [
                'error' => true,
                'message' => 'Signature inválida - token comprometido',
                'result' => null,
            ];
        }

        $decodedPayload = json_decode($this->base64UrlDecode($payloadEncoded), true);

        if (! is_array($decodedPayload)) {
            return [
                'error' => true,
                'message' => 'Payload inválido',
                'result' => null,
            ];
        }

        /** @var array<string, mixed> $payload */
        $payload = $decodedPayload;

        if (isset($payload['exp']) && is_int($payload['exp']) && $payload['exp'] < time()) {
            return [
                'error' => true,
                'message' => 'Token expirado',
                'result' => [
                    'expired_at' => date('Y-m-d H:i:s', $payload['exp']),
                    'payload' => $payload,
                ],
            ];
        }

        $iat = isset($payload['iat']) && is_int($payload['iat']) ? $payload['iat'] : time();
        $exp = isset($payload['exp']) && is_int($payload['exp']) ? $payload['exp'] : time();

        return [
            'error' => false,
            'message' => 'Token válido',
            'result' => [
                'payload' => $payload,
                'issued_at' => date('Y-m-d H:i:s', $iat),
                'expires_at' => date('Y-m-d H:i:s', $exp),
            ],
        ];
    }

    private function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function base64UrlDecode(string $data): string
    {
        return base64_decode(strtr($data, '-_', '+/'));
    }
}
