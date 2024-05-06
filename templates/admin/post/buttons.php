<?php
/**
 * @var Post $entity
 */

use DDaniel\Blog\Entities\Post;
?>

<div class="mb-3">
	<button class="btn btn-primary" type="submit">
		<?php echo $entity->isNull() ? 'Создать' : 'Сохранить' ?>
	</button>

	<?php if(!$entity->isNull()) : ?>
		<a class="btn btn-secondary-c" target="_blank" href="<?php echo app()->router->getUrlForEntityFrontend($entity) ?>">Открыть</a>
	<?php endif; ?>
</div>
