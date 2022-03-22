<?php

namespace App\Controller;

use App\Entity\CourseProgress;
use App\Entity\Student;
use App\Repository\CourseProgressRepository;
use App\Repository\CourseRepository;
use App\Repository\LessonRepository;
use App\Repository\StudentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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

    #[Route('/formation/{id}', name: 'app_course_detail')]
    public function courseDetail(int $id, CourseRepository $courseRepository, CourseProgressRepository $courseProgressRepository, StudentRepository $studentRepository): Response
    {
        $course = $courseRepository->findOneBy(['id' => $id]);
        $alreadyRegistered = false;

        if ($this->getUser() instanceof Student) {
            $student = $studentRepository->findOneBy(['id' => $this->getUser()->getId()]);
            $courseProgressExist = $courseProgressRepository->findBy(['course' => $course, 'student' => $student]);
            if ($courseProgressExist) {
                $alreadyRegistered = true;
            }
        }

        return $this->render('course/course_detail.html.twig', [
            'course' => $course,
            'alreadyRegistered' => $alreadyRegistered
        ]);
    }

    #[Route('/formation/{id}/rejoindre', name: 'app_register_course')]
    #[IsGranted('ROLE_USER')]
    public function registerCourse(int $id, CourseRepository $courseRepository, StudentRepository $studentRepository, EntityManagerInterface $entityManager): Response
    {

        if ($this->getUser() instanceof Student) {
            $student = $studentRepository->findOneBy(['id' => $this->getUser()->getId()]);
            $course = $courseRepository->findOneBy(['id' => $id]);

            $courseProgress = new CourseProgress();
            $courseProgress->setStudent($student);
            $courseProgress->setCourse($course);
            $courseProgress->setProgress(0);

            $entityManager->persist($courseProgress);
            $entityManager->flush();

        }

        return $this->redirectToRoute('app_course_detail', ['id' => $id]);
    }

    #[Route('/formation/{idCourse}/module/{idLesson}', name: 'app_lesson_detail')]
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
