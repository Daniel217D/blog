<?php

declare(strict_types=1);

namespace DDaniel\Blog;

use DDaniel\Blog\Admin\Authorization;
use DDaniel\Blog\Articles\Article;
use DDaniel\Blog\Articles\ArticleNotFoundException;
use DDaniel\Blog\Articles\Articles;
use DDaniel\Blog\PageControllers\AdminPageController;
use DDaniel\Blog\PageControllers\BasePageController;
use DDaniel\Blog\PageControllers\PageController;
use Exception;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

final class Router
{
    private RouteCollection $routes;
    private RequestContext $context;

    private bool $response_in_json = false;

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

    public function addRoute(string $name, string|array $methods, string $path, callable $function): void
    {
        $this->routes->add($name, new Route(
            path: $path,
            defaults: [ 'function' => $function ],
            methods: is_array($methods) ? $methods : [ $methods ]
        ));
    }

	public function getRoutePath( string $name ): string {
		return app()->site_url . $this->routes->get( $name )->getPath();
	}

	public function redirectToRoute( string $name ): never {
		header( 'Location: ' . $this->getRoutePath( $name ) );
		die();
	}

    public function renderPage(BasePageController $page_controller): void
    {
        app()->templates->include(
            $page_controller instanceof AdminPageController ? 'admin/wrapper' : 'wrapper',
            array( 'pc' => $page_controller )
        );
    }

    public function sendJson(mixed $data): void
    {
        header("Content-type: application/json; charset=utf-8");

        echo json_encode($data);

        die();
    }

    public function send404(string $message = ''): void
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

    private function initRoutes(): void
    {
        $this->addRoute('home', 'GET', '/', function () {
            $this->renderPage(new PageController(
                title: 'Блог о Web разработке Даниила Дубченко',
                description: 'Блог Даниила Дубченко о web-разработке',
                content: ( new Articles(app()->search_string) )->getContentHtml()
            ));
        });

	    $this->addRoute('admin', 'GET', '/admin', function (array $params) {
		    $this->renderPage(new AdminPageController(
			    title: 'Admin panel',
			    content: app()->isAuthorized ?
				    app()->templates->include('admin/dashboard', echo: false) :
				    app()->templates->include('admin/login', echo: false)
		    ));
	    });

	    $this->addRoute('login', 'POST', '/login', function (array $params) {
		    try {
			    ( new Authorization() )->authorize( $_POST['email'] ?? '', $_POST['password'] ?? '');
			    $this->redirectToRoute('admin');
		    } catch (Exception $e) {
				$this->redirectToRoute('admin'); //ToDo add error message
		    }
	    });

        $this->addRoute('article', 'GET', '/{slug}', function (array $params) {
            $this->renderPage(new PageController(
                title: $params['slug'],
                content: Article::foundOrFail($params['slug'])->getContentHtml(),
                type: 'article'
            ));
        });
        $this->addRoute('article_api', 'GET', '/api/articles/{slug}', function (array $params) {
            $content = Article::foundOrFail($params['slug'])->getContentHtml();
            $this->sendJson(array(
                'success' => true,
                'content' => $content,
            ));
        });
    }
}
