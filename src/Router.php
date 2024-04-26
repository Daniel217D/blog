<?php

declare(strict_types=1);

namespace DDaniel\Blog;

use DDaniel\Blog\Admin\Authorization;
use Exception;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

final class Router
{
    private RouteCollection $routes;
    private RequestContext $context;

    public function __construct()
    {
        $this->routes  = new RouteCollection();
        $this->context = new RequestContext($_SERVER['REQUEST_URI'] ?? '', $_SERVER['REQUEST_METHOD'] ?? '');

        $this->initRoutes();
    }

    public function processRequest(): void
    {
        $matcher = new UrlMatcher($this->routes, $this->context);

        try {
            $parameters = $matcher->match(parse_url($_SERVER["REQUEST_URI"] ?? '', PHP_URL_PATH));

            if ($parameters['mustBeAuthorized'] && ! app()->isAuthorized) {
                $this->sendError(403, 'Нет доступа');
            }

            $parameters['function']($parameters);
        } catch (ResourceNotFoundException $e) {
            $this->sendError(404, 'Неизвестный url');
        } catch (MethodNotAllowedException $e) {
            $this->sendError(405, 'Неизвестный url');
        } catch (Exception $e) {
            error_log('Routing error ' . $e->getMessage());
            error_log(print_r($e, true));
            $this->sendError(500, 'Что-то сломалось');
        }
    }

    public function addRoute(string $name, string|array $methods, string $path, callable $function, $mustBeAuthorized): void
    {
        $this->routes->add(
            $name,
            new Route(
                path: $path,
                defaults: ['function' => $function, 'mustBeAuthorized' => $mustBeAuthorized],
                methods: is_array($methods) ? $methods : [$methods]
            )
        );
    }

    public function getRoutePath(string $name): string
    {
        return app()->site_url . $this->routes->get($name)->getPath();
    }

    public function redirectToRoute(string $name): never
    {
        header('Location: ' . $this->getRoutePath($name));
        die();
    }

    public function sendJson(mixed $data): void
    {
        header("Content-type: application/json; charset=utf-8");

        echo json_encode($data);

        die();
    }

    public function sendError(int $code, string $message = ''): void
    {
        http_response_code($code);

        app()->templates->include('wrapper', [
            'title'       => (string)$code,
            'description' => $message ?: 'Страница не найдена',
            'content'     => $message ?: 'Страница не найдена',
        ]);
    }

    private function initRoutes(): void
    {
        //Login
        $this->addRoute('login', 'GET', '/login', function (array $params) {
            app()->templates->include('admin/wrapper', [
                'title'   => 'Admin panel',
                'content' => app()->templates->include('admin/login', echo: false)
            ]);
        }, false);

        $this->addRoute('login@post', 'POST', '/login', function (array $params) {
            try {
                (new Authorization())->authorize($_POST['email'] ?? '', $_POST['password'] ?? '');
                $this->redirectToRoute('admin');
            } catch (Exception $e) {
                $this->redirectToRoute('login'); //ToDo add error message
            }
        }, false);

        //Admin
        $this->addRoute('admin', 'GET', '/admin', function (array $params) {
            app()->templates->include('admin/wrapper', [
                'title'   => 'Admin panel',
                'content' => app()->templates->include('admin/dashboard', echo: false)
            ]);
        }, true);

        $this->addRoute('adminEntity', 'GET', '/admin/{entity}', function (array $params) {
            echo '!1!';
        }, true);

        $this->addRoute('adminEntityId', 'GET', '/admin/{entity}/{id}', function (array $params) {
            echo '!2!';
        }, true);

        //Public
        $this->addRoute('home', 'GET', '/', function () {
            app()->templates->include('wrapper', [
                'title'       => 'Заметки о Web разработке Д. Дубченко',
                'description' => 'Заметки  о web-разработке Д. Дубченко',
                'content'     => 'articles here',
            ]);
        }, false);

        $this->addRoute('article', 'GET', '{entity}/{slug}', function (array $params) {
            var_dump($params['entity'], $params['slug']);
        }, false);
    }
}
