<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {   
        if($request->path() == '/login'){
            return $next($request); // go to the next middleware
        }

        if(!Auth::check()){
            // return redirect('/login');
            return response()->json([
            'msg' => 'Access Denied ... ',
            'url' => $request->path()
            ], 403);
        }

        $user = Auth::user();
        //  Use users role relation; if user is not an admin deny access 
        if($user->role->isAdmin == 0){
            return response()->json([
                'msg' => 'Access Denied ... ',
                'url' => $request->path()   
            ], 403);
        }elseif ($user->role->isAdmin == 1) {
            // if user's role is of admin type (isAdmin is 1) return go to the next middleware
            return $next($request); 
        }
        }
}
