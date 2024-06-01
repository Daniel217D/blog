<?php

namespace DDaniel\Blog;

final class Breadcrumb
{
    /**
     * @param  non-empty-array<array{title: non-empty-string, link: ?string}>  $structure
     *
     * @return Breadcrumb
     */
    public static function generate(array $structure): Breadcrumb
    {
        $breadcrumbs = array_reduce(
            array_reverse($structure),
            function (?Breadcrumb $nextBreadcrumb, array $step): Breadcrumb {
                return new Breadcrumb($step['title'], $step['link'] ?? '', $nextBreadcrumb);
            },
            null
        );

        return new Breadcrumb(
            'Главная',
            app()->home_url,
            $breadcrumbs
        );
    }

    /**
     * @param  non-empty-string  $title
     * @param  string  $link
     * @param  Breadcrumb|null  $nextBreadcrumb
     */
    public function __construct(
        private string $title,
        private string $link = '',
        private ?Breadcrumb $nextBreadcrumb = null
    )
    {
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getLink(): string
    {
        return $this->link;
    }

    public function getNextBreadcrumb(): ?Breadcrumb
    {
        return $this->nextBreadcrumb;
    }

    public function isLast(): bool
    {
        return $this->getNextBreadcrumb() === null;
    }
}