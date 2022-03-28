<?php

namespace App\Controller;

use App\Form\EditStudentFormType;
use App\Form\EditTeacherFormType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[IsGranted('ROLE_USER')]
class ProfileController extends AbstractController
{
    #[IsGranted('ROLE_TEACHER')]
    #[Route('/profil-formateur', name: 'app_profile_teacher')]
    public function profileTeacher(): Response
    {
        return $this->render('profile/teacher_profile.html.twig', [
        ]);
    }

    #[IsGranted('ROLE_TEACHER')]
    #[Route('/profil-formateur/modifier', name: 'app_edit_profile_teacher')]
    public function editProfileTeacher(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $teacher = $this->getUser();
        $form = $this->createForm(EditTeacherFormType::class, $teacher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $profilePictureFile */
            $profilePictureFile = $form->get('profilePicture')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($profilePictureFile) {
                $originalFilename = pathinfo($profilePictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$profilePictureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $profilePictureFile->move(
                        $this->getParameter('profile_pictures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $teacher->setProfilePicture($newFilename);
            }

            $entityManager->persist($teacher);
            $entityManager->flush();

            return $this->redirectToRoute('app_profile_teacher');
        }

        return $this->render('profile/edit_teacher_profile.html.twig', [
            'editTeacherProfileForm' => $form->createView(),
        ]);
    }

    #[IsGranted('ROLE_STUDENT')]
    #[Route('/profil-apprenant', name: 'app_profile_student')]
    public function profileStudent(): Response
    {
        return $this->render('profile/student_profile.html.twig', [
        ]);
    }

    #[IsGranted('ROLE_STUDENT')]
    #[Route('/profil-apprenant/modifier', name: 'app_edit_profile_student')]
    public function editProfileStudent(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $student = $this->getUser();
        $form = $this->createForm(EditStudentFormType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $profilePictureFile */
            $profilePictureFile = $form->get('profilePicture')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($profilePictureFile) {
                $originalFilename = pathinfo($profilePictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$profilePictureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $profilePictureFile->move(
                        $this->getParameter('profile_pictures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $student->setProfilePicture($newFilename);
            }

            $entityManager->persist($student);
            $entityManager->flush();

            return $this->redirectToRoute('app_profile_student');
        }

        return $this->render('profile/edit_student_profile.html.twig', [
            'editStudentProfileForm' => $form->createView(),
        ]);
    }
}
