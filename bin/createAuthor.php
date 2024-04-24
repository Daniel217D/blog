<?php
declare(strict_types=1);

use DDaniel\Blog\Admin\Registration;
use DDaniel\Blog\Models\Author;
use Doctrine\Laminas\Hydrator\DoctrineObject as DoctrineHydrator;

require __DIR__ . '/../vendor/autoload.php';

app()->init();

( new Registration() )->register( ( new DoctrineHydrator( app()->entity_manager ) )->hydrate( [
	'login' => $argv[1],
	'name' => $argv[3],
	'email' => $argv[4],
	'role' => $argv[5] ?? 'admin',
], new Author() ), $argv[2] );