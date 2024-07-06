<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class Settings extends AbstractSeed
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
        $table = $this->table('settings');

        for ($i = 1; $i <= 9; $i++) {
            $table->insert([
                'key'   => 'Key' . rand(1, 9),
                'value' => 'Value' . rand(1, 9),
                'type'  => rand(0, 1) === 1 ? 'text' : 'textarea',
            ]);
        }

        $table->save();
    }
}
