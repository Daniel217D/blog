<?php

namespace DDaniel\Blog\Models;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'posts')]
class Post
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private int $id;

    #[ORM\Column(type: 'string', length: 127)]
    private string $title;

    #[ORM\Column(type: 'string', length: 127)]
    private string $slug;

    #[ORM\Column(type: 'string')]
    private string $content;

    #[ORM\Column(type: 'string')]
    private string $excerpt;

    #[ORM\Column(type: 'string', length: 15)]
    private string $status;

    #[ORM\ManyToOne(targetEntity: Author::class)]
    #[ORM\JoinColumn(name: 'author_id')]
    private Author $author;

    #[ORM\Column(name: 'created_time', type: 'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTimeImmutable $createdTime;

    #[ORM\Column(name: 'updated_time', type: 'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTimeImmutable $updatedTime;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getExcerpt(): string
    {
        return $this->excerpt;
    }

    public function setExcerpt(string $excerpt): void
    {
        $this->excerpt = $excerpt;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getAuthor(): Author
    {
        return $this->author;
    }

    public function setAuthor(Author $author): void
    {
        $this->author = $author;
    }

    public function getCreatedTime(): DateTimeImmutable
    {
        return $this->createdTime;
    }

    public function setCreatedTime(DateTimeImmutable $createdTime): void
    {
        $this->createdTime = $createdTime;
    }

    public function getUpdatedTime(): DateTimeImmutable
    {
        return $this->updatedTime;
    }

    public function setUpdatedTime(DateTimeImmutable $updatedTime): void
    {
        $this->updatedTime = $updatedTime;
    }
}