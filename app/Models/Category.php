<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name'];
    #Admin side (get the categories)
    public function categoryPost()
    {
        return $this->hasMany(CategoryPost::class);
    }

    #Get all story-category pivot records for this category
    public function categoryStory()
    {
        return $this->hasMany(CategoryStory::class);
    }
}
