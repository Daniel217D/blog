<?php
/**
 * @var Post[] $entities
 */

use DDaniel\Blog\Entities\Post;
use DDaniel\Blog\Enums\PostStatus;

?>

<h1>Последние посты: </h1>
<div class="list-group">
    <?php foreach ($entities as $entity) :
        ?>
        <a href="<?php echo app()->router->getUrlForEntityFrontend($entity) ?>" class="list-group-item list-group-item-action">
            <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-0"><?php echo $entity->getTitle() ?></h5>
                <small>
                    <?php if(app()->isAuthorized && in_array($entity->getStatus(), [PostStatus::Hidden, PostStatus::Draft])) : ?>
                        <span style="color: #8d8d12">(<?php echo $entity->getStatus()->value ?>)</span>
                    <?php endif; ?>
                    <span><?php echo $entity->getCreatedTime()->format('d.m.Y') ?></span>
                </small>
            </div>
            <p class="mb-1"><?php echo $entity->getExcerpt() ?></p>
        </a>
    <?php
    endforeach; ?>
</div>
