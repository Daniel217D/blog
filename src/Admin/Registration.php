<?php

namespace DDaniel\Blog\Admin;

use DDaniel\Blog\Models\Author;

class Registration {
	private Password $password;

	public function __construct() {
		$this->password = new Password();
	}

	/**
	 * @throws \Doctrine\ORM\OptimisticLockException
	 * @throws \Doctrine\ORM\Exception\ORMException
	 *
	 * ToDo check if author already exists
	 */
	public function register( Author $author, string $password ): Author {
		$author->setPassword( $this->password->hash( $password ) );

		app()->entity_manager->persist( $author );
		app()->entity_manager->flush();

		return $author;
	}
}