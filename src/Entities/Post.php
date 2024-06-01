<?php

namespace DDaniel\Blog\Entities;

use Ausi\SlugGenerator\SlugGenerator;
use DateTimeImmutable;
use DDaniel\Blog\Enums\Entity;
use DDaniel\Blog\Enums\PostStatus;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'posts')]
#[ORM\HasLifecycleCallbacks]
class Post extends BaseEntity
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private int $id;

    #[ORM\Column(type: 'string', length: 127)]
    private string $title;

    #[ORM\Column(type: 'string', length: 127)]
    private string $slug;

    #[ORM\Column(type: 'text')]
    private string $content;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $excerpt;

    #[ORM\Column(type: 'string', length: 15, enumType: PostStatus::class)]
    private PostStatus $status;

    #[ORM\Column(name: 'created_time', type: 'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTimeImmutable $createdTime;

    #[ORM\Column(name: 'updated_time', type: 'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTimeImmutable $updatedTime;

    #[ORM\ManyToOne(targetEntity: Author::class)]
    #[ORM\JoinColumn(name: 'author_id')]
    private ?Author $author = null;

    /**
     * @var Collection<int, Tag>
     */
    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'posts')]
    private Collection $tags;

    public function __construct()
    {
        $this->id          = 0;
        $this->title       = '';
        $this->slug        = '';
        $this->content     = '';
        $this->excerpt     = '';
        $this->status      = PostStatus::Draft;
        $this->tags        = new ArrayCollection();
        $this->createdTime = new DateTimeImmutable();
        $this->updatedTime = new DateTimeImmutable();
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

    public function getDescription(): string
    {
        return $this->getExcerpt();
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

    public function getStatus(): PostStatus
    {
        return $this->status;
    }

    public function setStatus(PostStatus|string $status): void
    {
        if(is_string($status)) {
            $status = PostStatus::from($status);
        }

        $this->status = $status;
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

    public function getAuthor(): Author
    {
        return $this->author;
    }

    public function setAuthor(Author $author): void
    {
        $this->author = $author;
    }

    /**
     * @return Collection<Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

	/**
	 * @param  Collection<Tag>  $tags
	 *
	 * @return void
	 */
	public function setTags(Collection $tags): void
	{
		$this->tags = $tags;
	}

    /**
     * @return array<int>
     */
    public function getTagIds(): array
    {
        return $this->tags->map(fn($tag) => $tag->getId())->toArray();
    }

	/**
	 * @param  array<int>  $tagIds
	 *
	 * @return void
	 */
    public function setTagIds(array $tagIds): void
    {
        $this->tags = new ArrayCollection(
            array_map(
                fn($tagId) => app()->em->getReference(Tag::class, $tagId),
                $tagIds
            )
        );
    }
}