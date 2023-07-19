<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Factory\ArticleFactory;
use App\Factory\ImageFactory;
use App\Helper\ReadTimeEstimateHelper;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[IsGranted('ROLE_WRITER')]
class UserArticleCrudController extends AbstractCrudController
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

    public function configureActions(Actions $actions): Actions
    {
        $actions->add(Crud::PAGE_INDEX, Action::new('open', 'Открыть')
            ->linkToRoute('article_read', function (Article $article) {
                return ['slug' => $article->getSlug()];
            })
            ->setHtmlAttributes(['target' => '_blank'])
        );
        return $actions;
    }

    /**
     * Displays only user's articles
     */
    public function createIndexQueryBuilder(
        SearchDto $searchDto,
        EntityDto $entityDto,
        FieldCollection $fields,
        FilterCollection $filters
    ): QueryBuilder {
        /**
         * @var QueryBuilder $queryBuilder
         */
        $queryBuilder = $this->container->get(EntityRepository::class)->createQueryBuilder(
            $searchDto,
            $entityDto,
            $fields,
            $filters
        );

        $user = $this->getUser();

        // get generic alias
        $alias = $queryBuilder->getRootAliases()[0];

        $queryBuilder->join($alias . '.user', 'u');
        $queryBuilder->andWhere('u.id = '.$user->getId());
        return $queryBuilder;
    }

    /**
     * Modifies the Article instance inside @var EntityDto $entityDto to set the value for virtual field
     */
    public function createEditForm(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormInterface
    {
        /**
         * @var Article $entity
         */
        $entity = $entityDto->getInstance();

        if ($entity->getMainImage()) {
            $entity->setMainImagePath($entity->getMainImage()->getPath());
        }

        $estimator = new ReadTimeEstimateHelper($entity->getText());
        $entity->setMinRead($estimator->getMinutes());

        return parent::createEditForm($entityDto, $formOptions, $context);
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param Article $entityInstance
     * @return void
     */
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance->getMainImagePath()) {
            $imagePath = $this->getParameter('image_path') . $entityInstance->getMainImagePath();
            $image = (new ImageFactory())->create($imagePath);

            $entityManager->persist($image);
            $entityManager->flush();

            $entityInstance->setMainImage($image);
        }

        if ($entityInstance->getCoverImagePath()) {
            $imagePath = "{$this->getParameter('image_path')}/{$entityInstance->getCoverImagePath()}";
            $image = (new ImageFactory())->create($imagePath);

            $entityManager->persist($image);
            $entityManager->flush();

            $entityInstance->setCoverImage($image);
        }

        $date = \DateTimeImmutable::createFromFormat('U', time());
        $article = $this->articleFactory->create(
            $entityInstance->getTitle(),
            $entityInstance->getText(),
            $date,
            $entityInstance->getSlug(),
            $entityInstance->getTextShort()
        );

        $article->setUser($this->getUser());

        if ($entityInstance->getTags()) {
            foreach ($entityInstance->getTags() as $tag) {
                $article->addTag($tag);
            }
        }

        $estimator = new ReadTimeEstimateHelper($article->getText());
        $article->setMinRead($estimator->getMinutes());

        parent::persistEntity($entityManager, $article);
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param Article $entityInstance
     * @return void
     */
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance->getUser()->getId() != $this->getUser()->getId()) {
            return;
        }

        if ($entityInstance->getMainImagePath()) {
            $imagePath = "{$this->getParameter('image_path')}/{$entityInstance->getMainImagePath()}";
            $image = (new ImageFactory())->create($imagePath);

            $entityManager->persist($image);
            $entityManager->flush();

            $entityInstance->setMainImage($image);
        }

        if ($entityInstance->getCoverImagePath()) {
            $imagePath = "{$this->getParameter('image_path')}/{$entityInstance->getCoverImagePath()}";
            $image = (new ImageFactory())->create($imagePath);

            $entityManager->persist($image);
            $entityManager->flush();

            $entityInstance->setCoverImage($image);
        }

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
            IdField::new('id')->onlyOnIndex(),
            TextField::new('title'),

            ImageField::new('main_image_path', 'Главная картинка')
                ->setUploadDir("/public{$this->getParameter('image_path')}")
                ->setUploadedFileNamePattern(function (UploadedFile $file): string {
                    return md5($file->getBasename()) . '_' . time() . ".{$file->guessExtension()}";
                })
                ->onlyOnForms(),

            ImageField::new('cover_image_path', 'Превью')
                ->setUploadDir("/public{$this->getParameter('image_path')}")
                ->setUploadedFileNamePattern(function (UploadedFile $file): string {
                    return md5($file->getBasename()) . '_' . time() . ".{$file->guessExtension()}";
                })
                ->onlyOnForms(),

            AssociationField::new('tags', 'Tags'),

            TextField::new('slug', 'Слаг статьи (генерируется автоматически, если оставить пустым)')
                ->hideOnIndex(),

            // text short
            TextField::new(
                'text_short',
                'Короткий текст статьи'
            )
                ->setRequired(false)
                ->onlyOnIndex(),
            TextField::new(
                'text_short',
                'Короткий текст статьи (генерируется автоматически, если оставить пустым)'
            )
                ->setRequired(false)
                ->hideOnIndex(),

            TextEditorField::new('text', 'Текст статьи')
                ->hideOnIndex()
                ->setFormType(CKEditorType::class),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig');
    }
}
