<?php

namespace App\Controller\Admin;

use App\Entity\Course;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CourseCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Course::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Formation')
            ->setEntityLabelInPlural('Formations')
            ->setEntityPermission('ROLE_TEACHER')
            ;
    }

    public function createEntity(string $entityFqcn)
    {
        $course = new Course();
        $course->setTeacher($this->getUser());
        $course->setCreationDate(new \DateTime());

        return $course;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            ImageField::new('coursePicture','Image')
                ->setUploadDir('/public/uploads/course_pictures')
                ->setBasePath('/uploads/course_pictures'),
            TextField::new('title', 'Titre'),
            TextareaField::new('description', 'Description'),
            DateField::new('creationDate', 'Date de création')->hideOnForm(),
            IntegerField::new('students', 'Nombre de participants')->hideOnForm()
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
