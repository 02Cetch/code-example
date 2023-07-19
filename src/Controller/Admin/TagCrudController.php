<?php

namespace App\Controller\Admin;

use App\Entity\Tag;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TagCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Tag::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('name'),
            TextField::new('title'),
            TextField::new('link'),

            // weight
            IntegerField::new('weight')
                ->setRequired(false)
                ->hideWhenCreating(),
            IntegerField::new('weight')
                ->setRequired(false)
                ->setFormTypeOption('data', Tag::DEFAULT_WEIGHT)
                ->onlyWhenCreating(),
        ];
    }

}
