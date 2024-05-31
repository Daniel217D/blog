<?php
/**
 * @var Tag $entity
 */

use DDaniel\Blog\Entities\Tag;
?>

<h1><?php echo $entity->getTitle() ?></h1>

<p><?php echo $entity->getDescription() ?></p>

<?php
app()->templates->include('components/list-post', ['entities' => $entity->getPosts()]);
