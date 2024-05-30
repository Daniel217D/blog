<?php
/**
 * @var Tag[] $entities
 */

use DDaniel\Blog\Entities\Tag;

?>

<a class="btn btn-primary mb-3" href="<?php echo app()->router->getRoutePath('adminEntityNew', ['entity' => 'list'])?>">Новый Тег</a>

<table class="table">
    <thead>
    <tr>
        <th scope="col">ID</th>
        <th scope="col">Title</th>
        <th scope="col">Slug</th>
        <th scope="col">Description</th>
        <th scope="col">Posts counter</th>
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
                <a href="<?php echo app()->router->getUrlForEntityFrontend($entity)?>" class="small" target="_blank"><?php echo $entity->getSlug() ?></a>
            </td>
            <td><?php echo $entity->getSlug() ?></td>
            <td><?php echo $entity->getDescription() ?></td>
            <td><?php echo $entity->getPosts()->count() ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>