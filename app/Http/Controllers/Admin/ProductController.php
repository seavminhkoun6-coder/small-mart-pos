<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->get();
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'sku' => 'required|string|max:100|unique:products,sku',
            'product_name' => 'required|string|max:255',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'nullable|integer|min:0',
            'expiry_date' => 'nullable|date',
        ]);

        $validated['min_stock'] = $validated['min_stock'] ?? 5;
        $validated['status'] = $validated['stock'] > 0 ? 'Active' : 'Sold Out';

        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'បានបន្ថែមទំនិញថ្មីចូលប្រព័ន្ធដោយជោគជ័យ!');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'sku' => [
                'required',
                'string',
                'max:100',
                Rule::unique('products', 'sku')->ignore($product->id),
            ],
            'product_name' => 'required|string|max:255',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'nullable|integer|min:0',
            'expiry_date' => 'nullable|date',
        ]);

        $validated['min_stock'] = $validated['min_stock'] ?? 5;
        $validated['status'] = $validated['stock'] > 0 ? 'Active' : 'Sold Out';

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'បានកែសម្រួលព័ត៌មានទំនិញដោយជោគជ័យ!');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'បានលុបទំនិញចេញពីប្រព័ន្ធដោយជោគជ័យ!');
    }
}