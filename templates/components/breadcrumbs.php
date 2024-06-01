<?php
/**
 * @var ?Breadcrumb $breadcrumb
 */

use DDaniel\Blog\Breadcrumb;

?>

<nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <?php while( $breadcrumb !== null ): ?>
            <?php if($breadcrumb->isLast()): ?>
                <li class="breadcrumb-item active" aria-current="page"><?php echo ucfirst($breadcrumb->getTitle()) ?></li>
            <?php else: ?>
                <li class="breadcrumb-item"><a href="<?php echo $breadcrumb->getLink() ?>"><?php echo ucfirst($breadcrumb->getTitle()) ?></a></li>
            <?php endif; ?>
        <?php
        $breadcrumb = $breadcrumb->getNextBreadcrumb();
        endwhile; ?>
    </ol>
</nav>
