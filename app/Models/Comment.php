<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'comments'
    ];

    public function post()
    {
        return $this->belongstoMany(Post::class,'post_comment','comment_id','post_id')->withTimestamps();;
    }
}
