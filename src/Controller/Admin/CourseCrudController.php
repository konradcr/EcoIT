<?php

namespace App\Controller\Admin;

use App\Entity\Course;
use App\Entity\Teacher;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;

class CourseCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Course::class;
    }

    public function __construct(EntityRepository $entityRepository)
    {
        $this->entityRepository = $entityRepository;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        // query only courses from the teacher connected
        $response = $this->get(EntityRepository::class)->createQueryBuilder($searchDto, $entityDto, $fields, $filters);
        if ($this->getUser() instanceof Teacher) {
            $response->andWhere('entity.teacher = :user');
            $response->setParameter('user', $this->getUser());
        }
        return $response;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Formation')
            ->setEntityLabelInPlural('Formations')
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
            AssociationField::new('teacher', 'Enseignant')
                ->setPermission('ROLE_ADMIN'),
            ImageField::new('coursePicture','Image')
                ->setUploadDir('/public/uploads/course_pictures')
                ->setBasePath('/uploads/course_pictures')
                ->setUploadedFileNamePattern('[timestamp]-[slug].[extension]'),
            TextField::new('title', 'Titre'),
            TextareaField::new('description', 'Description'),
            DateField::new('creationDate', 'Date de création')
                ->hideOnForm(),
            AssociationField::new('sections', 'Sections')
                ->hideOnForm()
                ->setTemplatePath('admin/fields/_sections.html.twig'),
            AssociationField::new('studentsCourseProgress', 'Nombre de participants')->hideOnForm()
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->setPermission('new', 'ROLE_TEACHER')
            ->setPermission('edit', 'ROLE_TEACHER')
            ->update(Crud::PAGE_INDEX, Action::NEW,
                fn (Action $action) => $action->setIcon('fas fa-plus'))
            ;
    }
}
