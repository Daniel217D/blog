<?php

declare(strict_types=1);

namespace DDaniel\Blog;

use DDaniel\Blog\Admin\Authorization;
use DDaniel\Blog\Entities\Author;
use DDaniel\Blog\Entities\BaseEntity;
use DDaniel\Blog\Entities\Post;
use DDaniel\Blog\Entities\Setting;
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

    public function getUrlForEntityAdmin(BaseEntity $entity): string
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

    public function getEntity(array $params, string $paramName = 'id'): BaseEntity
    {
        $entity = Entity::tryFrom($params['entity']);

        if(null === $entity) {
            throw new ResourceNotFoundException( "Entity {$params['entity']} not exist" );
        }

        $entityObject = app()->em->getRepository(Entity::from($params['entity'])->getEntityClass())->findOneBy(
            [$paramName => $params[$paramName]]
        );

        if(null === $entityObject) {
            throw new ResourceNotFoundException("Entity {$params['entity']} with id {$params['id']} not exist");
        }

        return $entityObject;
    }

    private function initRoutes(): void
    {
        //Login
        $this->addRoute('login', 'GET', '/login', function (array $params) {
            if(app()->isAuthorized) {
                $this->redirectToRoute('admin');
            }

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

        $this->addRoute('settings', 'GET', '/admin/settings', function () {
            app()->templates->include('admin/wrapper', [
                'title'   => 'settings',
                'content' => app()->templates->include('admin/settings/page', [
                    'settings' => app()->em->getRepository(Setting::class)->findAll()
                ], false)
            ]);
        }, true);

        $this->addRoute('settings@put', 'PUT', '/admin/settings', function () {
            $settingsNew = $_POST['setting'];

            /**
             * @var <int, Setting> $settingsCurrent
             */
            $settingsCurrent = app()->em
                ->createQueryBuilder()
                ->select('s')
                ->from(Setting::class, 's', 's.id')
                ->getQuery()
                ->getResult();


            $hydrator = new DoctrineHydrator( app()->em );

            foreach ($settingsNew as $id => $settingData) {
                if (isset($settingsCurrent[$id])) {
                    $settingsCurrent[$id]->setKey($settingData['key']);
                    $settingsCurrent[$id]->setValue($settingData['value']);
                } else {
                    $setting = $hydrator->hydrate($settingData, new Setting());

                    if( $setting->getKey() !== '' && $setting->getValue() !== '' ) {
                        app()->em->persist($setting);
                    }
                }
            }

            foreach ($settingsCurrent as $setting) {
                if ( ! isset($settingsNew[$setting->getId()])) {
                    app()->em->remove($setting);
                }
            }

            app()->em->flush();

            $this->redirectToRoute('settings' );
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
                'title'       => app()->site_name,
                'description' => app()->site_name,
                'content' => app()->templates->include('entities/' . Entity::Post->value . '/list', [
                    'entities' => $entities
                ], false)
            ]);
        }, false);

        $this->addRoute('sitemap', 'GET', 'sitemap.xml', function (array $params) {
            $sitemapGenerator = new SitemapGenerator();
            $sitemapGenerator->setHeaders();
            $sitemapGenerator->printXml();
        }, false);

        $this->addRoute('entitiesList', 'GET', '{entity}', function (array $params) {
            $entityType = $this->getEntityType($params);

            if($entityType === Entity::Author) {
                throw new ResourceNotFoundException();
            } elseif ($entityType === Entity::Post) {
                $this->redirectToRoute('home');
            }

            app()->templates->include('wrapper', [
                'title'       => "{$entityType->name} список | " . app()->site_name,
                'description' => "{$entityType->name} список | " . app()->site_name,
                'content' => app()->templates->include('entities/' . $entityType->value . '/list', [
                    'entities' => app()->em->getRepository($entityType->getEntityClass())->findAll()
                ], false),
                'breadcrumbs' => app()->templates->include('components/breadcrumbs', [
                    'breadcrumb' => Breadcrumb::generate([
                        ['title' => $entityType->getPluralValue()],
                    ])
                ], false),
            ]);
        }, false);

        $this->addRoute('entity', 'GET', '{entity}/{slug}', function (array $params) {
            $entityType = $this->getEntityType($params);

            if($entityType === Entity::Author) {
                throw new ResourceNotFoundException();
            }

            $entity = $this->getEntity($params, 'slug');

            if ($entity instanceof Post && $entity->getStatus() !== PostStatus::Published && ! app()->isAuthorized) {
                throw new ResourceNotFoundException();
            }

            app()->templates->include('wrapper', [
                'title'   => sprintf('%s | %s', $entity->getTitle(), app()->site_name),
                'description' => $entity->getDescription(),
                'content' => app()->templates->include('entities/' . $entityType->value . '/item', [
                    'entity' => $entity
                ], false),
                'breadcrumbs' => app()->templates->include('components/breadcrumbs', [
                    'breadcrumb' => Breadcrumb::generate([
                        [
                            'title' => $entityType->getPluralValue(),
                            'link' => app()->router->getRoutePath('entitiesList', ['entity' => $entityType->value])
                        ],
                        [
                            'title' => $entity->getTitle()
                        ],
                    ])
                ], false),
            ]);
        }, false);
    }
}
