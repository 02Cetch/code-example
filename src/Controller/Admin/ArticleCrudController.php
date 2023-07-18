<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Factory\ArticleFactory;
use App\Factory\ImageFactory;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ArticleCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ArticleFactory $articleFactory
    )
    {
    }

    public static function getEntityFqcn(): string
    {
        return Article::class;
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
        if ($entityInstance->getMainImagePath()) {
            $imagePath = $this->getParameter('image_path') . $entityInstance->getMainImagePath();
            $image = (new ImageFactory())->create($imagePath);

            $entityManager->persist($image);
            $entityManager->flush();

            $entityInstance->setMainImage($image);
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
            IntegerField::new('id')->onlyOnIndex(),
            TextField::new('title'),
            ImageField::new('main_image_path', 'Главная картинка')
                ->setUploadDir($this->getParameter('image_path'))
                ->setUploadedFileNamePattern(function (UploadedFile $file): string {
                    return md5($file->getBasename()) . '_' . time() . ".{$file->guessExtension()}";
                })
                ->onlyOnForms(),
            AssociationField::new('tags', 'Tags'),
            TextField::new('slug', 'Слаг статьи (генерируется автоматически, если оставить пустым)')
                ->hideOnIndex(),
            TextEditorField::new(
                'text_short',
                'Короткий текст статьи (генерируется автоматически, если оставить пустым)'
            )->setRequired(false),
            TextEditorField::new('text', 'Текст статьи')
                ->hideOnIndex()->setFormType(CKEditorType::class),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig');
    }
}
