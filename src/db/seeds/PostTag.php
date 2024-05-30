<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class PostTag extends AbstractSeed
{
	public function getDependencies(): array
	{
		return [
			'Posts',
			'Tags'
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
		$table = $this->table('post_tag');

	    for ($i = 1; $i <= 9; $i++) {
		    $table->insert([
				'post_id' => rand(1,9),
				'tag_id' => rand(1,9)
		    ]);
	    }

		$table->save();
    }
}
