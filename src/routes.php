<?php
/**
 * @var Router $this
 */

use DDaniel\Blog\Articles\Article;
use DDaniel\Blog\Articles\Articles;
use DDaniel\Blog\Router;

$this->add_route( 'home', 'GET', '/', function () {
	$this->render_page( 'Список страниц', print_r( ( new Articles() )->names, true ) );
} );

$this->add_route( 'articles', 'GET', '/{slug}', function ( array $params ) {
	$this->render_page( $params['slug'], Article::foundOrFail( $params['slug'] )->get_content_html() );
} );
