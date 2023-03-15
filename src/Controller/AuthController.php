<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AuthController extends ApiController
{
    public function __construct(private EntityManagerInterface $em, private UserRepository $userRepository)
    {
    }

    #[Route("/api/register", name: "register", methods: ["POST"])]
    public function register(Request $request, UserPasswordHasherInterface $encoder): JsonResponse
    {
        $request = $this->transformJsonBody($request);
        $username = $request->get('username');
        $password = $request->get('password');
        $email = $request->get('email');

        if (empty($username) || empty($password) || empty($email)) {
            return $this->respondValidationError("Invalid Username or Password or Email");
        }

        $user = new User();
        $user->setPassword($encoder->hashPassword($user, $password));
        $user->setEmail($email);
        $user->setUsername($username);
        $this->em->persist($user);
        $this->em-> flush();
        return $this->respondWithSuccess(sprintf('User %s successfully created', $user->getUsername()));
    }

    #[Route("/api/login_check", name:"login_check", methods:["POST"])]
    public function getTokenUser(): void
    {
    }

}
