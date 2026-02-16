<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Post;

class CategoriesController extends Controller
{
    private $category;
    private $post;

    public function __construct(Category $category, Post $post)
    {
        $this->category = $category;
        $this->post = $post;
    }

    public function index()
    {
        $categories = $this->category->withCount('categoryPost')->latest()->get();
        $uncategorized_count = $this->post->doesntHave('categoryPost')->count();
        return view('admin.categories.index')
            ->with('categories', $categories)
            ->with('uncategorized_count', $uncategorized_count);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:categories'
        ]);

        $this->category->create(['name' => $request->name]);
        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:categories,name,' . $id
        ]);

        $this->category->findOrFail($id)->update(['name' => $request->name]);
        return redirect()->back();
    }

    public function destroy($id)
    {
        $this->category->findOrFail($id)->delete();
        return redirect()->back();
    }
}
