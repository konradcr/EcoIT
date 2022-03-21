<?php

namespace App\Controller\Admin;

use App\Entity\StudyMaterial;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class StudyMaterialCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return StudyMaterial::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Ressource')
            ->setEntityLabelInPlural('Ressources')
            ->setEntityPermission('ROLE_TEACHER')
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title', 'Nom'),
            AssociationField::new('lesson', 'Module'),
            TextField::new('path', 'Ressource')
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions

            ->update(Crud::PAGE_INDEX, Action::NEW,
                fn (Action $action) => $action->setIcon('fas fa-plus'))
            ;
    }
}
