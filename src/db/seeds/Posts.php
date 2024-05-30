<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class Posts extends AbstractSeed
{

	public function getDependencies(): array {
		return [
			'User'
		];
	}

	/**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run(): void
    {
        $table = $this->table('posts');

	    for ($i = 1; $i <= 9; $i++) {
		    $table->insert([
			    'title'     => 'title' . $i . '_' . rand(100, 999),
			    'slug'      => 'slug' . $i . '_' . rand(100, 999),
			    'content'   => 'content' . $i . '_' . rand(100, 999),
			    'excerpt'   => 'excerpt' . $i . '_' . rand(100, 999),
			    'status'    => 'published',
			    'author_id' => 1,
		    ]);
	    }

        $table->saveData();
    }
}
