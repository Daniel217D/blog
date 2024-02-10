<?php

namespace DDaniel\Blog\Articles;

use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class Article {
	public static function foundOrFail( string $slug ): static {
		$article = new static( $slug );

		if ( ! $article->exists() ) {
			throw new ResourceNotFoundException( "Статья $slug не найдена" );
		}

		return $article;
	}

	public function __construct(
		protected string $slug
	) {
	}

	protected function get_content(): bool|string {
		return file_get_contents( $this->get_file_path() ) ?: '';
	}

	public function get_content_html(): bool|string {
		$content =  file_get_contents( $this->get_file_path() ) ?: '';

		ob_start();
		require app()->path . 'templates/article.php';

		return ob_get_clean();
	}

	public function get_file_path(): string {
		return app()->path . "content/$this->slug.md";
	}

	public function exists(): bool {
		return file_exists( $this->get_file_path() );
	}
}