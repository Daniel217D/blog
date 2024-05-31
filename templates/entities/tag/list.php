<?php
/**
 * @var Tag[] $entities
 */

use DDaniel\Blog\Entities\Tag;

app()->templates->include('components/list-tag', ['entities' => $entities]);
