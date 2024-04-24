<?php

declare(strict_types=1);

namespace DDaniel\Blog\PageControllers;

class AdminPageController extends BasePageController {
	public function __construct(
		public string $title = '',
		public ?string $description = null,
		public string $content = '',
		public string $type = '',
	) {
		parent::__construct($this->title, $this->description, $this->content, $this->type);
	}
}