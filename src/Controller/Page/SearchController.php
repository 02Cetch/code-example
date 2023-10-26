<?php

namespace App\Controller\Page;

use App\Dto\Request\Page\SearchViewRequest;
use App\Facade\BlogFacade;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/search', name: 'search')]
class SearchController extends AbstractController
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly BlogFacade $blogFacade
    ) {
    }

    /**
     * @throws Exception
     */
    #[Route(path: '/', name: '_view', methods: ['GET'])]
    public function view(#[MapQueryString] SearchViewRequest $request): Response
    {
        $articles = $this->blogFacade->getArticlesBySearchQuery($request->getQuery());

        return $this->render('/pages/search.html.twig', [
            'page_title' => "{$this->getParameter('app.name')} | Поиск",
            'articles' => $articles
        ]);
    }
}
