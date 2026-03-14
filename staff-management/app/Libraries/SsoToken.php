<?php

namespace App\Libraries;

use Config\SsoConfig;

class SsoToken
{
    public static function generate(int $userId, string $email, string $group): string
    {
        $config  = new SsoConfig();
        $payload = [
            'uid'   => $userId,
            'email' => $email,
            'group' => $group,
            'exp'   => time() + $config->tokenTtl,
        ];

        $data      = base64_encode(json_encode($payload));
        $signature = hash_hmac('sha256', $data, $config->secretKey);

        return $data . '.' . $signature;
    }

    public static function verify(string $token): ?array
    {
        $config = new SsoConfig();
        $parts  = explode('.', $token);

        if (count($parts) !== 2) return null;

        [$data, $signature] = $parts;

        $expected = hash_hmac('sha256', $data, $config->secretKey);
        if (!hash_equals($expected, $signature)) return null;

        $payload = json_decode(base64_decode($data), true);

        if (!$payload || $payload['exp'] < time()) return null;

        return $payload;
    }
}