<?php

namespace App\Controller\Api;

use App\Facade\Cache\CacheManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    public function __construct()
    {
    }

    #[Route('/test')]
    public function cache(CacheManager $cacheManager)
    {
        $t = $cacheManager->cache('123', 'test');
        return new JsonResponse(['data' => $t]);
    }
}
