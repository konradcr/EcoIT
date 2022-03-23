<?php

namespace App\Controller;

use App\Repository\CourseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(CourseRepository $courseRepository): Response
    {
        $last3courses = $courseRepository->findBy([], ['creationDate' => 'DESC'], 3);

        return $this->render('home/index.html.twig', [
            'lastCourses' => $last3courses
        ]);
    }
}