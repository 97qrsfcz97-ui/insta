<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryPost extends Model
{
    protected $table = 'category_post';
    protected $fillable = ['category_id', 'post_id'];
    protected $primaryKey = ['post_id', 'category_id'];
    public $incrementing = false;
    public $timestamps = false;

    #To get the name of the category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
