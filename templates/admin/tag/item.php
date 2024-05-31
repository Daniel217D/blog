<?php
/**
 * @var Tag $entity
 * @var ?string $error
 */

use DDaniel\Blog\Entities\Tag;

app()->templates->include( 'admin/components/editor-form-start', [ 'entity' => $entity, 'error' => $error ?? '' ] ) ?>

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

<div class="form-floating mb-3">
    <textarea type="text"
              class="form-control"
              id="description"
              name="description"
              placeholder="Description"
              style="min-height: 200px"><?php echo $entity->getDescription() ?></textarea>
    <label for="description">Description</label>
</div>

<?php app()->templates->include( 'admin/components/editor-form-end', [ 'entity' => $entity ] ) ?>



