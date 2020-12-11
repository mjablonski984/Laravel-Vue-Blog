<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{

    public function index()
    {   
        return response()->json(Role::orderBy('id', 'desc')->get());
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'roleName' => 'required|unique:roles,roleName'
        ]);
        return  Role::create([
            'roleName' => $request->roleName
        ]);
    }


    public function update(Request $request)
    {
        $this->validate($request, [
            'roleName' => 'required|unique:roles,roleName',
        ]);
        return  Role::where('id', $request->id)->update([
            'roleName' => $request->roleName
        ]);
    }


    public function destroy(Request $request)
    {
        $this->validate($request, [
            'id' => 'required', 
        ]);
        Role::where('id', $request->id)->delete();
        return $request->id;
    }


    public function assign(Request $request){
        $this->validate($request, [
            'permission' => 'required',
            'id' => 'required',
        ]);
        return Role::where('id', $request->id)->update([
            'permission' => $request->permission
        ]);
    }
}
