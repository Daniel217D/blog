<?php

declare(strict_types=1);

/**
 * @var AdminPageController $pc
 */

use DDaniel\Blog\PageControllers\AdminPageController;

?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <?php app()->assets->addCss('admin') ?>

    <link rel="apple-touch-icon" sizes="120x120" href="<?php echo app()->site_url . '/favicons/apple-touch-icon.png' ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo app()->site_url . '/favicons/favicon-32x32.png' ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo app()->site_url . '/favicons/favicon-16x16.png' ?>">
    <link rel="mask-icon" href="<?php echo app()->site_url . '/favicons/safari-pinned-tab.svg' ?>" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <title><?php echo $pc->title ?></title>
</head>
<body data-bs-theme="dark">
<?php app()->isAuthorized ? app()->templates->include('admin/header') : null; ?>

<div class="container-fluid content">
	<?php echo $pc->content ?>
</div>


<?php app()->assets->addJs('admin') ?>
</body>
</html>
