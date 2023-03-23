<?php

namespace App\Controller;

use App\DTO\UserDTO;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\ErrorManager;
use App\Service\UserManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/users')]
class UserController extends ApiController
{
    public function __construct(private UserRepository $userRepository, private UserManager $userManager, private ErrorManager $errorManager)
    {
    }

    #[Route('/register', name: 'user_register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        return $this->handleForm($request);
    }

    #[Route('/{id}', name: 'user_modify', methods: ['PUT', 'PATCH'])]
    public function modify(User $user, Request $request): JsonResponse
    {
        return $this->handleForm($request, $user);
    }

    #[Route('/{id}', name: 'user_delete', methods: ['DELETE'])]
    public function delete(User $user): JsonResponse
    {
        $this->userRepository->remove($user, true);
        return $this->respondNoContent();
    }

//    private function handleForm(Request $request, ?User $user = null): JsonResponse
//    {
//        $data = $this->returnTransformedData($request);
//        $clearMissing = $request->getMethod() !== 'PATCH';
//        $userDTO = new UserDTO();
//        $form = $this->createForm(UserType::class, $userDTO);
//        $form->submit($data, $clearMissing);
//        if ($form->isValid()) {
//            $statusCode = $request->getMethod() === 'POST' ? '201' : '204';
//            $this->userManager->handleUser($userDTO, $user);
//            $this->setStatusCode($statusCode);
//            return $this->response(sprintf('User %s successfully %s', $this->userManager->getUsername(),
//                $statusCode === '201' ? 'created' : 'modified'
//            ));
//        }
//        $this->setStatusCode(400);
//        return $this->respondWithErrors($this->errorManager->getErrorsFromForm($form), []);
//    }
}
