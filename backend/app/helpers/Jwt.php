<?php

namespace App\Helpers;

class Jwt
{
    static function generate_jwt($payload, $secret = 'your_secret_key')
    {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $payload = json_encode($payload);

        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    static function verify_jwt($token, $secret = 'your_secret_key')
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) return false;

        $header = base64_decode(str_replace(['-', '_'], ['+', '/'], $parts[0]));
        $payload = base64_decode(str_replace(['-', '_'], ['+', '/'], $parts[1]));
        $signature_provided = str_replace(['-', '_'], ['+', '/'], $parts[2]);

        $expected_signature = hash_hmac('sha256', $parts[0] . "." . $parts[1], $secret, true);
        $expected_signature = base64_encode($expected_signature);

        if ($signature_provided !== trim($expected_signature)) return false;

        $payload_data = json_decode($payload, true);
        if ($payload_data['exp'] < time()) return false; // Token expired

        return $payload_data;
    }
}
