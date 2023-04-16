<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\ErrorManager;
use App\Service\FormManagers\UserManager;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use OpenApi\Attributes as OA;


#[Route('/api/users')]
class UserController extends ApiController
{
    public function __construct(private UserRepository $userRepository, private UserManager $userManager, private ErrorManager $errorManager, private SerializerInterface $serializer)
    {
        parent::__construct($this->errorManager);
    }

    #[Route('', name: 'user_index', methods: 'GET')]
    #[OA\Response(
        response: 200,
        description: 'Returns the list of users',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: User::class, groups: ['user']))
        )
    )]
    #[OA\Tag(name: 'User')]
    #[Security(name: 'Bearer')]
    public function index():JsonResponse
    {
        $users = $this->userRepository->findAll();
        $data = $this->serializer->serialize($users, 'json',
            ['groups' =>['user']]);
        return $this->response($data,[],true);
    }

    #[Route('', name: 'user_register', methods: ['POST'])]
    #[OA\Response(
        response: 201,
        description: 'successful response',
        headers: [new OA\Header(
            header: 'Location',
            description: 'Use this url to query for your ingredient',
            schema: new OA\Schema(type: 'string')
        )]
    )]
    #[OA\Response(
        response: 400,
        description: 'bad response'
    )]
    #[OA\RequestBody(
        description: 'Ingredient data structure',
        required: true,
        content: new Model(type: UserType::class)
    )]
    #[OA\Tag(name: 'User')]
    #[Security(name: 'Bearer')]
    public function register(Request $request): JsonResponse
    {
        return $this->handleForm($request, $this->userManager);
    }

    #[Route('/{id}', name: 'user_show', methods: 'GET')]
    #[OA\Response(
        response: 200,
        description: 'Returns the User with specified Id',
        content: new Model(type: User::class, groups: ['user','user_detail'])
    )]
    #[OA\Tag(name: 'User')]
    #[Security(name: 'Bearer')]
    public function show(User $user): JsonResponse
    {
        $data = $this->serializer->serialize($user, 'json',
            ['groups' => ['user', 'user_detail']]);
        return $this->response($data, [], true);
    }

    #[Route('/{id}', name: 'user_modify', methods: ['PUT', 'PATCH'])]
    #[OA\Response(
        response: 201,
        description: 'successful response',
        headers: [new OA\Header(
            header: 'Location',
            description: 'Use this url to query for your ingredient',
            schema: new OA\Schema(type: 'string')
        )]
    )]
    #[OA\Response(
        response: 400,
        description: 'bad response'
    )]
    #[OA\RequestBody(
        description: 'User data structure',
        required: true,
        content: new Model(type: UserType::class)
    )]
    #[OA\Tag(name: 'User')]
    #[Security(name: 'Bearer')]
    public function modify(Request $request): JsonResponse
    {
        return $this->handleForm($request,$this->userManager);
    }

    #[OA\Response(
        response: 204,
        description: 'successful response'
    )]
    #[OA\Response(
        response: 405,
        description: 'bad response'
    )]
    #[OA\Tag(name: 'User')]
    #[Security(name: 'Bearer')]
    #[Route('/{id}', name: 'user_delete', methods: ['DELETE'])]
    public function delete(User $user): JsonResponse
    {
        $this->userRepository->remove($user, true);
        return $this->respondNoContent();
    }
}
