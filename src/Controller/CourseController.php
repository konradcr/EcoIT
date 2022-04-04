<?php

namespace App\Controller;

use App\Entity\CourseProgress;
use App\Entity\Student;
use App\Repository\CourseProgressRepository;
use App\Repository\CourseRepository;
use App\Repository\LessonRepository;
use App\Repository\QuizRepository;
use App\Repository\SectionRepository;
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
        // fetch courses ordered by creationDate
        $courses = $courseRepository->findBy([], ['creationDate' => 'DESC']);

        return $this->render('courses/index.html.twig', [
            'courses' => $courses,
        ]);
    }

    #[Route('/formation/{id}', name: 'app_course_detail')]
    public function courseDetail(int $id, CourseRepository $courseRepository, CourseProgressRepository $courseProgressRepository, StudentRepository $studentRepository): Response
    {
        // fetch a course by id
        $course = $courseRepository->findOneBy(['id' => $id]);
        $courseProgress = NULL;

        // if the user is a student, try to fetch its courseProgress related to the course
        if ($this->getUser() instanceof Student) {
            $student = $studentRepository->findOneBy(['id' => $this->getUser()->getId()]);
            $courseProgress = $courseProgressRepository->findOneBy(['course' => $course, 'student' => $student]);
        }

        return $this->render('courses/course/course_detail.html.twig', [
            'course' => $course,
            'courseProgress' => $courseProgress
        ]);
    }

    #[IsGranted('ROLE_STUDENT')]
    #[Route('/formation/{id}/rejoindre', name: 'app_register_course')]
    public function registerCourse(int $id, CourseRepository $courseRepository, StudentRepository $studentRepository, EntityManagerInterface $entityManager): Response
    {
        $student = $studentRepository->findOneBy(['id' => $this->getUser()->getId()]);
        $course = $courseRepository->findOneBy(['id' => $id]);

        // create a courseProgress for the student connected
        $courseProgress = new CourseProgress();
        $courseProgress->setStudent($student);
        $courseProgress->setCourse($course);
        $courseProgress->setProgress(0);

        $entityManager->persist($courseProgress);
        $entityManager->flush();

        return $this->redirectToRoute('app_course_detail', ['id' => $id]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/formation/{idCourse}/module/{idLesson}', name: 'app_lesson_detail')]
    public function lessonDetail(int $idCourse, int $idLesson, CourseRepository $courseRepository, SectionRepository $sectionRepository ,LessonRepository $lessonRepository, CourseProgressRepository $courseProgressRepository): Response
    {
        $course = $courseRepository->findOneBy(['id' => $idCourse]);
        $lesson = $lessonRepository->findOneBy(['id' => $idLesson]);
        $courseProgress = $courseProgressRepository->findOneBy(['course' => $course, 'student' => $this->getUser()]);

        return $this->render('courses/lesson/lesson.html.twig', [
            'course' => $course,
            'lesson' => $lesson,
            'courseProgress' => $courseProgress,
        ]);
    }

    #[IsGranted('ROLE_STUDENT')]
    #[Route('/formation/{idCourse}/module/{idLesson}/complete', name: 'app_complete_lesson')]
    public function completeLesson(int $idCourse, int $idLesson, LessonRepository $lessonRepository, CourseProgressRepository $courseProgressRepository, EntityManagerInterface $entityManager): Response
    {
        $lesson = $lessonRepository->findOneBy(['id' => $idLesson]);
        $course = $lesson->getSection()->getCourse();

        $courseProgress = $courseProgressRepository->findOneBy(['course' => $course, 'student' => $this->getUser()]);

        // add lesson to lessons completed in the courseProgress to track the progress
        $courseProgress->addLesson($lesson);

        $totalOfLessons = 0;
        // loop all sections of the course to count the total number of lessons
        foreach ($course->getSections() as $section) {
            $totalOfLessons += count($section->getLessons());
        }
        // count the number of lessons completed
        $totalLessonCompleted = count($courseProgress->getLessons());
        // calculate the progress of the student to the course
        $courseProgress->setProgress($totalLessonCompleted/$totalOfLessons);

        $entityManager->persist($courseProgress);
        $entityManager->persist($lesson);
        $entityManager->flush();

        return $this->redirectToRoute('app_lesson_detail', ['idCourse' => $idCourse, 'idLesson' => $idLesson]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/formation/{idCourse}/quiz/{idQuiz}', name: 'app_quiz')]
    public function quiz(int $idCourse, int $idQuiz, CourseRepository $courseRepository, CourseProgressRepository $courseProgressRepository, QuizRepository $quizRepository): Response
    {
        $course = $courseRepository->findOneBy(['id' => $idCourse]);
        $quiz = $quizRepository->findOneBy(['id' => $idQuiz]);
        $courseProgress = $courseProgressRepository->findOneBy(['course' => $course, 'student' => $this->getUser()]);

        return $this->render('courses/lesson/quiz.html.twig', [
            'course' => $course,
            'quiz' => $quiz,
            'courseProgress' => $courseProgress,
        ]);
    }
}
