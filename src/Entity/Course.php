<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CourseRepository::class)]
class Course
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\Column(type: 'text')]
    private $description;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $coursePicture;

    #[ORM\Column(type: 'date')]
    private $creationDate;

    #[ORM\ManyToOne(targetEntity: Teacher::class, inversedBy: 'courses')]
    #[ORM\JoinColumn(nullable: false)]
    private $teacher;

    #[ORM\OneToMany(mappedBy: 'course', targetEntity: CourseProgress::class, orphanRemoval: true)]
    private $studentsCourseProgress;

    #[ORM\OneToMany(mappedBy: 'course', targetEntity: Section::class, orphanRemoval: true)]
    #[ORM\OrderBy(['orderInCourse' => 'ASC'])]
    private $sections;

    public function __construct()
    {
        $this->studentsCourseProgress = new ArrayCollection();
        $this->sections = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCoursePicture(): ?string
    {
        return $this->coursePicture;
    }

    public function setCoursePicture(?string $coursePicture): self
    {
        $this->coursePicture = $coursePicture;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getTeacher(): ?Teacher
    {
        return $this->teacher;
    }

    public function setTeacher(?Teacher $teacher): self
    {
        $this->teacher = $teacher;

        return $this;
    }

    /**
     * @return Collection<int, CourseProgress>
     */
    public function getStudentsCourseProgress(): Collection
    {
        return $this->studentsCourseProgress;
    }

    public function addStudentsCourseProgress(CourseProgress $studentsCourseProgress): self
    {
        if (!$this->studentsCourseProgress->contains($studentsCourseProgress)) {
            $this->studentsCourseProgress[] = $studentsCourseProgress;
            $studentsCourseProgress->setCourse($this);
        }

        return $this;
    }

    public function removeStudentsCourseProgress(CourseProgress $studentsCourseProgress): self
    {
        if ($this->studentsCourseProgress->removeElement($studentsCourseProgress)) {
            // set the owning side to null (unless already changed)
            if ($studentsCourseProgress->getCourse() === $this) {
                $studentsCourseProgress->setCourse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Section>
     */
    public function getSections(): Collection
    {
        return $this->sections;
    }

    public function addSection(Section $section): self
    {
        if (!$this->sections->contains($section)) {
            $this->sections[] = $section;
            $section->setCourse($this);
        }

        return $this;
    }

    public function removeSection(Section $section): self
    {
        if ($this->sections->removeElement($section)) {
            // set the owning side to null (unless already changed)
            if ($section->getCourse() === $this) {
                $section->setCourse(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return (string) $this->getTitle();
    }

    // Determine the course content : lessons and quiz
    public function getContents(): Collection
    {
        $contents = new ArrayCollection();
        $sections = $this->getSections();
        foreach ($sections as $section) {
            $lessons = $section->getLessons();
            foreach ($lessons as $lesson) {
                $lesson->type = 'lesson';
                $contents[] = $lesson;
            }
            $quizzes = $section->getQuizzes();
            foreach ($quizzes as $quiz) {
                $quiz->type = 'quiz';
                $contents[] = $quiz;
            }
        }
        return $contents;
    }
}
