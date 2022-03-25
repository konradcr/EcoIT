<?php

namespace App\Controller\Admin;

use App\Entity\Lesson;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;

class LessonCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Lesson::class;
    }

    public function __construct(EntityRepository $entityRepository)
    {
        $this->entityRepository = $entityRepository;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $response = $this->get(EntityRepository::class)->createQueryBuilder($searchDto, $entityDto, $fields, $filters);
        if ($this->getUser() instanceof Teacher) {
            $response->join('entity.section', 'section');
            $response->join('section.course', 'course');
            $response->andWhere('course.teacher = :user');
            $response->setParameter('user', $this->getUser());
        }
        return $response;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Module')
            ->setEntityLabelInPlural('Modules')
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title', 'Titre'),
            AssociationField::new('section', 'Section')
                ->setQueryBuilder(function ($queryBuilder) {
                    return $queryBuilder
                        ->join('entity.course', 'course')
                        ->andWhere('course.teacher = :user')
                        ->setParameter('user', $this->getUser());
                }),
            IntegerField::new('orderInSection', 'Ordre'),
            UrlField::new('video', 'Lien vidéo'),
            AssociationField::new('studyMaterials', 'Ressources')
                ->hideOnForm()
                ->setTemplatePath('admin/fields/sections.html.twig'),
            TextEditorField::new('lessonContent', 'Cours')
                ->hideOnIndex(),
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
