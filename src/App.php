<?php

namespace DDaniel\Blog;

class App {
	public readonly string $path;
	public readonly string $home_url;

	public function __construct() {
		$this->path = dirname( __DIR__ ) . '/';
		$this->home_url = '/';
	}

	public function start() {
		( new Router() )->process_request();
	}
}