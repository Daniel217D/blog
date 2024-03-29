<?php

declare(strict_types=1);

use DDaniel\Blog\App;

function app(): App
{
    static $app = null;

    if (is_null($app)) {
        $app = new App();
    }

    return $app;
}
