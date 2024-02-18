<?php

namespace DDaniel\Blog;

class PageController {
	public string $is_home_page;

	public function __construct(
		public string $title = '',
		public ?string $description = null,
		public string $content = '',
		public string $type = 'website',
	) {
		$this->is_home_page = app()->home_url === parse_url( $_SERVER["REQUEST_URI"], PHP_URL_PATH );

		if ( empty( $this->description ) && ! empty( $this->content ) ) {
			$this->description = mb_substr( strip_tags( $this->content ), 0, 100 ) . '...';
			$this->description = trim( str_replace( array( "\n", "\r" ), ' ', $this->description ) );
		}
	}
}