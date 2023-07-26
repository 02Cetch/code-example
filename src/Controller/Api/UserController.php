<?php

namespace App\Controller\Api;

use App\Exception\Repository\NotFoundRepositoryException;
use App\Service\TagService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/user', name: 'api_user')]
class UserController extends AbstractApiController
{
    public function __construct(private readonly TagService $tagService)
    {
    }

    #[Route('/tags/{userId}', name: '_tags', methods: ['GET'])]
    public function getUserTags(int $userId): Response
    {
        try {
            $tags = $this->tagService->getTagsQuantityByUserId($userId);
        } catch (NotFoundRepositoryException $e) {
            $tags = $this->tagService->getMockTagsCount();
        }
        return $this->response($tags);
    }
}
