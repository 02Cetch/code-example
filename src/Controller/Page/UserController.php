<?php

namespace App\Controller\Page;

use App\Exception\ServiceException;
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
    public function viewPage(string $nickname, UserService $userService): Response
    {
        $user = $userService->getUserByNickname($nickname);
        return $this->render('pages/profile.html.twig', [
            'page_title' => "RuLeak | {$user->getNickname()}",
            'user' => $user
        ]);
    }
}
