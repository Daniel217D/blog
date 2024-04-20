<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateDefaultStructure extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $tableUsers = $this->table('authors');
        $tableUsers->addColumn('login', 'string', ['limit' => 15])
                   ->addColumn('password', 'string', ['limit' => 255])
                   ->addColumn('name', 'string', ['limit' => 31])
                   ->addColumn('email', 'string', ['limit' => 127])
                   ->addColumn('role', 'string', ['limit' => 15])
                   ->create();

        $tablePosts = $this->table('posts');
        $tablePosts->addColumn('title', 'string', ['limit' => 127])
                   ->addColumn('slug', 'string', ['limit' => 127])
                   ->addColumn('content', 'text')
                   ->addColumn('excerpt', 'text')
                   ->addColumn('status', 'string', ['limit' => 15])
                   ->addColumn('author_id', 'integer')
                   ->addForeignKey('author_id', 'authors', 'id', ['delete' => 'SET_NULL', 'update' => 'NO_ACTION'])
                   ->addColumn('created_time', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
                   ->addColumn('updated_time', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
                   ->create();

        $tableCategories = $this->table('categories');
        $tableCategories->addColumn('title', 'string', ['limit' => 127])
                        ->addColumn('slug', 'string', ['limit' => 127])
                        ->addColumn('description', 'text')
                        ->addColumn('parent_category_id', 'integer')
                        ->addForeignKey('parent_category_id', 'categories', 'id', ['delete' => 'SET_NULL', 'update' => 'NO_ACTION'])
                        ->create();

        $tablePostCategory = $this->table('post_category');
        $tablePostCategory->addColumn('post_id', 'integer')
                          ->addForeignKey('post_id', 'posts', 'id', ['delete' => 'SET_NULL', 'update' => 'NO_ACTION'])
                          ->addColumn('category_id', 'integer')
                          ->addForeignKey('category_id', 'categories', 'id', ['delete' => 'SET_NULL', 'update' => 'NO_ACTION'])
                          ->create();

        $tableTags = $this->table('tags');
        $tableTags->addColumn('title', 'string', ['limit' => 127])
                      ->addColumn('slug', 'string', ['limit' => 127])
                      ->addColumn('description', 'text')
                      ->create();

        $tablePostTag = $this->table('post_tag');
        $tablePostTag->addColumn('post_id', 'integer')
                          ->addForeignKey('post_id', 'posts', 'id', ['delete' => 'SET_NULL', 'update' => 'NO_ACTION'])
                          ->addColumn('tag_id', 'integer')
                          ->addForeignKey('tag_id', 'tags', 'id', ['delete' => 'SET_NULL', 'update' => 'NO_ACTION'])
                          ->create();
    }
}
