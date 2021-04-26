<?php

namespace TJGazel\LaravelDocBlockAcl;

use Illuminate\Support\Facades\Route as RouteFacade;
use Illuminate\Routing\Route;

class Acl
{
	private $middleware;
	private $prefixURL;
	private $prefixRouteName;

	public function routes(array $options = [])
	{
		$default = [
			'middleware' => ['auth', 'acl'],
			'prefix' => 'acl',
			'name' => 'acl.'
		];

		$config = count($options) > 0 ? array_merge($default, $options) : $default;

		$this->middleware = $config['middleware'];
		$this->prefixURL = "/{$config['prefix']}";
		$this->prefixRouteName = "{$config['name']}";

		RouteFacade::middleware($config['middleware'])
			->prefix($config['prefix'])
			->name($config['name'])
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
			$controllerMethods = explode('@', $actionName);

			$reflectionClass = new \ReflectionClass($controllerMethods[0]);

			$method = strstr(
				$reflectionClass->getMethod($controllerMethods[1])->getDocComment(),
				"@permissionName('"
			);
			$method = strstr($method, "')", true);
			$method = (explode("('", $method))[1] ?? $controllerMethods[1];

			$controller = strstr($reflectionClass->getDocComment(), "@permissionResource('");
			$controller = strstr($controller, "')", true);
			$controller = (explode("('", $controller))[1] ?? $controllerMethods[0];

			return ['name' => $method, 'resource' => $controller, 'action' => $actionName];
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

				$reflectionClass = new \ReflectionClass($controllerMethods[0]);

				return strstr($reflectionClass->getMethod($controllerMethods[1])->getDocComment(), "@permissionName('");
			}

			return false;
		});
	}
}
