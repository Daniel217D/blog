<?php
/**
 * @var Post[] $entities
 */

use DDaniel\Blog\Entities\Post;

app()->templates->include('components/list-post', ['entities' => $entities]);
