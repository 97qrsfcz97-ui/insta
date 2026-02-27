<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryStory extends Model
{
    protected $table = 'category_story';
    protected $fillable = ['category_id', 'story_id'];
    protected $primaryKey = ['category_id', 'story_id'];
    public $incrementing = false;
    public $timestamps = false;

    #Get the category details for this record
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
