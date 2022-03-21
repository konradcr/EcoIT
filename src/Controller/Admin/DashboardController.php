<?php

namespace App\Controller\Admin;

use App\Entity\Course;
use App\Entity\Student;
use App\Entity\Teacher;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin_dashboard')]
    public function index(): Response
    {
        // return parent::index();

        // Option 1. You can make your welcome redirect to some common page of your backend
        //
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(TeacherCrudController::class)->generateUrl());

        // Option 2. You can make your welcome redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper welcome with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-welcome.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('EcoIT')
            ->setFaviconPath('img/favicon.ico')
            ->setTranslationDomain('admin')
            ;
    }

    public function configureMenuItems(): iterable
    {
        // yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        return [
            MenuItem::linktoRoute('Retour à l\'accueil', 'fa fa-home', 'app_dashboard'),
            MenuItem::linkToLogout('Déconnexion', 'fa fa-sign-out'),

            MenuItem::section('Utilisateurs'),
            MenuItem::linkToCrud('Formateurs', 'fa fa-chalkboard-teacher', Teacher::class),
            MenuItem::linkToCrud('Apprenants', 'fa fa-user-graduate', Student::class),

            MenuItem::section('Formations'),
            MenuItem::linkToCrud('Formations', 'fa fa-graduation-cap', Course::class),
        ];
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user);
    }

    public function configureCrud(): Crud
    {
        return Crud::new()
            // this defines the configuration for all CRUD controllers
            ->showEntityActionsInlined()
            ;
    }
}
