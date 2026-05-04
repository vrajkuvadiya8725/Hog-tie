<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:10072'],
        ]);

        $category = new Category();
        $category->name = $validated['name'];
        if ($request->hasFile('image')) {
            $category->image_path = $request->file('image')->store('categories', 'public');
        }
        $category->save();

        return back()->with('success', 'Category added successfully.');
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:10072'],
        ]);

        $category->name = $validated['name'];
        if ($request->hasFile('image')) {
            if ($category->image_path) {
                Storage::disk('public')->delete($category->image_path);
            }
            $category->image_path = $request->file('image')->store('categories', 'public');
        }
        $category->save();

        return back()->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        if ($category->image_path) {
            Storage::disk('public')->delete($category->image_path);
        }
        $category->delete();

        return back()->with('success', 'Category deleted successfully.');
    }
}
