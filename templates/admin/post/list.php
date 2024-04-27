<?php
/**
 * @var Post[] $entities
 */

use DDaniel\Blog\Entities\Post;

?>

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
            <th>
                <a href="<?php echo app()->router->getRoutePathForEntity($entity) ?>">
                    #<?php echo $entity->getId() ?>
                </a>
            </th>
            <td>
                <span><?php echo $entity->getTitle() ?></span>
                <br>
                <span class="small"><?php echo $entity->getSlug() ?></span>
            </td>
            <td><?php echo $entity->getStatus() ?></td>
            <td><?php echo $entity->getAuthor()->getName() ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>