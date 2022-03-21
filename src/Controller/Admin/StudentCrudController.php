<?php

namespace App\Controller\Admin;

use App\Entity\Student;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AvatarField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class StudentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Student::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Apprenant')
            ->setEntityLabelInPlural('Apprenants')
            ->setEntityPermission('ROLE_ADMIN')
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            ImageField::new('profilePicture','Photo de profil')
                ->setUploadDir('/public/uploads/profile_pictures')
                ->setBasePath('/uploads/profile_pictures')
                ->setUploadedFileNamePattern('[timestamp]-[slug].[extension]'),
            TextField::new('pseudo'),
            EmailField::new('email'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions

            ->update(Crud::PAGE_INDEX, Action::NEW,
                fn (Action $action) => $action->setIcon('fas fa-user-plus'))
            ;
    }

}
