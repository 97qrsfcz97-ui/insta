<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    public $timestamps = false;
    protected $primaryKey = ['post_id', 'user_id'];
    public $incrementing = false;

    # post
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
