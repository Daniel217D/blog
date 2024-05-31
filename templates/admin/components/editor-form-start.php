<?php
/**
 * @var BaseEntity $entity
 * @var ?string $error
 */

use DDaniel\Blog\Entities\BaseEntity;

?>

<?php if ( isset( $error ) && '' !== $error ) : ?>
	<div class="alert alert-danger" role="alert">
		<?php echo $error ?>
	</div>
<?php endif; ?>

<form action="<?php echo app()->router->getUrlForEntityAdmin($entity) ?>" method="post">
	<input type="hidden" name="method" value="<?php echo $entity->isNull() ? 'post' : 'patch' ?>">

	<?php app()->templates->include( 'admin/components/editor-buttons', [ 'entity' => $entity ] ) ?>



