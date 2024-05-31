<?php

declare( strict_types=1 );

namespace DDaniel\Blog\Entities;

abstract class BaseEntity {
	public function isNull(): bool {
		return $this->getId() === 0;
	}

	abstract public function getId(): int;

    abstract public function getTitle(): string;

    abstract public function getDescription(): string;
}