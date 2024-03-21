<?php

declare(strict_types=1);

namespace DDaniel\Blog\Articles;

class Articles
{
    /**
     * @var Article[]
     */
    protected array $articles;

    public function __construct(string $name_filter = '')
    {
        $file_paths = glob(app()->path . 'content/*.md');

        if ($name_filter !== '') {
            $name_filter = strtolower($name_filter);
            $file_paths = array_filter($file_paths, static fn(string $path) => str_contains(strtolower(basename($path, '.md')), $name_filter));
        }

        $this->articles = array_map(
            static fn(string $path) => new Article(basename($path, '.md')),
            $file_paths
        );


        usort($this->articles, fn(Article $a, Article $b) => $b->getCreatedTimestamp() - $a->getCreatedTimestamp());
    }

    public function getContentHtml(): string
    {
        return app()->templates->include(
            'articles',
            array( 'articles' => $this->articles ),
            false
        );
    }
}
