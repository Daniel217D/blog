<?php

namespace DDaniel\Blog;

class App {
	public readonly string $path;

	public function __construct() {
		$this->path = dirname( __DIR__ ) . '/';
	}

	public function start() {
		( new Router() )->process_request();
	}
}