<?php
/**
 * @var Router $this
 */

use DDaniel\Blog\Router;

$this->add_route( 'home', 'GET', '/', function () {
	echo 'Hello World!';
} );
