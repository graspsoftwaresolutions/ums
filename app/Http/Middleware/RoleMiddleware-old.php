<?php

namespace App\Http\Middleware;

use Closure;

class RoleMiddleware-old
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure $next
	 * @param $role
	 * @param null $permission
	 *
	 * @return mixed
	 */
    public function handle($request, Closure $next, $roles_string, $permission = null)
    {
	/**
	 * Fix for "Call to a member function hasRole() on null".
	 * If the user is not logged in, there is no user data to process,
	 * so we need to throw 404 code.
	 */
		if(is_null($request->user())){
            abort(404);
        }
		$role_access =0;
		$roles = explode("|",$roles_string);
	    foreach ($roles as $role) {
			if ($request->user()->hasRole($role)) {
				$role_access = 1;
			}
		}
    	if($role_access == 0) {
			abort(404);
	    }
		if($permission !== null && !$request->user()->can($permission)) {
			abort(404);
		}
        return $next($request);
    }
}
