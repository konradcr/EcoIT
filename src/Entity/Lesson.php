<?php

namespace App\Entity;

use App\Repository\LessonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LessonRepository::class)]
class Lesson
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\Column(type: 'integer')]
    private $orderInSection;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $video;

    #[ORM\Column(type: 'text')]
    private $lessonContent;

    #[ORM\ManyToOne(targetEntity: Section::class, inversedBy: 'lessons')]
    #[ORM\JoinColumn(nullable: false)]
    private $section;

    #[ORM\OneToMany(mappedBy: 'lesson', targetEntity: StudyMaterial::class, orphanRemoval: true)]
    private $studyMaterials;

    #[ORM\ManyToMany(targetEntity: CourseProgress::class, mappedBy: 'lessons')]
    private $courseProgress;

    public function __construct()
    {
        $this->studyMaterials = new ArrayCollection();
        $this->courseProgress = new ArrayCollection();
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

    public function getOrderInSection(): ?int
    {
        return $this->orderInSection;
    }

    public function setOrderInSection(int $orderInSection): self
    {
        $this->orderInSection = $orderInSection;

        return $this;
    }

    public function getVideo(): ?string
    {
        return $this->video;
    }

    public function setVideo(?string $video): self
    {
        $this->video = $video;

        return $this;
    }

    public function getLessonContent(): ?string
    {
        return $this->lessonContent;
    }

    public function setLessonContent(string $lessonContent): self
    {
        $this->lessonContent = $lessonContent;

        return $this;
    }

    public function getSection(): ?Section
    {
        return $this->section;
    }

    public function setSection(?Section $section): self
    {
        $this->section = $section;

        return $this;
    }

    /**
     * @return Collection<int, StudyMaterial>
     */
    public function getStudyMaterials(): Collection
    {
        return $this->studyMaterials;
    }

    public function addStudyMaterial(StudyMaterial $studyMaterial): self
    {
        if (!$this->studyMaterials->contains($studyMaterial)) {
            $this->studyMaterials[] = $studyMaterial;
            $studyMaterial->setLesson($this);
        }

        return $this;
    }

    public function removeStudyMaterial(StudyMaterial $studyMaterial): self
    {
        if ($this->studyMaterials->removeElement($studyMaterial)) {
            // set the owning side to null (unless already changed)
            if ($studyMaterial->getLesson() === $this) {
                $studyMaterial->setLesson(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return (string) $this->getTitle();
    }

    /**
     * @return Collection<int, CourseProgress>
     */
    public function getCourseProgress(): Collection
    {
        return $this->courseProgress;
    }

    public function addCourseProgress(CourseProgress $courseProgress): self
    {
        if (!$this->courseProgress->contains($courseProgress)) {
            $this->courseProgress[] = $courseProgress;
            $courseProgress->addLesson($this);
        }

        return $this;
    }

    public function removeCourseProgress(CourseProgress $courseProgress): self
    {
        if ($this->courseProgress->removeElement($courseProgress)) {
            $courseProgress->removeLesson($this);
        }

        return $this;
    }
}
