<?php

declare(strict_types=1);

namespace DDaniel\Blog;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

final class App
{
    public readonly string $path;
    public readonly string $home_url;
    public readonly string $current_url;
    public readonly string $search_string;

    public readonly string $site_name;
    public readonly string $site_url;
    public readonly bool $debug_enabled;

    public readonly Assets $assets;
    public readonly Templates $templates;

    public readonly EntityManager $entity_manager;

    public function __construct()
    {
        $this->path = dirname(__DIR__) . '/';
        $this->home_url = '/';
        $this->current_url = sprintf(
            "%s://%s%s",
            isset($_SERVER['HTTPS']) ? 'http' : 'https',
            $_SERVER['HTTP_HOST'] ?? '',
            $_SERVER['REQUEST_URI'] ?? ''
        );
        $this->search_string = isset( $_GET['s'] ) && is_string( $_GET['s'] ) ? $_GET['s'] : '';

        $env = @parse_ini_file("$this->path/.env");
        $this->site_name = $env['APP_NAME'];
        $this->site_url = $env['APP_URL'];
        $this->debug_enabled = 'true' === $env['APP_DEBUG'];

        ini_set('log_errors', 1);
        ini_set('error_log', "{$this->path}error.log");

        $this->assets = new Assets("{$this->path}public/assets", '/assets');
        $this->templates = new Templates("{$this->path}templates/");

        $this->initOrm($env);
    }

    private function initOrm(array $env): void
    {
        $config = ORMSetup::createAttributeMetadataConfiguration(
            paths: array("{$this->path}src/Models"),
            isDevMode: $env['APP_ENV'] !== 'production',
        );

        $connection = DriverManager::getConnection([
            'driver' => 'pdo_pgsql',
            'host' => $env['DB_HOST'],
            'port' => null,
            'dbname' => $env['DB_NAME'],
            'user' => $env['DB_USER'],
            'password' => $env['DB_PASSWORD'],
        ], $config);

        $this->entity_manager = new EntityManager($connection, $config);
    }

    public function start(): void
    {
        ( new Router() )->processRequest();
    }
}
