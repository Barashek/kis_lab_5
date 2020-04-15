<?php


namespace App\Controller;


use Symfony\Component\HttpFoundation\JsonResponse;

class AuthController
{
    public function create()
    {
        return new JsonResponse([
            'token' => 'YmFyYXNoZWsuQ29tcGFueTpjcmVhdGUsdXBkYXRlLGRlbGV0ZTtWYWNhbmN5OmNyZWF0ZSx1cGRhdGUsZGVsZXRl'
        ]);
    }
}