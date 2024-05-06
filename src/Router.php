<?php

declare(strict_types=1);

namespace DDaniel\Blog;

use DDaniel\Blog\Admin\Authorization;
use DDaniel\Blog\Entities\Post;
use DDaniel\Blog\Enums\Entity;
use DDaniel\Blog\Enums\PostStatus;
use Exception;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Doctrine\Laminas\Hydrator\DoctrineObject as DoctrineHydrator;

final class Router
{
    private RouteCollection $routes;
    private RequestContext $context;

	private readonly string $currentRoute;

    public function __construct()
    {
        $this->routes  = new RouteCollection();
        $this->context = new RequestContext($_SERVER['REQUEST_URI'] ?? '', $_REQUEST['method'] ?? $_SERVER['REQUEST_METHOD'] ?? '');

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

            $this->currentRoute = explode('@', strtolower($parameters['_route']))[0];

            $parameters['function']($parameters);
        } catch (ResourceNotFoundException $e) {
            $this->sendError(404, $e->getMessage() ?: 'Не найдено');
        } catch (MethodNotAllowedException $e) {
            $this->sendError(405, 'Неизвестный url');
        } catch (Exception $e) {
            error_log('Routing error ' . $e);
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

    public function isCurrentRoute(string|array $route): bool
    {
        if (is_string($route)) {
            return $this->currentRoute === strtolower($route);
        }

        foreach ($route as $r) {
            if ($this->isCurrentRoute($r)) {
                return true;
            }
        }

        return false;
    }

    public function getRoutePath(string $name, array $replacements = []): string
    {
        $relativePath = $this->routes->get($name)?->getPath();

        foreach ($replacements as $search => $replace) {
            $relativePath = str_replace('{'.$search.'}', (string)$replace, $relativePath);
        }

        return app()->site_url . $relativePath;
    }

    public function getUrlForEntityAdmin(object $entity): string
    {
        $entityName = Entity::fromEntityClass($entity::class)->value;

        return $entity->isNull() ?
            $this->getRoutePath('adminEntityNew', [
                'entity' => $entityName,
            ]) :
            $this->getRoutePath('adminEntityEdit', [
                'entity' => $entityName,
                'id'     => $entity->getId(),
            ]);
    }

    public function getUrlForEntityFrontend(object $entity): string
    {
        return $this->getRoutePath('entity', [
            'entity' => Entity::fromEntityClass($entity::class)->value,
            'slug'   => $entity->getSlug(),
        ]);
    }

    public function redirectToRoute(string $name, array $replacements = []): never
    {
        header('Location: ' . $this->getRoutePath($name, $replacements));
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

        die();
    }

    public function getEntityType(array $params): Entity
    {
        $entity = Entity::tryFrom($params['entity']);

        if(null === $entity) {
            throw new ResourceNotFoundException( "Entity {$params['entity']} not exist" );
        }

        return $entity;
    }

    public function getEntity(array $params): object
    {
        $entity = Entity::tryFrom($params['entity']);

        if(null === $entity) {
            throw new ResourceNotFoundException( "Entity {$params['entity']} not exist" );
        }

        $entityObject = app()->em->find($this->getEntityType($params)->getEntityClass(), $params['id']);

        if(null === $entityObject) {
            throw new ResourceNotFoundException("Entity {$params['entity']} with id {$params['id']} not exist");
        }

        return $entityObject;
    }

    private function initRoutes(): void
    {
        //Login
        $this->addRoute('login', 'GET', '/login', function (array $params) {
            //if(app()->isAuthorized) {
            //    $this->redirectToRoute('admin');
            //}

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
			    app()->templates->include('admin/wrapper', [
				    'title'   => 'Admin panel',
				    'content' => app()->templates->include('admin/login', [
					    'error'  => $e->getMessage()
				    ], false)
			    ]);
		    }
	    }, false);

	    $this->addRoute('logout', 'POST', '/logout', function (array $params) {
		    (new Authorization())->vanish();
		    $this->redirectToRoute('login');
	    }, true);

        //Admin
        $this->addRoute('admin', 'GET', '/admin', function (array $params) {
            app()->templates->include('admin/wrapper', [
                'title'   => 'Admin panel',
                'content' => app()->templates->include('admin/dashboard', echo: false)
            ]);
        }, true);

        $this->addRoute('adminEntitiesList', 'GET', '/admin/{entity}', function (array $params) {
            $entity = Entity::tryFrom($params['entity']);

            if(null === $entity) {
                throw new ResourceNotFoundException();
            }

            app()->templates->include('admin/wrapper', [
                'title'   => $entity->name . ' list',
                'content' => app()->templates->include('admin/' . $entity->value . '/list', [
                    'entities' => app()->em->getRepository($entity->getEntityClass())->findBy([], ['id' => 'DESC'])
                ], false)
            ]);
        }, true);

        $this->addRoute('adminEntityNew', 'GET', '/admin/{entity}/new', function (array $params) {
            $entity = $this->getEntityType($params);

            app()->templates->include('admin/wrapper', [
                'title'   => $entity->name . ' new',
                'content' => app()->templates->include('admin/' . $entity->value . '/item', [
                    'entity' => new ($entity->getEntityClass())()
                ], false),
            ]);
        }, true);

        $this->addRoute('adminEntityEdit', 'GET', '/admin/{entity}/{id}', function (array $params) {
            $entityType = $this->getEntityType($params);
            $entity = $this->getEntity($params);

            app()->templates->include('admin/wrapper', [
                'title'   => sprintf('%s #%d edit', $entityType->name, $entity->getId()),
                'content' => app()->templates->include('admin/' . $entityType->value . '/item', [
                    'entity' => $entity
                ], false)
            ]);
        }, true);

	    $this->addRoute('adminEntityEdit@post', 'POST', '/admin/{entity}/new', function (array $params) {
		    $entityType = $this->getEntityType($params);

		    $entity = ( new DoctrineHydrator( app()->em ) )->hydrate( $_POST, new ( $entityType->getEntityClass() )() );

            try {
                app()->em->persist($entity);
                app()->em->flush();

                $this->redirectToRoute('adminEntityEdit', [
                    'entity' => $entityType->value,
                    'id' => $entity->getId()
                ]);
            } catch (Exception $e) {
                $entity->setId(0);

                app()->templates->include('admin/wrapper', [
                    'title'   => $entityType->name . ' new (with error)',
                    'content' => app()->templates->include('admin/' . $entityType->value . '/item', [
                        'entity' => $entity,
                        'error'  => $e->getMessage()
                    ], false),
                ]);
            }
	    }, true);

        $this->addRoute('adminEntityEdit@patch', 'PATCH', '/admin/{entity}/{id}', function (array $params) {
            $entityType = $this->getEntityType($params);
            $entity = $this->getEntity($params);

            foreach ($_POST as $prop => $value) {
                $methodName = 'set'. ucfirst($prop);

                if(method_exists($entity, $methodName)) {
                    $entity->{$methodName}($value);
                }
            }

            app()->em->flush();

            $this->redirectToRoute('adminEntityEdit', [
                'entity' => $entityType->value,
                'id'     => $entity->getId(),
            ]);
        }, true);

        $this->addRoute('adminEntityEdit@delete', 'DELETE', '/admin/{entity}/{id}', function (array $params) {
            $entityType = $this->getEntityType($params);
            $entity = $this->getEntity($params);

            app()->em->remove($entity);
            app()->em->flush();

            $this->redirectToRoute('adminEntitiesList', [
                'entity' => $entityType->value
            ]);
        }, true);

        //Public
        $this->addRoute('home', 'GET', '/', function () {
            $entities = app()->em->getRepository(Entity::Post->getEntityClass())->findBy([
                'status' => app()->isAuthorized ? [ PostStatus::Published, PostStatus::Draft, PostStatus::Hidden ] : PostStatus::Published
            ], ['id' => 'DESC']);

            app()->templates->include('wrapper', [
                'title'       => 'Web разработка от Даниила Дубченко',
                'description' => 'Web разработка от Даниила Дубченко',
                'content' => app()->templates->include('entities/' . Entity::Post->value . '/list', [
                    'entities' => $entities
                ], false)
            ]);
        }, false);
        //
        //$this->addRoute('entitiesList', 'GET', 'post', function (array $params) {
        //    $entities = app()->em->getRepository(Entity::Post->getEntityClass())->findBy([
        //        'status' => PostStatus::Published
        //    ]);
        //
        //    app()->templates->include('wrapper', [
        //        'title'   => Entity::Post->name . ' list',
        //        'content' => app()->templates->include('entities/' . Entity::Post->value . '/list', [
        //            'entities' => $entities
        //        ], false)
        //    ]);
        //}, false);

        $this->addRoute('entity', 'GET', 'post/{slug}', function (array $params) {
            /**
             * @var Post $post
             */
            $post = app()->em->getRepository(Entity::Post->getEntityClass())->findOneBySlug($params['slug']);

            if(null === $post) {
                throw new ResourceNotFoundException();
            }

            if($post->getStatus() !== PostStatus::Published && !app()->isAuthorized) {
                throw new ResourceNotFoundException();
            }

            app()->templates->include('wrapper', [
                'title'   => sprintf('%s %s', Entity::Post->name, $post->getTitle()),
                'description' => $post->getExcerpt(),
                'content' => app()->templates->include('entities/' . Entity::Post->value . '/item', [
                    'entity' => $post
                ], false)
            ]);
        }, false);
    }
}
