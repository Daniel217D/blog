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
                <a href="<?php echo app()->router->getUrlForEntityEditor($entity) ?>">
                    #<?php echo $entity->getId() ?>
                </a>
            </th>
            <td>
                <span><?php echo $entity->getTitle() ?></span>
                <br>
                <a href="<?php echo app()->router->getUrlForEntityView($entity)?>" class="small"><?php echo $entity->getSlug() ?></a>
            </td>
            <td><?php echo $entity->getStatus()->name ?></td>
            <td><?php echo $entity->getAuthor()->getName() ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>