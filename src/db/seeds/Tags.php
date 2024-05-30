<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class Tags extends AbstractSeed
{
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
	    $table = $this->table('tags');

	    for ($i = 1; $i <= 9; $i++) {
		    $table->insert([
			    'title'     => 'title' . $i . '_tag_' . rand(100, 999),
			    'slug'      => 'slug' . $i . '_tag_' . rand(100, 999),
			    'description'   => 'excerpt' . $i . '_' . rand(100, 999),
		    ]);
	    }

        $table->saveData();
    }
}
