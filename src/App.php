<?php

declare(strict_types=1);

namespace DDaniel\Blog;

use DDaniel\Blog\Admin\Authorization;
use DDaniel\Blog\Entities\Author;
use DDaniel\Blog\Entities\Setting;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

final class App
{
    public readonly string $path;
    public readonly string $home_url;
    public readonly string $current_url;
    public readonly bool $is_home_page;
    public readonly string $search_string;

    public readonly string $site_name;
    public readonly string $site_url;
    public readonly bool $debug_enabled;

    public readonly Assets $assets;
    public readonly Templates $templates;

	public readonly ?Author $author;
	public readonly bool $isAuthorized;

	public readonly Router $router;

	public readonly EntityManager $em;

	private readonly array $env;

    public function __construct()
    {
        $this->path = dirname(__DIR__) . '/';

        ini_set('log_errors', 1);
        ini_set('error_log', "{$this->path}error.log");

        $this->env = @parse_ini_file("$this->path.env");
        $this->site_name = $this->env['APP_NAME'];
        $this->site_url = $this->env['APP_URL'];
        $this->debug_enabled = '1' === $this->env['APP_DEBUG'];

        $this->home_url = $this->site_url;
        $this->current_url = sprintf(
            "%s://%s%s",
            (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== 'off') || (!empty($_SERVER["HTTP_X_FORWARDED_SSL"]) && $_SERVER["HTTP_X_FORWARDED_SSL"] !== 'off') || (!empty($_SERVER["HTTP_X_FORWARDED_PROTO"]) && $_SERVER["HTTP_X_FORWARDED_PROTO"] === 'https')
                ? 'https' : 'http',
            $_SERVER['HTTP_HOST'] ?? '',
            rtrim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/') ?? ''
        );

        $this->is_home_page = $this->current_url === $this->site_url;
        $this->search_string = isset( $_GET['s'] ) && is_string( $_GET['s'] ) ? $_GET['s'] : '';
    }

    public function init(): App
    {
		$this->initOrm();

	    $this->assets = new Assets("{$this->path}public/assets", '/assets');
	    $this->templates = new Templates("{$this->path}templates/");

	    $this->author = ( new Authorization() )->getAuthor();
	    $this->isAuthorized = $this->author !== null;

		$this->router = new Router();

		return $this;
    }

	protected function initOrm(): void {
		$config = ORMSetup::createAttributeMetadataConfiguration(
			paths: array("{$this->path}src/Entities"),
			isDevMode: $this->env['APP_ENV'] !== 'production',
		);

		$connection = DriverManager::getConnection([
			'driver' => 'pdo_pgsql',
			'host' => $this->env['DB_HOST'],
			'port' => null,
			'dbname' => $this->env['DB_NAME'],
			'user' => $this->env['DB_USER'],
			'password' => $this->env['DB_PASSWORD'],
		], $config);

		$this->em = new EntityManager($connection, $config);
	}

    public function start(): void
    {
        $this->router->processRequest();
    }

    /**
     * @param  string  $key
     *
     * @return mixed|null null if setting not found
     */
    public function getSetting(string $key): mixed {
        return $this->em->getRepository(Setting::class)->findOneBy(
            ['key' => $key]
        )?->getValue();
    }
}
