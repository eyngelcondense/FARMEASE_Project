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
        $token     = $data . '.' . $signature;

        log_message('info', "SSO Token generated for user_id: $userId, email: $email, group: $group");

        return $token;
    }

    public static function verify(string $token): ?array
    {
        $config = new SsoConfig();
        $parts  = explode('.', $token);

        if (count($parts) !== 2) {
            log_message('warning', 'SSO Token verification failed: Invalid token format');
            return null;
        }

        [$data, $signature] = $parts;

        $expected = hash_hmac('sha256', $data, $config->secretKey);
        if (!hash_equals($expected, $signature)) {
            log_message('warning', 'SSO Token verification failed: Invalid signature');
            return null;
        }

        $payload = json_decode(base64_decode($data), true);

        if (!$payload) {
            log_message('warning', 'SSO Token verification failed: Invalid payload format');
            return null;
        }

        if ($payload['exp'] < time()) {
            log_message('warning', 'SSO Token verification failed: Token expired (exp: ' . $payload['exp'] . ', now: ' . time() . ')');
            return null;
        }

        log_message('info', "SSO Token verified for user_id: {$payload['uid']}, group: {$payload['group']}");

        return $payload;
    }
}