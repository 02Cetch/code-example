<?php

namespace App\Controller\Page;

use App\Facade\ArticleFacade;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path:'/tag', name: 'tag')]
class TagController extends AbstractController
{
    public function __construct(private readonly RequestStack $request)
    {
    }

    #[Route(path:'/{tagLink}', name: '_view')]
    public function view(string $tagLink, ArticleFacade $facade): Response
    {
        $articlePaginationResponse = $facade->getPaginatedArticlesByTagLinkAndRequestStack(
            $tagLink,
            $this->request
        );
        return $this->render('pages/tag.html.twig', [
            'page_title' => "{$this->getParameter('app.name')} | {$articlePaginationResponse->getTagTitle()}",
            'tag_title' => $articlePaginationResponse->getTagTitle(),
            'articles' => $articlePaginationResponse->getPagination()
        ]);
    }
}
