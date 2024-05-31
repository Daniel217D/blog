<?php

namespace DDaniel\Blog\Entities;

use Ausi\SlugGenerator\SlugGenerator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'tags')]
#[ORM\HasLifecycleCallbacks]
class Tag extends BaseEntity
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private int $id;

    #[ORM\Column(type: 'string', length: 127)]
    private string $title;

    #[ORM\Column(type: 'string', length: 127)]
    private string $slug;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description;

    /**
     * @var Collection<int, Post>
     */
    #[ORM\ManyToMany(targetEntity: Post::class, mappedBy: 'tags')]
    private Collection $posts;

    public function __construct()
    {
        $this->id = 0;
        $this->title = '';
        $this->slug = '';
        $this->description = '';
        $this->posts = new ArrayCollection();
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function generateSlug()
    {
        if ($this->getSlug() === '') {
            $this->setSlug((new SlugGenerator())->generate($this->title));
        }

        $slugDuplicate = app()->em->getRepository(__CLASS__)->findOneBySlug($this->getSlug());

        if ($slugDuplicate !== null && $slugDuplicate->getId() !== $this->getId()) {
            throw new \Exception("Пост с таким slug'ом уже существует");
        }
    }

    public function isNull(): bool {
        return $this->getId() === 0;
    }

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

    public function getDescription(): string
    {
        return $this->description ?: '';
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getPosts(): ArrayCollection|Collection
    {
        return $this->posts;
    }

    public function setPosts(ArrayCollection|Collection $posts): void
    {
        $this->posts = $posts;
    }
}