<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {   
        return view('index');
    }

    public function getPosts(Request $request)
    {   
        return response()->json(Post::with(['tag', 'cat','user'])->orderBy('id', 'desc')->paginate($request->total));
    }

    public function getCategories()
    {   
        return response()->json(Category::select('id', 'categoryName', 'iconImage')->get());
    }

    public function postSingle(Request $request, $slug){
        // get post with related user,category and tag data
        $post = Post::where('slug', $slug)->with(['cat','tag' , 'user'])->first(['id', 'title', 'post', 'user_id', 'featuredImage','created_at']);
        
        $category_ids = [];
        foreach ($post->cat as $cat) {
            array_push($category_ids, $cat->id);
        }

        // GET RELATED posts /('id', '!=', $post->id) - excluding currently visited one
        /* whereHas() is similiar to has() but allows you to specify additional filters for the related model to check
        ('cat'  and 'user' are post's relations, $q query instance), use() - specifies variables used in closure  */
        $relatedPosts = Post::with('user')->where('id', '!=', $post->id)->whereHas('cat', function($q) use($category_ids){
            $q->whereIn('category_id',$category_ids);
        })->limit(5)->orderBy('id','desc')->get((['id', 'title', 'postExcerpt', 'slug', 'user_id', 'featuredImage']));
              
        return response()->json([ 'post' => $post, 'relatedPosts' => $relatedPosts]);
    }

    public function categoryIndex(Request $request, $categoryName, $id)
    {
        $posts = Post::with(['tag', 'cat','user'])->whereHas('cat', function($q) use($id){
            $q->where('category_id',$id); // use() method is needed to pass variables to the closure
        })->orderBy('id','desc')->select(['id', 'title', 'postExcerpt', 'slug', 'user_id', 'featuredImage','created_at'])->paginate($request->total);
        return response()->json([ 'posts' => $posts, 'categoryName' => $categoryName]); 
    }

    public function tagIndex(Request $request, $tagName, $id)
    {
       $posts = Post::with(['tag', 'cat','user'])->whereHas('tag', function($q) use($id){
            $q->where('tag_id',$id);
        })->orderBy('id','desc')->select(['id', 'title', 'postExcerpt', 'slug', 'user_id', 'featuredImage','created_at'])->paginate($request->total);
        return response()->json([ 'posts' => $posts, 'tagName' => $tagName]); 
    }

    public function userIndex(Request $request, $userName, $id)
    {
       $posts = Post::with(['tag', 'cat','user'])->whereHas('user', function($q) use($id){
            $q->where('user_id',$id);
        })->orderBy('id','desc')->select(['id', 'title', 'postExcerpt', 'slug', 'user_id', 'featuredImage','created_at'])->paginate($request->total);
        return response()->json([ 'posts' => $posts, 'userName' => $userName]); 
    }

    public function search(Request $request)
    {   
        $str = $request->str;
        $posts = Post::orderBy('id', 'desc')->with(['cat','tag', 'user'])->select('id', 'title', 'postExcerpt', 'slug', 'user_id', 'featuredImage','created_at'); 
  
        $posts->when($str!='', function($q) use($str){
            $q->where('title','LIKE',"%$str%")
            ->orWhereHas('cat', function($q) use($str){
                // this will search 'cat' relation pivot table
                $q->where('categoryName','LIKE',"%$str%");
            })
            ->orWhereHas('tag', function($q) use($str){
                // this will search 'tag' relation pivot table
                $q->where('tagName','LIKE',"%$str%");
            });
        });
 
        $posts = $posts->paginate($request->total); 
        // use appends to get correct url when using pagination with vue (otherwise query str= will get removed)
        $posts = $posts->appends($request->all()) ; 
   
        return response()->json(['posts' => $posts]);
    }
}
