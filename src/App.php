<?php

namespace DDaniel\Blog;

class App {
	public readonly string $path;
	public readonly string $home_url;
	public readonly bool $is_home_page;

	public function __construct() {
		$this->path = dirname( __DIR__ ) . '/';
		$this->home_url = '/';
		$this->is_home_page = $this->home_url === parse_url( $_SERVER["REQUEST_URI"], PHP_URL_PATH );


		ini_set( 'log_errors', 1 );
		ini_set( 'error_log', "{$this->path}error.log" );
	}

	public function start() {
		( new Router() )->process_request();
	}
}