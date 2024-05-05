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
        $tableUsers->addColumn('login', 'string', ['limit' => 15, 'null' => false])
                   ->addColumn('password', 'string', ['limit' => 255, 'null' => false])
                   ->addColumn('name', 'string', ['limit' => 31, 'null' => false])
                   ->addColumn('email', 'string', ['limit' => 127, 'null' => false])
                   ->addColumn('role', 'string', ['limit' => 15, 'null' => false])
                   ->create();

        $tablePosts = $this->table('posts');
        $tablePosts->addColumn('title', 'string', ['limit' => 127, 'null' => false])
                   ->addColumn('slug', 'string', ['limit' => 127, 'null' => false])
                   ->addColumn('content', 'text', ['null' => false])
                   ->addColumn('excerpt', 'text', ['null' => true])
                   ->addColumn('status', 'string', ['limit' => 15, 'null' => false])
                   ->addColumn('author_id', 'integer', ['null' => false])
                   ->addForeignKey('author_id', 'authors', 'id', ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION'])
                   ->addColumn('created_time', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false])
                   ->addColumn('updated_time', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false])
                   ->create();

        $tableCategories = $this->table('categories');
        $tableCategories->addColumn('title', 'string', ['limit' => 127, 'null' => false])
                        ->addColumn('slug', 'string', ['limit' => 127, 'null' => false])
                        ->addColumn('description', 'text', ['null' => true])
                        ->addColumn('parent_category_id', 'integer', ['null' => true])
                        ->create();

        $tablePostCategory = $this->table('post_category', ['id' => false, 'primary_key' => ['post_id', 'category_id']]);
        $tablePostCategory->addColumn('post_id', 'integer', ['null' => false])
                          ->addForeignKey('post_id', 'posts', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
                          ->addColumn('category_id', 'integer', ['null' => false])
                          ->addForeignKey('category_id', 'categories', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
                          ->create();

        $tableTags = $this->table('tags');
        $tableTags->addColumn('title', 'string', ['limit' => 127, 'null' => false])
                  ->addColumn('slug', 'string', ['limit' => 127, 'null' => false])
                  ->addColumn('description', 'text')
                  ->create();

        $tablePostTag = $this->table('post_tag', ['id' => false, 'primary_key' => ['post_id', 'tag_id']]);
        $tablePostTag->addColumn('post_id', 'integer', ['null' => false])
                     ->addForeignKey('post_id', 'posts', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
                     ->addColumn('tag_id', 'integer', ['null' => false])
                     ->addForeignKey('tag_id', 'tags', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
                     ->create();
    }
}
