<?php
/**
 * @var Router $this
 */

use DDaniel\Blog\Articles\Article;
use DDaniel\Blog\Articles\Articles;
use DDaniel\Blog\Router;

$this->add_route( 'home', 'GET', '/', function () {
	$this->render_page( 'Заметки', ( new Articles() )->get_content_html() );
} );

$this->add_route( 'articles', 'GET', '/{slug}', function ( array $params ) {
	$this->render_page( $params['slug'], Article::foundOrFail( $params['slug'] )->get_content_html() );
} );
