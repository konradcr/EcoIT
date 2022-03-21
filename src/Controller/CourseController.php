<?php

namespace App\Controller;

use App\Repository\CourseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CourseController extends AbstractController
{
    #[Route('/formations', name: 'app_courses')]
    public function index(CourseRepository $courseRepository): Response
    {
        return $this->render('course/index.html.twig', [
            'courses' => $courseRepository->findAll(),
        ]);
    }

    #[Route('/formations/{id}', name: 'app_course_detail')]
    public function courseDetail(int $id, CourseRepository $courseRepository): Response
    {
        return $this->render('course/course_detail.html.twig', [
            'course' => $courseRepository->findOneBy(['id' => $id]),
        ]);
    }
}
