<?php
/**
 * @var Post $entity
 */

use DDaniel\Blog\Entities\Post;

?>

<h1><?php echo $entity->getTitle() ?></h1>

<div class="markdown" style="display: none"><?php echo $entity->getContent() ?></div>


