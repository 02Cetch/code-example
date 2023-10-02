<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractApiController extends AbstractController
{
    /**
     * @var int HTTP status code - 200 (OK) by default
     */
    protected int $statusCode = 200;

    /**
     * Gets the value of statusCode.
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Sets the value of statusCode.
     *
     * @param int $statusCode the status code
     *
     * @return static
     */
    protected function setStatusCode(int $statusCode): static
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Returns a JSON response.
     *
     * @param array $data
     * @param array $headers
     *
     * @return JsonResponse
     */
    public function response(array $data, array $headers = []): JsonResponse
    {
        $data = [
            'code' => $this->getStatusCode(),
            'data' => $data
        ];
        return new JsonResponse($data, $this->getStatusCode(), $headers);
    }

    /**
     * Sets an error message and returns a JSON response.
     *
     * @param string $errors
     * @param array $headers
     *
     * @return JsonResponse
     */
    public function respondWithErrors(string $errors, array $headers = []): JsonResponse
    {
        $data = [
            'code' => $this->getStatusCode(),
            'data' => [
                'errors' => $errors
            ],
        ];
        return new JsonResponse($data, $this->getStatusCode(), $headers);
    }

    /**
     * Sets an error message and returns a JSON response.
     *
     * @param string $success
     * @param array $headers
     *
     * @return JsonResponse
     */
    public function respondWithSuccess(string $success, array $headers = []): JsonResponse
    {
        $data = [
            'code' => $this->getStatusCode(),
            'data' => [
                'success' => $success
            ],
        ];

        return new JsonResponse($data, $this->getStatusCode(), $headers);
    }

    /**
     * Returns a 401 Unauthorized http response.
     *
     * @param string $message
     *
     * @return JsonResponse
     */
    public function respondUnauthorized(string $message = 'Not authorized!'): JsonResponse
    {
        return $this->setStatusCode(401)->respondWithErrors($message);
    }

    /**
     * Returns a 422 Unprocessible Entity.
     *
     * @param string $message
     *
     * @return JsonResponse
     */
    public function respondValidationError(string $message = 'Validation errors'): JsonResponse
    {
        return $this->setStatusCode(422)->respondWithErrors($message);
    }

    /**
     * Returns a 404 Not Found.
     *
     * @param string $message
     *
     * @return JsonResponse
     */
    public function respondNotFound(string $message = 'Not found!'): JsonResponse
    {
        return $this->setStatusCode(404)->respondWithErrors($message);
    }

    /**
     * Returns a 201 Created.
     *
     * @param array $data
     *
     * @return JsonResponse
     */
    public function respondCreated(array $data = []): JsonResponse
    {
        return $this->setStatusCode(201)->response($data);
    }

    /**
     * allows to accept JSON payloads in POST requests
     */
    protected function transformJsonBody(Request $request): Request
    {
        $data = json_decode($request->getContent(), true);

        if (null === $data) {
            return $request;
        }

        $request->request->replace($data);
        return $request;
    }
}
