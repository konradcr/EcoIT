<?php

namespace App\Controller\Admin;

use App\Entity\Question;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;

class QuestionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Question::class;
    }

    public function __construct(EntityRepository $entityRepository)
    {
        $this->entityRepository = $entityRepository;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $response = $this->get(EntityRepository::class)->createQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $response->join('entity.quiz', 'quiz');
        $response->join('quiz.section', 'section');
        $response->join('section.course', 'course');
        $response->andWhere('course.teacher = :user');
        $response->setParameter('user', $this->getUser());
        return $response;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Question')
            ->setEntityLabelInPlural('Questions')
            ;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('question', 'Question'),
            AssociationField::new('quiz', 'Quiz')
                ->setQueryBuilder(function ($queryBuilder) {
                    return $queryBuilder
                        ->join('entity.section', 'section')
                        ->join('section.course', 'course')
                        ->andWhere('course.teacher = :user')
                        ->setParameter('user', $this->getUser());
                }),
            AssociationField::new('answers', 'Réponses')->hideOnForm(),
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
