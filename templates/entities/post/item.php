<?php
/**
 * @var Post $entity
 */

use DDaniel\Blog\Entities\Post;

?>

<h1><?php echo $entity->getTitle() ?></h1>

<?php if( app()->isAuthorized ) : ?>
    <a href="<?php echo app()->router->getRoutePath('adminEntityEdit', [
            'entity' => 'post',
            'id' => $entity->getId()
    ]) ?>" target="_blank">(edit)</a>
<?php endif; ?>

<div class="markdown" style="display: none"><?php echo $entity->getContent() ?></div>


