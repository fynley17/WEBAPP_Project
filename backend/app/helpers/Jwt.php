<?php

namespace App\Helpers;

class Jwt
{
    // Function to generate a JWT token
    static function generate_jwt($payload, $secret = 'your_secret_key')
    {
        // Encode the header specifying the type (JWT) and algorithm (HS256)
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);

        // Encode the payload (data to be stored in the token)
        $payload = json_encode($payload);

        // Convert to base64 URL format (removing special characters)
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

        // Create the signature using HMAC SHA256
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        // Return the complete JWT token
        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    // Function to verify a JWT token
    static function verify_jwt($token, $secret = 'your_secret_key')
    {
        // Split the token into its three parts
        $parts = explode('.', $token);
        if (count($parts) !== 3) return false; // Invalid token format

        // Decode the header and payload from base64 URL format
        $header = base64_decode(str_replace(['-', '_'], ['+', '/'], $parts[0]));
        $payload = base64_decode(str_replace(['-', '_'], ['+', '/'], $parts[1]));

        // Retrieve the provided signature
        $signature_provided = str_replace(['-', '_'], ['+', '/'], $parts[2]);

        // Generate the expected signature using HMAC SHA256
        $expected_signature = hash_hmac('sha256', $parts[0] . "." . $parts[1], $secret, true);
        $expected_signature = base64_encode($expected_signature);

        // Compare the provided signature with the expected signature
        if ($signature_provided !== trim($expected_signature)) return false; // Signature mismatch

        // Decode the payload and check if the token has expired
        $payload_data = json_decode($payload, true);
        if ($payload_data['exp'] < time()) return false; // Token has expired

        // Return the decoded payload if the token is valid
        return $payload_data;
    }
}
