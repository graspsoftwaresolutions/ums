<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Route;


use Closure;

class ModuleMiddleware
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
    public function handle($request, Closure $next, $module, $permission = null)
    {
	/**
	 * Fix for "Call to a member function hasRole() on null".
	 * If the user is not logged in, there is no user data to process,
	 * so we need to throw 404 code.
	 */
		$defdaultLang = app()->getLocale();
		if(!empty($request->user())){
			$get_roles = $request->user()->roles;
			foreach($get_roles as $role){
				$login_role = $role->id;
				//$controller_name = (class_basename(\Route::current()->controller));
				$project_modules = DB::table('form_type')->where('status',1)->where('module',$module)->get();
				$role_models = DB::table('roles_modules')->where('role_id',$login_role)->get();
				$module_id =$project_modules[0]->id;
				$access_count = DB::table('roles_modules')->where('role_id',$login_role)->where('module_id',$module_id)->count();
				if($access_count==0){
					return redirect($defdaultLang.'/home')->with('error','You are not authorized to access these module'); 
				}
				/* 	die;
				foreach($project_modules as $module){
					
					 $route_name = app()->router->getCurrentRoute(); 
					 $controller_name = (class_basename(\Route::current()->controller)); 
					//print_r($request);
					die;
				}
				return $role_models;
				die;
				//return redirect()->intended('home2');
				return redirect($login_role); */
			}
			//$user_role = $get_roles[0]->slug;
		}
		//abort(404);
		if(is_null($request->user())){
            abort(404);
        }
		/* $role_access =0;
		$roles = explode("|",$roles_string);
	    foreach ($roles as $role) {
			if ($request->user()->hasRole($role)) {
				$role_access = 1;
			}
		}
    	if($role_access == 0) {
			abort(404);
	    } */
		if($permission !== null && !$request->user()->can($permission)) {
			abort(404);
		}
        return $next($request);
    }
}
