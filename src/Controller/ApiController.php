<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ApiController extends AbstractController
{
    protected int $statusCode = 200;

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    protected function setStatusCode(int $statusCode): ApiController
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function response(string|null $data, array $headers = [], bool $json = false): JsonResponse
    {
        return new JsonResponse($data, $this->getStatusCode(), $headers, $json);
    }

    public function respondWithErrors(string|array $errors, array $headers = []): JsonResponse
    {
        return new JsonResponse($errors, $this->getStatusCode(), $headers);
    }

    public function respondWithSuccess(string|array $success, array $headers = []): JsonResponse
    {
        return new JsonResponse($success, $this->getStatusCode(), $headers);
    }

    public function respondUnauthorized(string $message = 'Not authorized!'): JsonResponse
    {
        return $this->setStatusCode(401)->respondWithErrors($message);
    }

    public function respondValidationError(string $message = 'Validation errors'): JsonResponse
    {
        return $this->setStatusCode(422)->respondWithErrors($message);
    }

    public function respondNotFound(string $message = 'Not found!'): JsonResponse
    {
        return $this->setStatusCode(404)->respondWithErrors($message);
    }

    public function respondCreated(string|null $data = null): JsonResponse
    {
        return $this->setStatusCode(201)->response($data);
    }

    public function respondNoContent(string|null $data = null): JsonResponse
    {
        return $this->setStatusCode(204)->response($data);
    }

    protected function transformJsonBody(Request $request): Request
    {
        $data = json_decode($request->getContent(), true);
        if ($data === null) {
            return $request;
        }
        $request->request->replace($data);
        return $request;
    }

    protected function returnTransformedData(Request $request): array
    {
        $body = $request->getContent();
        return json_decode($body, true);
    }

}