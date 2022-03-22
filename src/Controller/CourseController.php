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
    public function courseDetail(int $id, CourseRepository $courseRepository): Response
    {
        $course = $courseRepository->findOneBy(['id' => $id]);

        return $this->render('course/course_detail.html.twig', [
            'course' => $course,
        ]);
    }

    #[Route('/formations/{idCourse}/lesson/{idLesson}', name: 'app_lesson_detail')]
    public function lessonDetail(int $idCourse, int $idLesson, CourseRepository $courseRepository, LessonRepository $lessonRepository): Response
    {
        $course = $courseRepository->findOneBy(['id' => $idCourse]);
        $lesson = $lessonRepository->findOneBy(['id' => $idLesson]);

        return $this->render('course/lesson_detail.html.twig', [
            'course' => $course,
            'lesson' => $lesson
        ]);
    }
}
