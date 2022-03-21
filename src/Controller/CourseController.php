<?php

namespace App\Controller;

use App\Repository\CourseRepository;
use App\Repository\LessonRepository;
use App\Repository\SectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CourseController extends AbstractController
{
    #[Route('/formations', name: 'app_courses')]
    public function index(CourseRepository $courseRepository): Response
    {
        $courses = $courseRepository->findAll();

        return $this->render('course/index.html.twig', [
            'courses' => $courses,
        ]);
    }

    #[Route('/formations/{id}', name: 'app_course_detail')]
    public function courseDetail(int $id, CourseRepository $courseRepository, SectionRepository $sectionRepository, LessonRepository $lessonRepository): Response
    {
        $course = $courseRepository->findOneBy(['id' => $id]);

        return $this->render('course/course_detail.html.twig', [
            'course' => $course,
        ]);
    }
}
