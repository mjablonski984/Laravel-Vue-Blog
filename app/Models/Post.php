<?php

namespace App\Models;

use App\Models\Category;
use App\Models\Postcategory;
use App\Models\Posttag;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'post',
        'postExcerpt',
        'slug',
        'user_id',
        'featuredImage',
        'metaDescription',
        'views',
        'jsonData'];


        // Set mutator to create unique slug each time we create blogpost
        public function setTitleAttribute($title){
            $this->attributes['title'] = $title;
            $this->attributes['slug'] = $this->uniqueSlug($title); //"this is title" ==> "this-is-title
        }
    
        private function uniqueSlug($title){
            $slug = Str::slug($title, '-'); 
            $count = Post::where('slug', 'LIKE', "{$slug}%")->count(); //slug must start with title str
            $newCount = $count > 0 ? ++$count : ''; 
            return $newCount > 0 ? "$slug-$newCount" : $slug; // if already existe add count+1 to slug title
        }    

    /* Relationships
     belongsToMany - many-to-many relationship. Posts may belong to many categories/tags (which are shared between posts).
     belongsTo - inverse one-to-one or many relationship. Post belongs to one user.
     */
    public function tag() 
    {
        return $this->belongsToMany(Tag::class,Posttag::class);
    }

    public function cat() 
    {
        return $this->belongsToMany(Category::class,Postcategory::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class)->select('id', 'name','avatar');
    }
}
