<?php

namespace App\Controller\Admin;

use App\Entity\UserSkill;
use App\Factory\ImageFactory;
use App\Factory\UserSkillFactory;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\String\Slugger\AsciiSlugger;

class UserSkillCrudController extends AbstractCrudController
{
    public function __construct(private readonly UserSkillFactory $skillFactory)
    {
    }

    public static function getEntityFqcn(): string
    {
        return UserSkill::class;
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param UserSkill $entityInstance
     * @return void
     */
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $article = $this->skillFactory->create(
            $entityInstance->getTitle(),
            $entityInstance->getName(),
            $entityInstance->getWeight()
        );

        parent::persistEntity($entityManager, $article);
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param UserSkill $entityInstance
     * @return void
     */
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance->getName()) {
            $slugger = new AsciiSlugger('ru_RU');
            $entityInstance->setName($slugger->slug($entityInstance->getTitle())->lower());
        }

        parent::updateEntity($entityManager, $entityInstance);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('title', 'Название навыка'),

            TextField::new('name', 'Имя параметра')->onlyOnIndex(),
            TextField::new('name', 'Имя параметра (генерируется автоматически, если оставить пустым)')
                ->setRequired(false)
                ->hideOnIndex(),

            // weight
            IntegerField::new('weight')
                ->setRequired(false)
                ->hideWhenCreating(),
            IntegerField::new('weight')
                ->setRequired(false)
                ->setFormTypeOption('data', UserSkill::DEFAULT_WEIGHT)
                ->onlyWhenCreating(),
        ];
    }

}
