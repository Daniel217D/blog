<?php
/**
 * @var Post $entity
 */

use DDaniel\Blog\Entities\Post;
use DDaniel\Blog\Enums\PostStatus;

?>

<form action="<?php echo app()->router->getUrlForEntityEditor($entity)?>" method="post">
    <input type="hidden" name="method" value="patch">

    <button class="btn btn-primary mb-3" type="submit">Сохранить</button>

    <div class="form-floating mb-3">
        <input type="text"
               class="form-control"
               id="title"
               name="title"
               placeholder="Title"
               value="<?php echo $entity->getTitle() ?>">
        <label for="title">Title</label>
    </div>

    <div class="form-floating mb-3">
        <input type="text"
               class="form-control"
               id="slug"
               name="slug"
               placeholder="Slug"
               value="<?php echo $entity->getSlug() ?>">
        <label for="slug">Slug</label>
    </div>

    <select class="form-select mb-3">
        <?php foreach ( PostStatus::cases() as $post_status ) : ?>
            <option value="<?php echo $post_status->value ?>"
                <?php echo $entity->getStatus() === $post_status ? 'selected' : '' ?>
            >
                <?php echo $post_status->name ?>
            </option>
        <?php endforeach; ?>
    </select>

    <div class="form-floating mb-3" id="contentContainer">
    <textarea class="form-control"
              id="content"
              name="content"
              placeholder="Content"
              style="min-height: 500px"><?php echo $entity->getContent() ?></textarea>
        <label for="content">Content</label>
        <iframe id="contentRenderer" style="display: none"  ></iframe>
        <button class="btn btn-primary" id="contentToggleView"><></button>
    </div>

    <div class="form-floating mb-3">
    <textarea type="text"
              class="form-control"
              id="excerpt"
              name="excerpt"
              placeholder="Excerpt"
              style="min-height: 200px"><?php echo $entity->getExcerpt() ?></textarea>
        <label for="excerpt">Excerpt</label>
    </div>

    <button class="btn btn-primary mb-3" type="submit">Сохранить</button>
</form>



