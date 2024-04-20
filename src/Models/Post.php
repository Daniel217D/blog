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

    #[ORM\Column(name: 'author_id', type: 'integer')]
    private int $authorId;

    #[ORM\Column(name: 'created_time', type: 'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTimeImmutable $createdTime;

    #[ORM\Column(name: 'updated_time', type: 'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTimeImmutable $updatedTime;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param  int  $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param  string  $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param  string  $slug
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param  string  $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getExcerpt(): string
    {
        return $this->excerpt;
    }

    /**
     * @param  string  $excerpt
     */
    public function setExcerpt(string $excerpt): void
    {
        $this->excerpt = $excerpt;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param  string  $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getAuthorId(): int
    {
        return $this->authorId;
    }

    /**
     * @param  int  $authorId
     */
    public function setAuthorId(int $authorId): void
    {
        $this->authorId = $authorId;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreatedTime(): DateTimeImmutable
    {
        return $this->createdTime;
    }

    /**
     * @param  DateTimeImmutable  $createdTime
     */
    public function setCreatedTime(DateTimeImmutable $createdTime): void
    {
        $this->createdTime = $createdTime;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getUpdatedTime(): DateTimeImmutable
    {
        return $this->updatedTime;
    }

    /**
     * @param  DateTimeImmutable  $updatedTime
     */
    public function setUpdatedTime(DateTimeImmutable $updatedTime): void
    {
        $this->updatedTime = $updatedTime;
    }
}