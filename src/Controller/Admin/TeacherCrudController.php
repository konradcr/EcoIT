<?php

namespace App\Controller\Admin;

use App\Entity\Teacher;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class TeacherCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Teacher::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Formateur·trice')
            ->setEntityLabelInPlural('Formateurs·trices')
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
            EmailField::new('email'),
            TextField::new('firstName', 'Prénom'),
            TextField::new('lastName', 'Nom'),
            TextareaField::new('description', 'Description'),
            BooleanField::new('isApproved', 'Approuvé'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::NEW);
    }
}
