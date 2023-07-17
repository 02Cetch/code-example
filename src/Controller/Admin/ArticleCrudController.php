<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Factory\ArticleFactory;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\String\Slugger\AsciiSlugger;

class ArticleCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly ArticleFactory $articleFactory
    )
    {
    }

    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param Article $entityInstance
     * @return void
     */
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $date = \DateTimeImmutable::createFromFormat('U', time());
        $article = $this->articleFactory->create(
            $entityInstance->getTitle(),
            $entityInstance->getText(),
            $date,
            $entityInstance->getSlug(),
            $entityInstance->getTextShort()
        );
        parent::persistEntity($entityManager, $article);
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param Article $entityInstance
     * @return void
     */
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance->getSlug()) {
            $slugger = new AsciiSlugger('ru_RU');
            $entityInstance->setSlug($slugger->slug($entityInstance->getTitle())->lower() . "_" . uniqid());
        }

        $date = \DateTimeImmutable::createFromFormat('U', time());
        $entityInstance->setCreatedAt($date);
        $entityInstance->setUpdatedAt($date);
        parent::updateEntity($entityManager, $entityInstance);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IntegerField::new('id')->onlyOnIndex(),
            TextField::new('title'),
            TextField::new('slug', 'Слаг статьи (генерируется автоматически, если оставить пустым)')
                ->hideOnIndex(),
            TextEditorField::new(
                'text_short',
                'Короткий текст статьи (генерируется автоматически, если оставить пустым)'
            )->setRequired(false),
            TextEditorField::new('text', 'Текс статьи')->hideOnIndex(),
        ];
    }
}
