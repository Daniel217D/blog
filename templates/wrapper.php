<?php

/**
 * @var PageController $pc
 */



use DDaniel\Blog\PageController;

?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <meta name="description" content="<?php echo $pc->description ?>">
    <meta name="robots" content="follow, index, max-snippet:-1, max-video-preview:-1, max-image-preview:large">
    <meta property="og:locale" content="ru_RU">
    <meta property="og:type" content="<?php echo $pc->type ?>">
    <meta property="og:title" content="<?php echo $pc->title ?>">
    <meta property="og:description" content="<?php echo $pc->description ?>">
    <meta property="og:url" content="<?php echo app()->current_url ?>">
    <meta property="og:site_name" content="<?php echo app()->site_name ?>">
    <meta property="og:image" content="<?php echo app()->site_url . '/images/logo_wide.png' ?>">
    <meta property="og:image:secure_url" content="<?php echo app()->site_url . '/images/logo_wide.png' ?>">
    <meta property="og:image:width" content="1024">
    <meta property="og:image:height" content="512">

    <?php app()->assets->add_css('index') ?>

    <link rel="apple-touch-icon" sizes="120x120" href="<?php echo app()->site_url . '/favicons/apple-touch-icon.png' ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo app()->site_url . '/favicons/favicon-32x32.png' ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo app()->site_url . '/favicons/favicon-16x16.png' ?>">
    <link rel="mask-icon" href="<?php echo app()->site_url . '/favicons/safari-pinned-tab.svg' ?>" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <title><?php echo $pc->title ?></title>
</head>
<body>
    <?php app()->templates->include('header'); ?>

    <div class="container content">
        <div class="row">
            <div class="col">
                <?php if (! $pc->is_home_page) :
                    ?>
                    <a href="<?php echo app()->home_url ?>" class="d-inline-block mb-2">← Назад</a>
                    <?php
                endif; ?>
                <h1><?php echo $pc->title ?></h1>
                <?php echo $pc->content ?>
            </div>
        </div>
    </div>

    <?php app()->templates->include('footer'); ?>

    <script>
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.body.setAttribute('data-bs-theme', 'dark');
        } else {
            document.body.setAttribute('data-bs-theme', 'light');
        }
    </script>

    <?php app()->assets->add_js('index') ?>
</body>
</html>
