<?php

namespace DDaniel\Blog\Articles;

class Articles {
	public readonly array $names;

	public function __construct() {
		$this->names = array_map(
			fn( $path ) => basename( $path, '.md' ),
			glob( app()->path . 'content/*.md' )
		);
	}
}