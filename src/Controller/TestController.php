<?php

namespace App\Controller;

use App\Repository\CourseRepository;
use App\Repository\LessonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/test/course/{idCourse}/lesson/{idLesson}', name: 'app_test')]
    public function index(int $idCourse, int $idLesson, CourseRepository $courseRepository, LessonRepository $lessonRepository): Response
    {
        $course = $courseRepository->findOneBy(['id' => $idCourse]);
        $lesson = $lessonRepository->findOneBy(['id' => $idLesson]);


        return $this->render('test/index.html.twig', [
            'lesson' => $lesson,
            'contents' => $course->getContents(),
        ]);
    }
}
