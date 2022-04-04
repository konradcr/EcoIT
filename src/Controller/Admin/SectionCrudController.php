<?php

namespace App\Controller\Admin;

use App\Entity\Section;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;

class SectionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Section::class;
    }

    public function __construct(EntityRepository $entityRepository)
    {
        $this->entityRepository = $entityRepository;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        // query only sections from the teacher connected
        $response = $this->get(EntityRepository::class)->createQueryBuilder($searchDto, $entityDto, $fields, $filters);
        if ($this->getUser() instanceof Teacher) {
            $response->join('entity.course', 'course');
            $response->andWhere('course.teacher = :user');
            $response->setParameter('user', $this->getUser());
        }
        return $response;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Section')
            ->setEntityLabelInPlural('Sections')
            ;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title', 'Titre'),
            AssociationField::new('course', 'Formation')
                ->setQueryBuilder(function ($queryBuilder) {
                    return $queryBuilder
                        ->andWhere('entity.teacher = :user')
                        ->setParameter('user', $this->getUser());
                }),
            IntegerField::new('orderInCourse', 'Ordre'),
            AssociationField::new('lessons', 'Modules')
                ->hideOnForm()
                ->setTemplatePath('admin/fields/_sections.html.twig'),
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
