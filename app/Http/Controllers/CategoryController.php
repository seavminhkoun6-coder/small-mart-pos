<?php
namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // បង្ហាញបញ្ជី Categories ទាំងអស់
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    // រក្សាទុក Category ថ្មីចូល Database
    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Category::create([
            'category_name' => $request->category_name,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Category created successfully!');
    }
}
