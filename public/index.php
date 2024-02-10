<?php

use DDaniel\Blog\App;

require __DIR__ . '/../vendor/autoload.php';

function app(): App {
	static $app = null;

	if ( is_null( $app ) ) {
		$app = new App();
	}

	return $app;
}

app()->start();