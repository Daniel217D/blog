<?php

namespace DDaniel\Blog\Articles;

class Articles {
	/**
	 * @var Article[]
	 */
	protected array $articles;

	public function __construct( string $name_filter = '' ) {
		$file_paths = glob( app()->path . 'content/*.md' );

		if ( ! empty( $name_filter ) ) {
			$name_filter = strtolower( $name_filter );
			$file_paths = array_filter( $file_paths, fn( $path ) => str_contains( strtolower( basename( $path, '.md' ) ), $name_filter ) );
		}

		$this->articles = array_map(
			fn( $path ) => new Article( basename( $path, '.md' ) ),
			$file_paths
		);


		usort( $this->articles, fn( Article $a, Article $b ) => $b->get_created_timestamp() - $a->get_created_timestamp() );
	}

	public function get_content_html(): string {
		$articles = $this->articles;

		ob_start();
		require app()->path . 'templates/articles.php';

		return ob_get_clean();
	}
}