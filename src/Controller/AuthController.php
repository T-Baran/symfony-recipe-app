<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class AuthController extends ApiController
{
    #[Route("/login_check", name: "login_check", methods: ["POST"])]
    public function getTokenUser(): void
    {
    }
}
