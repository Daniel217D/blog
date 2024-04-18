<?php

$env = parse_ini_file(__DIR__ . '/.env');

return
[
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/src/db/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/src/db/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'default',
        'default' => [
	        'adapter' => 'pgsql',
	        'host' => 'db',
	        'name' => $env['DB_NAME'],
	        'user' => $env['DB_USER'],
	        'pass' => $env['DB_PASSWORD'],
	        'port' => '5432',
	        'charset' => 'utf8',
        ]
    ],
    'version_order' => 'creation'
];
