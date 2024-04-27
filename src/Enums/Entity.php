<?php

namespace DDaniel\Blog\Enums;

use DDaniel\Blog\Entities\Author;
use DDaniel\Blog\Entities\Category;
use DDaniel\Blog\Entities\Post;
use DDaniel\Blog\Entities\Tag;
use InvalidArgumentException;

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

    public static function fromEntityClass(string $class): self {
        return match ($class) {
            Author::class => self::Author,
            Category::class => self::Category,
            Post::class => self::Post,
            Tag::class => self::Tag,
            default => throw new InvalidArgumentException( "Entity for $class not found" )
        };
    }
}