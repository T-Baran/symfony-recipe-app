<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\ErrorManager;
use App\Service\FormManagers\UserManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/users')]
class UserController extends ApiController
{
    public function __construct(private UserRepository $userRepository, private UserManager $userManager, private ErrorManager $errorManager, private SerializerInterface $serializer)
    {
        parent::__construct($this->errorManager);
    }

    #[Route('', name: 'user_index', methods: 'GET')]
    public function index():JsonResponse
    {
        $users = $this->userRepository->findAll();
        $data = $this->serializer->serialize($users, 'json',
            ['groups' =>['user']]);
        return $this->response($data,[],true);
    }

    #[Route('', name: 'user_register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        return $this->handleForm($request, $this->userManager);
    }

    #[Route('/{id}', name: 'user_modify', methods: ['PUT', 'PATCH'])]
    public function modify(Request $request): JsonResponse
    {
        return $this->handleForm($request,$this->userManager);
    }

    #[Route('/{id}', name: 'user_delete', methods: ['DELETE'])]
    public function delete(User $user): JsonResponse
    {
        $this->userRepository->remove($user, true);
        return $this->respondNoContent();
    }
}
