<?php
/**
 * @var Tag[] $entities
 */

use DDaniel\Blog\Entities\Tag;

?>

<div class="list-group">
    <?php foreach ($entities as $entity) : ?>
        <a class="list-group-item" href="<?php echo app()->router->getUrlForEntityFrontend($entity) ?>">
            <h5 class="mb-0"><?php echo $entity->getTitle() ?></h5>
            <p class="mb-1"><?php echo $entity->getDescription() ?></p>
        </a>
    <?php endforeach; ?>
</div>
