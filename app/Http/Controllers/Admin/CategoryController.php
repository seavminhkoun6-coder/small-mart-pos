<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->latest()->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150|unique:categories,category_name',
            'description' => 'nullable|string|max:500',
            'status' => 'nullable|in:Active,Inactive',
        ]);

        Category::create([
            'category_name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'បានបន្ថែមប្រភេទទំនិញថ្មីដោយជោគជ័យ!');
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:150',
                Rule::unique('categories', 'category_name')->ignore($category->id),
            ],
            'description' => 'nullable|string|max:500',
            'status' => 'nullable|in:Active,Inactive',
        ]);

        $category->update([
            'category_name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'បានកែសម្រួលប្រភេទទំនិញដោយជោគជ័យ!');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'បានលុបប្រភេទទំនិញចេញពីប្រព័ន្ធដោយជោគជ័យ!');
    }
}