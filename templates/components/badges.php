<?php

declare(strict_types=1);

/**
 * @var Collection<Tag> $tags
 */

use DDaniel\Blog\Entities\Tag;
use Doctrine\Common\Collections\Collection;

?>

<div class="badges mb-1">
    <?php foreach ( $tags as $tag ): ?>
        <a class="badge" href="<?php echo app()->router->getUrlForEntityFrontend($tag) ?>"><?php echo $tag->getTitle() ?></a>
    <?php endforeach; ?>
</div>
