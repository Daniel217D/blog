<?php
/**
 * @var Post[] $entities
 */

use DDaniel\Blog\Entities\Post;

?>

<div class="list-group">
    <?php foreach ($entities as $entity) :
        ?>
        <a href="<?php echo app()->router->getUrlForEntityView($entity) ?>" class="list-group-item list-group-item-action">
            <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-0"><?php echo $entity->getTitle() ?></h5>
                <small><?php echo $entity->getCreatedTime()->format('j.m.Y') ?></small>
            </div>
            <p class="mb-1"><?php echo $entity->getExcerpt() ?></p>
        </a>
    <?php
    endforeach; ?>
</div>
