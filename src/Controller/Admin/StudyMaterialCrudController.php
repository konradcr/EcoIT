<?php

namespace App\Controller\Admin;

use App\Entity\StudyMaterial;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\FileUploadType;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;

class StudyMaterialCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return StudyMaterial::class;
    }

    public function __construct(EntityRepository $entityRepository)
    {
        $this->entityRepository = $entityRepository;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $response = $this->get(EntityRepository::class)->createQueryBuilder($searchDto, $entityDto, $fields, $filters);
        if ($this->getUser() instanceof Teacher) {
            $response->join('entity.lesson', 'lesson');
            $response->join('lesson.section', 'section');
            $response->join('section.course', 'course');
            $response->andWhere('course.teacher = :user');
            $response->setParameter('user', $this->getUser());
        }
        return $response;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Ressource')
            ->setEntityLabelInPlural('Ressources')
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title', 'Nom'),
            AssociationField::new('lesson', 'Module')
                ->setQueryBuilder(function ($queryBuilder) {
                    return $queryBuilder
                        ->join('entity.section', 'section')
                        ->join('section.course', 'course')
                        ->andWhere('course.teacher = :user')
                        ->setParameter('user', $this->getUser());
                }),
            ImageField::new('path','Ressource')
                ->setFormType(FileUploadType::class)
                ->setUploadDir('/public/uploads/study_materials')
                ->setBasePath('/uploads/study_materials')
                ->setUploadedFileNamePattern('[timestamp]-[slug].[extension]')
                ->hideOnIndex(),
            TextField::new('path')
                ->setTemplatePath('admin/fields/_study_material_link.html.twig')
                ->onlyOnIndex(),
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
