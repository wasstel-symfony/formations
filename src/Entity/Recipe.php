<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\RecipeRepository;
use App\Validator\BanWorld;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
#[Broadcast]
#[UniqueEntity('title')]
#[UniqueEntity('slug')]
#[Vich\Uploadable()]
#[ApiResource]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['api_recipe',])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank, ]
    #[Assert\Length(min: 5, max: 255)]
    #[BanWorld]
    #[Groups(['api_recipe', 'api_recipe_create'])]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 10, max: 255)]
    #[Assert\Regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', message: "Slug not valid")]
    #[Groups(['api_recipe', 'api_recipe_create'])]
    private ?string $slug = null;
    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 65000)]
    #[Groups(['api_recipe_show', 'api_recipe_create'])]
    private ?string $content = null;

    #[ORM\Column]
    #[Groups(['api_recipe',])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Groups(['api_recipe_show'])]
    private ?\DateTimeImmutable $updatedAt = null;
    #[Vich\UploadableField(mapping: 'recipes', fileNameProperty: 'thumbnail',)]
    #[Assert\Image()]
    private ?File $thumbnailFile = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Positive]
    #[Assert\LessThan(value: 1440)]
    #[Groups(['api_recipe', 'api_recipe_create'])]
    private ?int $duration = null;

    #[ORM\ManyToOne(inversedBy: 'recipes', cascade: ['persist'])]
    #[Groups(['api_recipe_show'])]
    private ?Category $category = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('api_recipe')]
    private ?string $thumbnail = null;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?string $thumbnail): static
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    public function getThumbnailFile(): ?File
    {
        return $this->thumbnailFile;
    }

    public function setThumbnailFile(?File $thumbnailFile): static
    {
        $this->thumbnailFile = $thumbnailFile;
        return $this;
    }
}
