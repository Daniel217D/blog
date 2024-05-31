<?php
/**
 * @var Post[] $entities
 */

use DDaniel\Blog\Entities\Post;
use DDaniel\Blog\Enums\PostStatus;

?>

<div class="list-group">
    <?php foreach ($entities as $entity) : ?>
        <div class="list-group-item">
            <a class="d-flex w-100 justify-content-between flex-wrap text-decoration-none" href="<?php echo app()->router->getUrlForEntityFrontend($entity) ?>">
                <h5 class="mb-0"><?php echo $entity->getTitle() ?></h5>
                <small>
                    <?php if(app()->isAuthorized && in_array($entity->getStatus(), [PostStatus::Hidden, PostStatus::Draft])) : ?>
                        <span style="color: #8d8d12">(<?php echo $entity->getStatus()->value ?>)</span>
                    <?php endif; ?>
                    <span><?php echo $entity->getCreatedTime()->format('d.m.Y') ?></span>
                </small>
                <p class="mb-1 d-inline-block w-100"><?php echo $entity->getExcerpt() ?></p>
            </a>
            <?php app()->templates->include('components/badges', ['tags' => $entity->getTags()]) ?>
        </div>
    <?php endforeach; ?>
</div>
