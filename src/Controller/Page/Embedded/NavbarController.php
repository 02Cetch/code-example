<?php

namespace App\Controller\Page\Embedded;

use App\Service\TagService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
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

    public function getSearch(
        RequestStack $requestStack
    ): Response {
        $request = $requestStack->getMainRequest();

        if ($request->get('_route') == 'search_view') {
            $searchQuery = $request->get('query') ?? '';

            return $this->render('/searchInput.html.twig', [
                'searchQuery' => $searchQuery
            ]);
        }
        return $this->render('/searchInput.html.twig');
    }
}
