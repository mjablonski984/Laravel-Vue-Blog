<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {   
        return response()->json(Tag::orderBy('id', 'desc')->get());
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'tagName' => 'required'
        ]);
        return Tag::create([
            'tagName' => $request->tagName
        ]);
    }


    public function update(Request $request)
    {
        $this->validate($request, [
            'tagName' => 'required', 
            'id' => 'required', 
        ]);
        return Tag::where('id', $request->id)->update([
            'tagName' => $request->tagName
        ]);
    }


    public function destroy(Request $request)
    {
        $this->validate($request, [
            'id' => 'required', 
        ]);
        return Tag::where('id', $request->id)->delete();
    }
}
