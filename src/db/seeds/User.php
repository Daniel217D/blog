<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class User extends AbstractSeed
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
	    shell_exec('php /var/www/html/bin/createAuthor.php test 123456 test test@test.com');
    }
}
