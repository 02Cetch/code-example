<?php

namespace App\Controller\Page;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends AbstractController
{
    #[Route('/tags', name: 'tag_index')]
    public function index(): Response
    {
        return $this->render('pages/index.html.twig', [
            'page_title' => 'RuLeak — блог о технологиях и не только',
        ]);
    }
}
