<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{


    public function index()
    {   
        return response()->json(Category::orderBy('id', 'desc')->get());
    }
    
    
    public function store(Request $request)
    {
        $this->validate($request, [
            'categoryName' => 'required',
            'iconImage' => 'required',
        ]);
        return Category::create([
            'categoryName' => $request->categoryName,
            'iconImage' => $request->iconImage,
        ]);
    }
    
    
    public function update(Request $request)
    {   
        $this->validate($request, [
            'categoryName' => 'required',
            'iconImage' => 'required',
        ]);
        return Category::where('id', $request->id)->update([
            'categoryName' => $request->categoryName,
            'iconImage' => $request->iconImage,
        ]);
    }


    public function destroy(Request $request)
    {
        // First delete the original file from the server (deleteFileFromServer method is located in Contoller class)
        $this->deleteFileFromServer($request->iconImage); 
         
        $this->validate($request, [
            'id' => 'required', 
        ]);
        return Category::where('id', $request->id)->delete();
    }
    
}
