<?php

namespace DDaniel\Blog\Enums;

use DDaniel\Blog\Entities\Author;
use DDaniel\Blog\Entities\Post;
use DDaniel\Blog\Entities\Tag;
use InvalidArgumentException;

enum SettingType: string
{
    case TEXT = 'text';
    case TEXTAREA = 'textarea';
}