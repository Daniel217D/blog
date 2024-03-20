<?php

namespace DDaniel\Blog\Articles;

use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class Article
{
    /**
     * @throws ArticleNotFoundException
     */
    public static function foundOrFail(string $slug): static
    {
        $article = new static($slug);

        if (! $article->exists()) {
            throw new ArticleNotFoundException("Статья $slug не найдена");
        }

        return $article;
    }

    public function __construct(
        protected string $slug
    ) {
    }

    public function getTitle(): string
    {
        return $this->slug;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getUrl(): string
    {
        return '/' . $this->getSlug();
    }

    public function getCreatedTimestamp(): string
    {
        return filemtime($this->getFilePath());
    }

    public function getCreatedTime(): string
    {
        return date('d/m/Y', $this->getCreatedTimestamp());
    }

    protected function getContent(): bool|string
    {
        return file_get_contents($this->getFilePath()) ?: '';
    }

    public function getContentHtml(): bool|string
    {
        return app()->templates->include(
            'article',
            array( 'content' => $this->getContent() ),
            false
        );
    }

    public function getFilePath(): string
    {
        return app()->path . "content/$this->slug.md";
    }

    public function exists(): bool
    {
        return file_exists($this->getFilePath());
    }
}
