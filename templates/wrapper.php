<?php
/**
 * @var string $title
 * @var string $content
 */
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="/assets/index.css">

    <link rel="apple-touch-icon" sizes="120x120" href="favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicons/favicon-16x16.png">
    <link rel="mask-icon" href="favicons/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <title><?php echo $title ?? 'DDaniel blog' ?></title>
</head>
<body>
    <?php include app()->path . 'templates/header.php' ?>

    <div class="container content">
        <div class="row">
            <div class="col">
	            <?php if( app()->home_url !== $_SERVER['REQUEST_URI'] ) : ?>
                    <a href="<?php echo app()->home_url ?>" class="d-inline-block mb-2">← Назад</a>
	            <?php endif; ?>
                <h1><?php echo $title ?></h1>
	            <?php echo $content ?>
            </div>
        </div>
    </div>

    <?php include app()->path . 'templates/footer.php' ?>

    <script src="/assets/index.js"></script>
</body>
</html>