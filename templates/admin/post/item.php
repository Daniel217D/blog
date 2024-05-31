<?php
/**
 * @var Post $entity
 * @var ?string $error
 */

use DDaniel\Blog\Entities\Post;
use DDaniel\Blog\Entities\Tag;
use DDaniel\Blog\Enums\PostStatus;

$tagIds = $entity->getTagIds();

app()->templates->include( 'admin/components/editor-form-start', [ 'entity' => $entity ] ) ?>

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

    <select class="form-select mb-3" name="status">
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

    <div class="toggleBtns mb-3">
        <h6 class="mb-2">Tags</h6>

        <?php foreach (app()->em->getRepository(Tag::class)->findBy([], ['title' => 'ASC']) as $tag ): ?>
            <input
                    type="checkbox"
                    class="btn-check"
                    name="tagIds[]"
                    value="<?php echo $tag->getId() ?>"
                    id="tag-id-<?php echo $tag->getId() ?>"
                    <?php echo !$entity->isNull() && in_array($tag->getId(), $tagIds) ? 'checked' : '' ?>
                    autocomplete="off"
            >
            <label class="btn mb-2" for="tag-id-<?php echo $tag->getId() ?>">
                <?php echo $tag->getTitle() ?>
            </label>
        <?php endforeach; ?>
    </div>

    <?php if($entity->isNull()) : ?>
        <input type="hidden" name="author" value="<?php echo app()->author->getId() ?>">
    <?php endif; ?>

<?php app()->templates->include( 'admin/components/editor-form-end', [ 'entity' => $entity ] ) ?>



