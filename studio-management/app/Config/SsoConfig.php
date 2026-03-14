<?php

namespace Config;

class SsoConfig
{
    public int $tokenTtl = 30;
    public string $secretKey = '7f9A2cD4kLm8Qx1PzR5vT0bN6yH3eWjS';
    public string $loginUrl  = 'http://localhost:8080/login';
    public array  $groupUrlMap = [
        'staff'  => 'http://localhost:8082/staff',
        'studio' => 'http://localhost:8083/studio',
    ];
    public array $landingRoutes = [
        'staff'  => 'http://localhost:8082/staff/dashboard',
        'studio' => 'http://localhost:8083/studio/dashboard',
    ];
}