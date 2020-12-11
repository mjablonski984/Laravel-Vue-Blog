<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {   
        return response()->json(User::all());
    }


    public function store(Request $request)
    {
            // validate request 
            $this->validate($request, [
                'name' => 'required',
                'email' => 'bail|required|email|unique:users',
                'password' => 'bail|required|min:6',
                'role_id' => 'required',
            ]);
            $password = bcrypt($request->password);
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $password,
                'role_id' => $request->role_id
            ]);
            return $user;
    }


    public function update(Request $request)
    {
           // validate request 
           $this->validate($request, [
            'name' => 'required',
            'email' => "bail|required|email|unique:users,email,$request->id", //make sure email is unique (ignore email of edited user(get by id)) 
            'password' => 'min:6',
            'role_id' => 'required',
        ]);
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
        ];
        // check if request contains optional password
        if($request->password){
            $password = bcrypt($request->password);
            $data['password'] = $password;
        }

        
        $user = User::where('id', $request->id)->update($data);
        return $user;
    }


    public function destroy(Request $request)
    {   
        
        Post::where('user_id', $request->id)->delete();
        
        $user = User::where('id', $request->id)->first();
        
        if ($user->avatar) {
            $this->deleteFileFromServer($user->avatar); 
        }
        return $user->delete();
    }


    public function updateAvatar(Request $request)
    {      
        $this->validate($request, [
            'avatar' => 'required',
        ]);
        
        $user = Auth::user();

        // Delete old avatar file from the server
        if ($user->avatar) {
            $this->deleteFileFromServer($user->avatar); 
        }
       
        $user->avatar = $request->avatar;
        
        return $user->save();
    }
}
