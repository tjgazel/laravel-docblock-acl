<?php

namespace TJGazel\LaravelDocBlockAcl\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use TJGazel\LaravelDocBlockAcl\Facades\Acl;

/**
 * Class AclMiddleware
 * @package TJGazel\LaravelDocBlockAcl\Http\Middleware
 */
class AclMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!auth()->check()) {
            return $next($request);
        }

        $currentRouteActionName = Route::getCurrentRoute()->getActionName();
        $user = auth()->user();

        foreach (Acl::routesWithPermission() as $route) {
            if ($route->getActionName() == $currentRouteActionName) {
                foreach ($user->groups as $group) {
                    foreach ($group->permissions as $permission) {
                        if ($permission->action == $currentRouteActionName) {
                            return $next($request);
                        }
                    }
                }

                if ($request->ajax()) {
                    return response()->json(__('acl::msg.forbidden'), 403);
                }

                if (class_exists('\\TJGazel\\Toastr\\ToastrServiceProvider')) {
                    toastr()->error(__('acl::msg.forbidden'));
                }

                if ($request->url() != session()->previousUrl()) {
                    return back()->with(Config::get('acl.session_error'), __('acl::msg.forbidden'));
                } else {
                    return redirect('/')->with(Config::get('acl.session_error'), __('acl::msg.forbidden'));
                }
            }
        }
    }
}
