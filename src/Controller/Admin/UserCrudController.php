<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Factory\UserFactory;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormInterface;

class UserCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly UserFactory $userFactory
    )
    {
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param User $entityInstance
     * @return void
     */
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $date = \DateTimeImmutable::createFromFormat('U', time());

        $entityInstance->setCreatedAt($date);
        $entityInstance->setUpdatedAt($date);

        if ($entityInstance->getVirtualRole()) {
            $entityInstance->setRoles([$entityInstance->getVirtualRole()]);
        }
        parent::updateEntity($entityManager, $entityInstance);
    }

    /**
     * Modifies the User instance inside @var EntityDto $entityDto to set the value for virtual field
     */
    public function createEditForm(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormInterface
    {
        $entity = $entityDto->getInstance();

        $role = $entity->getRoles()[0] ?? null;
        if ($role) {
            $entity->setVirtualRole($role);
        }
        return parent::createEditForm($entityDto, $formOptions, $context);
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param User $entityInstance
     * @return void
     */
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $user = $this->userFactory->create(
            $entityInstance->getEmail(),
            $entityInstance->getNickname(),
            $entityInstance->getPlainPassword(),
            $entityInstance->getVirtualRole()
        );
        parent::persistEntity($entityManager, $user);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),

            TextField::new('nickname'),
            EmailField::new('email'),

            ChoiceField::new('virtual_role', 'Role')->setChoices(User::ALLOWED_ROLES)
                ->setRequired(true)
                ->hideOnIndex(),

            ArrayField::new('roles', 'Role')
                ->hideOnForm(),

            TextField::new('plain_password', 'Пароль')->setFormType(PasswordType::class)
                ->setRequired(true)
                ->onlyWhenCreating(),
            TextField::new('plain_password', 'Пароль')->setFormType(PasswordType::class)
                ->setRequired(false)
                ->onlyWhenUpdating(),
        ];
    }
}
