<?php

namespace DDaniel\Blog\Articles;

class Articles {
	/**
	 * @var Article[]
	 */
	protected array $articles;

	public function __construct() {
		$this->articles = array_map(
			fn( $path ) => new Article( basename( $path, '.md' ) ),
			glob( app()->path . 'content/*.md' )
		);
	}

	public function get_content_html(): string {
		$articles = $this->articles;

		ob_start();
		require app()->path . 'templates/articles.php';

		return ob_get_clean();
	}
}