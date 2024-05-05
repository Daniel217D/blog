<div class="sidebar">
    <a class="sidebar__logo" href="<?php echo app()->router->getRoutePath('admin') ?>">
        <img src="/images/logo_white.svg" alt="Логотип белый" class="logo logo-white">
    </a>
	<ul class="list-unstyled">
        <li style="margin-bottom: 5px">
            <a class="text-white" style="font-size: 1.2rem" href="<?php echo app()->site_url ?>">To site</a>
        </li>
	    <?php foreach ( \DDaniel\Blog\Enums\Entity::cases() as $entity ) : ?>
            <li style="margin-bottom: 5px;">
                <a class="text-white" style="font-size: 1.2rem" href="<?php echo app()->router->getRoutePath('adminEntitiesList', ['entity' => $entity->value])?>">
                    <?php echo $entity->name ?>
                </a>
            </li>
        <?php endforeach; ?>
	</ul>
</div>