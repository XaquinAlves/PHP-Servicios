<?php

declare(strict_types=1);

namespace Com\Daw2\Libraries;

use Ahc\Jwt\JWT;

class JWTHelper
{
    private const  SECRET = 'e;CZP[N%,m}eQ3:JKCX:63ny1(DZ-YCj';
    private const ALGO = 'HS256';
    private const EXPIRATION_TIME = 1800;
    private const LEEWAY = 10;
    public function getToken(array $payload)
    {
        $jwt = new JWT(self::SECRET, self::ALGO, self::EXPIRATION_TIME, self::LEEWAY);
        return $jwt->encode($payload);
    }

    public function decodeToken(string $token)
    {
        $jwt = new JWT(self::SECRET, self::ALGO, self::EXPIRATION_TIME, self::LEEWAY);
        return $jwt->decode($token);
    }
}
