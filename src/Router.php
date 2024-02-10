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
			$parameters = $matcher->match( $_SERVER['REQUEST_URI'] );

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

	private function init_routes() {
		require __DIR__ . '/routes.php';
	}
}