<?php

namespace App\Controller\Api;

use App\Exception\ServiceException;
use App\Service\TagService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/user', name: 'api_user')]
class UserController extends AbstractApiController
{
    public function __construct(private readonly TagService $tagService)
    {
    }

    #[Route('/tags/{userId}', name: 'api_user_tags', methods: ['GET'])]
    public function getUserTags(int $userId): Response
    {
        try {
            $tags = $this->tagService->getTagsCountByUserId($userId);
        } catch (ServiceException $e) {
            $tags = $this->tagService->getMockTagsCount();
        }
        return $this->response($tags);
    }
}
