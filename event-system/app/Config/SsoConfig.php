<?php

namespace Config;

class SsoConfig
{
    public int $tokenTtl;
    public string $secretKey;
    public string $loginUrl;
    public array $groupUrlMap;
    public array $landingRoutes;

    public function __construct()
    {
        $this->tokenTtl    = (int) env('SSO_TOKEN_TTL', 30);
        $this->secretKey   = env('SSO_SECRET_KEY', '7f9A2cD4kLm8Qx1PzR5vT0bN6yH3eWjS');
        $this->loginUrl    = env('SSO_LOGIN_URL', 'http://localhost:8080/login');
        $this->groupUrlMap = [
            'staff'  => env('SSO_GROUP_URL_STAFF', 'http://localhost:8082/staff'),
            'studio' => env('SSO_GROUP_URL_STUDIO', 'http://localhost:8083/studio'),
        ];
        $this->landingRoutes = [
            'staff'  => env('SSO_LANDING_STAFF', 'http://localhost:8082/staff/dashboard'),
            'studio' => env('SSO_LANDING_STUDIO', 'http://localhost:8083/studio/dashboard'),
        ];
    }
}