<?php
/**
 * @var Post[] $entities
 */

use DDaniel\Blog\Entities\Post;

?>

<a class="btn btn-primary mb-3" href="<?php echo app()->router->getRoutePath('adminEntityNew', ['entity' => 'post'])?>">Новый пост</a>

<table class="table">
    <thead>
    <tr>
        <th scope="col">ID</th>
        <th scope="col">Title</th>
        <th scope="col">Status</th>
        <th scope="col">Author</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($entities as $entity) : ?>
        <tr>
            <td>
                <a href="<?php echo app()->router->getUrlForEntityAdmin($entity) ?>">
                    #<?php echo $entity->getId() ?>
                </a>
                <br>
                <form action="<?php echo app()->router->getUrlForEntityAdmin($entity) ?>">
                    <a href="#" class="delete" onclick="event.preventDefault(); this.closest('form').submit()">delete</a>
                    <input type="hidden" name="method" value="delete">
                </form>
            </td>
            <td>
                <span><?php echo $entity->getTitle() ?></span>
                <br>
                <a href=" <?php echo app()->router->getUrlForEntityFrontend($entity)?>" class="small"><?php echo $entity->getSlug() ?></a>
            </td>
            <td><?php echo $entity->getStatus()->name ?></td>
            <td><?php echo $entity->getAuthor()->getName() ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>