<?php

namespace App\Controller\Page\Embedded;

use App\Service\TagService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class NavbarController extends AbstractController
{
    public function get(TagService $tagService): Response
    {
        $tags = $tagService->getTopTags();
        return $this->render('navbar.html.twig', [
            'tags' => $tags
        ]);
    }
}
