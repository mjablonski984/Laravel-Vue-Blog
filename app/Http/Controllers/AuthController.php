<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{   

    public function index(Request $request)
    {
        // first check if you are loggedin and admin user ... 
        if(!Auth::check() && $request->path() != '/login'){
            return redirect('/login'); // not logged in and not on the login page (redirect to login)
        }
        if(!Auth::check() && $request->path() == '/login' ){
            return view('index'); // not logged in and already on main page (render main page)
        }
        // you are already logged in... so check for if you are an admin user.. 
        $user = Auth::user();
        
        if($user->role->isAdmin == 0){
            return redirect('/'); 
            // return redirect('/login'); 
        }
        if($request->path() == 'login'){
            return redirect('/');
        }
        
        return view('index');
    }


    public function auth ()
    {   
        if(Auth::check()){
            $user = Auth::user();
            $role = Auth::user()->role;
            return response()->json(['user'=> $user,'role' => $role]);
        }
        else{
            return response()->json(['user'=> false, 'msg'=>'User is not authenticated']);
        }
    }


    public function login(Request $request){
            
        $this->validate($request, [
            'email' => 'bail|required|email',
            'password' => 'bail|required|min:6',
        ]);
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();

            // Automatically logout if user role isn't admin type
            if($user->role->isAdmin == 0){
                // Auth::logout();
                return response()->json([
                    'redirect' => '/', 
                    'msg' => 'User, You are logged in', 
                    'user' => $user,
                    'role' => $user->role
                ]);
            }
            return response()->json([
                'redirect' => '/app', 
                'msg' => 'Admin, You are logged in', 
                'user' => $user,
                'role' => $user->role
            ]);
        }else{
            return response()->json([
                'msg' => 'Incorrect login details', 
            ], 401);
        }
    }
    

    public function logout(){
        Auth::logout();
        return response()->json([
            'msg' => 'Logout succesfull', 
        ], 200);
        return redirect('/');
    }

}
