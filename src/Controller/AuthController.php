<?php

namespace App\Controller;

use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;


#[Route('/api')]
class AuthController extends ApiController
{
    #[Route('/login_check', name: 'login_check', methods: ['POST'])]
    #[OA\RequestBody(
        description: 'Ingredient data structure',
        required: true,
        content: new OA\MediaType(
            mediaType:'application/json',
            schema: new OA\Schema(
                title: 'login',
                description: 'User data for login',
                properties: [
                    new OA\Property(
                        property:'email',
                        type: 'string'
                    ),
                    new OA\Property(
                        property:'password',
                        type: 'string'
                    )
            ],
                type: 'object'
            )
        )
    )]
    public function getTokenUser(): void
    {
    }

    #[Route('/token/refresh', name: 'token_refresh')]
    public function refreshToken():void
    {
    }

    #[Route('/token/invalidate', name: 'token_invalidate')]
    public function logout():void
    {
    }
}
