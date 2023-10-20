<?php

namespace App\Controller\Page;

use App\Exception\Repository\NotFoundRepositoryException;
use App\Facade\ArticleFacade;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/search', name: 'search')]
class SearchController extends AbstractController
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly ArticleFacade $articleFacade
    ) {
    }

    /**
     * @throws Exception
     */
    #[Route(path: '/', name: '_view', methods: ['GET'])]
    public function view(): Response
    {
        $searchQuery = $this->requestStack->getCurrentRequest()->get('query');
        $articles = $this->articleFacade->getArticlesBySearchQuery($searchQuery);

        return $this->render('/pages/search.html.twig', [
            'page_title' => "{$this->getParameter('app.name')} | Поиск",
            'articles' => $articles
        ]);
    }
}
