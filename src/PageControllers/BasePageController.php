<?php

declare(strict_types=1);

namespace DDaniel\Blog\PageControllers;

abstract class BasePageController
{
    public readonly bool $is_home_page;

    public function __construct(
        public string $title = '',
        public ?string $description = null,
        public string $content = '',
        public string $type = 'website',
    ) {}
}
