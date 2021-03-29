<?php

namespace App\Entity;

use App\Repository\BookRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=BookRepository::class)
 */
class Book
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Author::class, mappedBy="book")
     */
    private $author_id;

    /**
     * @ORM\OneToMany(targetEntity=Category::class, mappedBy="book")
     */
    private $category_id;

    /**
     * @ORM\Column(type="date")
     */
    private $publication;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $cover;

    public function __construct()
    {
        $this->author_id = new ArrayCollection();
        $this->category_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Author[]
     */
    public function getAuthorId(): Collection
    {
        return $this->author_id;
    }

    public function addAuthorId(Author $authorId): self
    {
        if (!$this->author_id->contains($authorId)) {
            $this->author_id[] = $authorId;
            $authorId->setBook($this);
        }

        return $this;
    }

    public function removeAuthorId(Author $authorId): self
    {
        if ($this->author_id->removeElement($authorId)) {
            // set the owning side to null (unless already changed)
            if ($authorId->getBook() === $this) {
                $authorId->setBook(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategoryId(): Collection
    {
        return $this->category_id;
    }

    public function addCategoryId(Category $categoryId): self
    {
        if (!$this->category_id->contains($categoryId)) {
            $this->category_id[] = $categoryId;
            $categoryId->setBook($this);
        }

        return $this;
    }

    public function removeCategoryId(Category $categoryId): self
    {
        if ($this->category_id->removeElement($categoryId)) {
            // set the owning side to null (unless already changed)
            if ($categoryId->getBook() === $this) {
                $categoryId->setBook(null);
            }
        }

        return $this;
    }

    public function getPublication(): ?\DateTimeInterface
    {
        return $this->publication;
    }

    public function setPublication(\DateTimeInterface $publication): self
    {
        $this->publication = $publication;

        return $this;
    }

    public function getCover(): ?string
    {
        return $this->cover;
    }

    public function setCover(string $cover): self
    {
        $this->cover = $cover;

        return $this;
    }
}
