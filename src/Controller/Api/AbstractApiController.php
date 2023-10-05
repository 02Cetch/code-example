<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AbstractApiController extends AbstractController
{
    public function respondWithCreated($data): JsonResponse
    {
        return $this->respond(Response::$statusTexts[Response::HTTP_CREATED], code: Response::HTTP_CREATED);
    }

    public function respond(string $message, $data = [], $code = Response::HTTP_OK): JsonResponse
    {
        $data = [
            'code' => $code,
            'message' => $message,
            'data' => $data
        ];
        return $this->json($data, $code);
    }
}
