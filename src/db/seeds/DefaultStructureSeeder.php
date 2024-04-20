<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class DefaultStructureSeeder extends AbstractSeed
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
        $tableUsers = $this->table('authors');
        $tableUsers->insert([
            'login'    => 'test',
            'password' => 'test',
            'name'     => 'test',
            'email'    => 'test',
            'role'     => 'test',
        ])->saveData();

        $tablePosts = $this->table('posts');

        for ($i = 1; $i <= 9; $i++) {
            $tablePosts->insert([
                'title'     => 'title' . $i . '_' . rand(100, 999),
                'slug'      => 'slug' . $i . '_' . rand(100, 999),
                'content'   => 'content' . $i . '_' . rand(100, 999),
                'excerpt'   => 'excerpt' . $i . '_' . rand(100, 999),
                'status'    => 'status' . $i . '_' . rand(100, 999),
                'author_id' => 1,
            ]);
        }

        $tablePosts->saveData();
    }
}