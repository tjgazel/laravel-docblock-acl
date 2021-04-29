<?php

namespace TJGazel\LaravelDocBlockAcl;

use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as RouteFacade;

/**
 * Class Acl
 * @package TJGazel\LaravelDocBlockAcl
 */
class Acl
{
    /**
     * @var array $middleware
     */
    protected $middleware;

    /**
     * @var string $prefixURL
     */
    protected $prefixURL;

    /**
     * @var string $prefixRouteName
     */
    protected $prefixRouteName;

    /**
     * @var array $defaultOptions
     */
    protected $defaultOptions = [
        'middleware' => ['auth', 'acl'],
        'prefix' => 'acl',
        'name' => 'acl.',
    ];

    /**
     * @param array $options ['middleware' => ['auth', 'acl'], 'prefix' => 'acl', 'name' => 'acl.'];
     */
    public function routes(array $options = [])
    {
        $this->mergeDefaultOptions($options);

        RouteFacade::middleware($this->middleware)
            ->prefix($this->prefixURL)
            ->name($this->prefixRouteName)
            ->namespace('\TJGazel\LaravelDocBlockAcl\Http\Controllers')
            ->group(function () {
                RouteFacade::name('index')->get('', 'AclController@index');
                RouteFacade::name('store')->post('', 'AclController@store');
                RouteFacade::name('create')->get('create', 'AclController@create');
                RouteFacade::name('edit')->get('{id}/edit', 'AclController@edit');
                RouteFacade::name('update')->put('{id}', 'AclController@update');
                RouteFacade::name('destroy')->delete('{id}', 'AclController@destroy');
            });
    }

    /**
     * Returns the middleware set of the route
     * Retorna o conjunto de middlewares da rota
     *
     * @return string
     */
    public function getMiddleware()
    {
        return $this->middleware;
    }

    /**
     * Returns route url prefix
     * Retorna prefixo da url da rota
     *
     * @return string
     */
    public function getPrefixURL()
    {
        return $this->prefixURL;
    }

    /**
     * Return route name prefix
     * Retorna prefixo do nome da rota
     *
     * @return string
     */
    public function getPrefixRouteName()
    {
        return $this->prefixRouteName;
    }

    /**
     * Returns collection of permissions to be synchronized.
     * Retorna coleção das permissões as serem sincronizadas.
     *
     * @return \Illuminate\Support\Collection
     */
    public function mapPermissions()
    {
        return $this->routesWithPermission()->map(function (Route $route) {
            $actionName = $route->getActionName();

            if (strstr($actionName, '@')) {
                $controllerMethods = explode('@', $actionName);
            }

            $controller = $controllerMethods[0] ?? $actionName;
            $method = $controllerMethods[1] ?? 'render';
            $reflectionClass = new \ReflectionClass($controller);
            $name = strstr(
                $reflectionClass->getMethod($method)->getDocComment(),
                "@permissionName('"
            );
            $name = strstr($name, "')", true);
            $name = (explode("('", $name))[1] ?? $actionName;
            $resource = strstr($reflectionClass->getDocComment(), "@permissionResource('");
            $resource = strstr($resource, "')", true);
            $resource = (explode("('", $resource))[1] ?? $actionName;

            return ['name' => $name, 'resource' => $resource, 'action' => $actionName];
        });
    }

    /**
     * Returns routes that contain permission labels.
     * Retorna rotas que contém rótulos de permissão.
     *
     * @return \Illuminate\Support\Collection
     */
    public function routesWithPermission()
    {
        return collect(RouteFacade::getRoutes()->getIterator())->filter(function (Route $route) {
            $actionName = $route->getActionName();

            if (strstr($actionName, '@')) {
                $controllerMethods = explode('@', $actionName);
            }

            $controller = $controllerMethods[0] ?? $actionName;
            $method = $controllerMethods[1] ?? 'render';
            $reflectionClass = new \ReflectionClass($controller);

            if ($reflectionClass->hasMethod($method)) {
                return strstr($reflectionClass->getMethod($method)->getDocComment(), "@permissionName('");
            }

            return false;
        });
    }

    /**
     * @param array $options
     * @return void
     */
    protected function mergeDefaultOptions(array $options)
    {
        $merged = count($options) > 0 ? array_merge($this->defaultOptions, $options) : $this->defaultOptions;

        $this->middleware = $merged['middleware'];
        $this->prefixURL = "/{$merged['prefix']}";
        $this->prefixRouteName = "{$merged['name']}";
    }
}
