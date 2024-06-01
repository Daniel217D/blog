<?php

declare( strict_types=1 );

namespace DDaniel\Blog\Entities;

use DateTimeImmutable;

abstract class BaseEntity {
	public function isNull(): bool {
		return $this->getId() === 0;
	}

	abstract public function getId(): int;

    abstract public function getTitle(): string;

    abstract public function getDescription(): string;

    abstract public function getSlug(): string;

    abstract public function getCreatedTime(): DateTimeImmutable;

    abstract public function getUpdatedTime(): DateTimeImmutable;
}