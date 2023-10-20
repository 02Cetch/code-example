<?php

namespace App\Controller\Page;

use App\Exception\Service\ServiceException;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user', name: 'user')]
class UserController extends AbstractController
{
    /**
     * @throws ServiceException
     */
    #[Route('/{nickname}', name: '_view')]
    public function view(string $nickname, UserService $userService): Response
    {
        $user = $userService->getUserByNickname($nickname);
        return $this->render('pages/profile.html.twig', [
            'page_title' => "{$this->getParameter('app.name')} | {$user->getNickname()}",
            'user' => $user
        ]);
    }
}
