<?php

namespace App\Http\Controllers;

use App\Models\PostCategory;
use App\Models\Post;
use App\Models\PostTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
  
    public function index(Request $request)
    {   
        if ($request->total){
            return response()->json(Post::with(['tag', 'cat','user'])->orderBy('id', 'desc')->paginate($request->total));
        } else {
            return response()->json(Post::with(['tag', 'cat','user'])->orderBy('id', 'desc')->get());
        }   
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'post' => 'required',
            'postExcerpt' => 'required',
            'metaDescription' => 'required',
            'jsonData' => 'required',
            'category_id' => 'required',
            'tag_id' => 'required',
        ]);

        $categories = $request->category_id;
        $tags = $request->tag_id;

        $postCategories = [];
        $postTags = [];
        /* You may use the transaction method on the DB facade to run a set of operations 
            within a database. If an exception is thrown within the transaction Closure,
            the transaction will automatically be rolled back (no data gets inserted)
            If you would like to begin a transaction manually and have complete control 
            over rollbacks and commits, you may use the beginTransaction */
        DB::beginTransaction();
        try {
            $post = Post::create([
                'title' => $request->title,
                'slug' => $request->title,
                'post' => $request->post, 
                'postExcerpt' => $request->postExcerpt,
                'user_id' => Auth::user()->id,
                'metaDescription' => $request->metaDescription,
                'jsonData' => $request->jsonData,
                'featuredImage' => $request->featuredImage ?? null
            ]);

            // insert post tags 
            foreach($tags as $t){
                array_push($postTags, ['tag_id' => $t, 'post_id' => $post->id]);
            }
            PostTag::insert($postTags);    

            // insert categories (must add created/updated_at manually)
            foreach($categories as $c){
                array_push($postCategories, ['category_id' => $c, 'post_id' => $post->id]);
            }

            // Use insert insted of create when using transaction
            PostCategory::insert($postCategories); 
            
 
            DB::commit(); // Commit transaction
            return 'Post Created';
        } catch (\Throwable $th) {
            DB::rollback(); // In case of errors rollback all changes
            return $th;
        }
    }


    public function show($id)
    {
        return Post::with(['tag', 'cat'])->where('id', $id)->first();
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'post' => 'required',
            'postExcerpt' => 'required',
            'metaDescription' => 'required',
            'jsonData' => 'required',
            'category_id' => 'required',
            'tag_id' => 'required',
        ]);
        $categories = $request->category_id;
        $tags = $request->tag_id;
        $postCategories = [];
        $postTags = [];
        
        $post = Post::where('id', $id)->first();
        if($post->featuredImage && $post->featuredImage != $request->featuredImage){
            $this->deleteFileFromServer($post->featuredImage); // delete old imagefile from the server
        }

        DB::beginTransaction();
        try {
            Post::where('id', $id)->update([
                'title' => $request->title,
                'slug' => $request->title,
                'post' => $request->post,
                'postExcerpt' => $request->postExcerpt,
                'metaDescription' => $request->metaDescription,
                'jsonData' => $request->jsonData,
                'featuredImage' => $request->featuredImage ?? null
            ]);


            // insert categories
            foreach ($categories as $c) {
                array_push($postCategories, ['category_id' => $c, 'post_id' => $id]);
            }
            // delete all previous categories
            PostCategory::where('post_id', $id)->delete();
            PostCategory::insert($postCategories);
            
            foreach ($tags as $t) {
                array_push($postTags, ['tag_id' => $t, 'post_id' => $id]);
            }
            Posttag::where('post_id', $id)->delete();
            Posttag::insert($postTags);

            DB::commit();
            return 'Post Updated';
        } catch (\Throwable $e) {
            DB::rollback();
            return $e;
        }
    }


    public function destroy(Request $request)
    {   
        $post = Post::where('id', $request->id)->first();
        if($post->featuredImage){
            $this->deleteFileFromServer($post->featuredImage); 
        }
        return $post->delete();
    }


        
    // Editor.js image upload
    public function uploadEditorImage(Request $request){
        
        $this->validate($request, [
            'image' => 'required|mimes:jpeg,jpg,png'
        ]);
        $picName = time().'.'.$request->image->extension();
        $request->image->move(public_path('uploads'),$picName );

        // Editor js (Vue wrapper) json object ( allows to display image back in the Vue component)
        return response()->json([
            'success' => 1, 
            'file' => [
                'url' => env('APP_URL')."uploads/$picName"
            ]
        ]);
        

        /* TO DO - REMOVE UNUSED IMAGES
        remove unused images that arent saved in the post :
        1 make temp_image_table
        2 whenever an image is uploaded add to temp_image_table id : 1, img: 34322.png
        3 before creating resource check if you have any image for that resource in temp_image_table,
        you can get this images and delete at the end 
        4 upload image
        */
    }
}
