<?php

namespace DDaniel\Blog;

use DDaniel\Blog\Articles\ArticleNotFoundException;
use Exception;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;

final class Router
{
    private RouteCollection $routes;
    private RequestContext $context;

    private bool $response_in_json = false;

    public function __construct()
    {
        $this->routes  = new RouteCollection();
        $this->context = new RequestContext($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);

        $this->initRoutes();
    }

    public function processRequest()
    {
        $matcher = new UrlMatcher($this->routes, $this->context);

        try {
            $parameters = $matcher->match(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH));

            $this->response_in_json = str_contains($parameters['_route'], '_api') || str_contains(strtolower(getallheaders()['Accept'] ?? ''), 'json');

            $parameters['function']($parameters);
        } catch (ArticleNotFoundException $e) {
            $this->send404($e->getMessage());
        } catch (ResourceNotFoundException $e) {
            $this->send404('Неизвестный url');
        } catch (Exception $e) {
            error_log('Routing error ' . $e->getMessage());
            $this->send404('Что-то сломалось');
        }
    }

    public function addRoute(string $name, string|array $methods, string $path, callable $function)
    {
        $this->routes->add($name, new Route(
            path: $path,
            defaults: [ 'function' => $function ],
            methods: is_array($methods) ? $methods : [ $methods ]
        ));
    }

    public function renderPage(PageController $page_controller): void
    {
        app()->templates->include(
            'wrapper',
            array( 'pc' => $page_controller )
        );
    }

    public function sendJson(mixed $data): void
    {
        header("Content-type: application/json; charset=utf-8");

        echo json_encode($data);

        die();
    }

    public function send404(string $message = '')
    {
        http_response_code(404);

        if ($this->response_in_json) {
            $this->sendJson(array(
                'success' => false,
                'message' => $message
            ));
        }

        $this->renderPage(new PageController(
            title: '404',
            description: $message ?: 'Страница не найдена',
            content: $message ?: 'Страница не найдена'
        ));
    }

    private function initRoutes()
    {
        require app()->path . 'src/routes.php';
    }
}
