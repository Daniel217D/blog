<?php

namespace DDaniel\Blog;

class App {
	public readonly string $path;
	public readonly string $home_url;
	public readonly string $current_url;

	public readonly string $site_name;
	public readonly string $site_url;
	public readonly bool $debug_enabled;

	public readonly Assets $assets;
	public readonly Templates $templates;

	public function __construct() {
		$this->path = dirname( __DIR__ ) . '/';
		$this->home_url = '/';
		$this->current_url = ( empty( $_SERVER['HTTPS'] ) ? 'http' : 'https' ) . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

		$env = @parse_ini_file( "$this->path/.env" ) ?: array();
		$this->site_name = $env['APP_NAME'];
		$this->site_url = $env['APP_URL'];
		$this->debug_enabled = $env['APP_DEBUG'];

		ini_set( 'log_errors', 1 );
		ini_set( 'error_log', "{$this->path}error.log" );

		$this->assets = new Assets( "{$this->path}public/assets", '/assets' );
		$this->templates = new Templates( "{$this->path}templates/" );
	}

	public function start() {
		( new Router() )->process_request();
	}
}