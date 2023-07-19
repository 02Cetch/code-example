<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Tag;
use App\Entity\User;
use App\Entity\UserSkill;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractDashboardController
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    #[Route('/admin', name: 'admin_index')]
    public function index(): Response
    {
         $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
         return $this->redirect($adminUrlGenerator->setController(UserArticleCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Ruleak')->renderSidebarMinimized()->renderContentMaximized();
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToCrud('Мои статьи', 'fa-solid fa-newspaper', Article::class)
                ->setPermission('ROLE_WRITER')
                ->setController(UserArticleCrudController::class),
            MenuItem::linkToCrud('Все статьи', 'fas fa-list', Article::class)
                ->setPermission('ROLE_ADMIN')
                ->setController(ArticleCrudController::class),
            MenuItem::linkToCrud('Теги', 'fas fa-tag', Tag::class),
            MenuItem::linkToCrud('Навыки', 'fa-solid fa-pen', UserSkill::class),
            MenuItem::linkToCrud('Пользователи', 'fas fa-user', User::class)
                ->setPermission('ROLE_ADMIN'),
        ];
    }
}
