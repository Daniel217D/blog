<?php

namespace DDaniel\Blog;

use Exception;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;

final class Router {
	private RouteCollection $routes;
	private RequestContext $context;

	public function __construct() {
		$this->routes  = new RouteCollection();
		$this->context = new RequestContext( $_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD'] );
		$this->init_routes();
	}

	public function process_request() {
		$matcher = new UrlMatcher( $this->routes, $this->context );

		try {
			$parameters = $matcher->match( parse_url( $_SERVER["REQUEST_URI"], PHP_URL_PATH ) );

			$parameters['function']( $parameters );
		} catch ( Exception $e ) {
			echo $e->getMessage();
			exit;
		}
	}

	public function add_route( string $name, string|array $methods, string $path, callable $function ) {
		$this->routes->add( $name, new Route(
			$path,
			[ 'function' => $function ],
			methods: is_array( $methods ) ? $methods : [ $methods ]
		) );
	}

	public function render_page( string $title, string $content, ...$args ): void {
		extract( $args );
		require app()->path . 'templates/wrapper.php';
	}

	public function send_json( mixed $data ):void {
		header( "Content-type: application/json; charset=utf-8" );

		echo json_encode( $data );

		die();
	}

	public function send_404( string $message = '' ) {
		http_response_code(404);

		if( str_contains( strtolower( getallheaders()['Accept'] ), 'json' ) ) {
			$this->send_json( array(
				'success' => false,
				'message' => $message
			) );
		}

		$this->render_page( '404', $message ?: 'Страница не найдена' );
	}

	private function init_routes() {
		require app()->path . 'src/routes.php';
	}
}