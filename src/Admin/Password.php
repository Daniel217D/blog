<?php

namespace DDaniel\Blog\Admin;

class Password {
	private string $salt;

	public function __construct() {
		$this->salt = parse_ini_file( app()->path . '.env' )['PASSWORD_SALT'];
	}

	public function hash( string $password ): string {
		return password_hash( $this->salt . $password, PASSWORD_DEFAULT );
	}

	public function verify( string $password, string $hash ): bool {
		return password_verify( $this->salt . $password, $hash );
	}
}