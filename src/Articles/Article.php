<?php

declare(strict_types=1);

namespace DDaniel\Blog\Articles;

final class Article
{
    /**
     * @throws ArticleNotFoundException
     */
    public static function foundOrFail(string $slug): Article
    {
        $article = new Article($slug);

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

    public function getCreatedTimestamp(): int
    {
        return (int) filemtime($this->getFilePath());
    }

    public function getCreatedTime(): string
    {
        return date('d/m/Y', $this->getCreatedTimestamp());
    }

    protected function getContent(): string
    {
        return (string) file_get_contents($this->getFilePath());
    }

    public function getContentHtml(): string
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
