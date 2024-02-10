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

    <title><?php echo $title ?? 'DDaniel blog' ?></title>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col">
                <h1><?php echo $title ?></h1>
	            <?php echo $content ?>
            </div>
        </div>
    </div>
    <script src="/assets/index.js"></script>
</body>
</html>