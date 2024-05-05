<?php

namespace DDaniel\Blog\Enums;

enum PostStatus: string {
    case Draft = 'draft';
    case Published = 'published';
    case Hidden = 'hidden';
    case Trashed = 'trashed';
}
