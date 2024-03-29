<?php

namespace App\Controller\Api;

use App\Exception\Service\NotFoundServiceException;
use App\Service\TagService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/user', name: 'api_user')]
class UserController extends AbstractApiController
{
    public function __construct(private readonly TagService $tagService)
    {
    }

    #[Route('/{userId}/tags', name: '_tags', methods: ['GET'])]
    public function getUserTags(int $userId): Response
    {
        try {
            $tags = $this->tagService->getTagsQuantityByUserId($userId);
        } catch (NotFoundServiceException $e) {
            $tags = $this->tagService->getMockTagsCount();
        }
        return $this->respond('Tag list', $tags);
    }
}
