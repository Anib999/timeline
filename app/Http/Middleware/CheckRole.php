<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$role) {
        if ($request->user() === null) {
           abort(401,'Insufficent Permission');
        }
        
       $roles = explode('|',$role);
 
        
        $actions = $request->route()->getAction();
        $roless = isset($actions['roles'])? $actions['roles'] : null;
    //    var_dump($roless,$roles); exit();
        if ($request->user()->hasAnyRole($roles) || $roless || $request->user()->hasRole('SuperAdmin') ) {
            return $next($request);
        }
       abort(401,'Insufficent Permission');
    }

}
