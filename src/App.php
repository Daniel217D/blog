<?php

namespace DDaniel\Blog;

class App {
	public function __construct() {
		( new Router() )->process_request();
	}
}