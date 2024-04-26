<?php

namespace DDaniel\Blog\Enums;

use DDaniel\Blog\Entities\Author;
use DDaniel\Blog\Entities\Category;
use DDaniel\Blog\Entities\Post;
use DDaniel\Blog\Entities\Tag;

enum Entity: string
{
    case Author = 'author';
    case Category = 'category';
    case Post = 'post';
    case Tag = 'tag';

    public function getEntityClass(): string
    {
        return match ($this) {
            self::Author => Author::class,
            self::Category => Category::class,
            self::Post => Post::class,
            self::Tag => Tag::class,
        };
    }
}