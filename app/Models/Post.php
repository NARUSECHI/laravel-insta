<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\CategoryPost;
use App\Models\Comment;
use App\Models\Like;

class Post extends Model
{
    use HasFactory,SoftDeletes;

    // A post belongs to a user
    // To get the owner of the post

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    // To get categories under a post
    public function categoryPost()
    {
        return $this->hasMany(CategoryPost::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    # To get likes of a post
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    # Return TRUE if the AUth user already liked the post
    public function isLiked()
    {
        return $this->likes()->where('user_id',Auth::user()->id)->exists();
    }
}
