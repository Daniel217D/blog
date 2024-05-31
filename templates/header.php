<?php

declare(strict_types=1);

?>
<header class="p-3 pt-3 mb-2 border-bottom">
    <div class="container">
        <div class="row">
            <a href="/" class="col-auto d-flex">
                <img src="/images/logo_white.svg" alt="Логотип белый" class="logo logo-white">
                <img src="/images/logo_black.svg" alt="Логотип черный" class="logo logo-black">
            </a>
            <div class="col-auto d-flex align-items-center ms-auto">
                <ul class="nav nav-pills">
                    <li class="nav-item text-white"><a href="<?php echo app()->router->getRoutePath('home') ?>" class="nav-link">Посты</a></li>
                    <li class="nav-item text-white"><a href="<?php echo app()->router->getRoutePath('entitiesList', ['entity' => 'tag']) ?>" class="nav-link">Теги</a></li>
                </ul>
            </div>
        </div>
    </div>
</header>
