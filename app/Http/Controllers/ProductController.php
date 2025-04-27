<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = Product::query()
            ->orderBy('created_at', 'DESC')
            ->filter($request->only('filter'))
            ->paginate(8)
            ->withQueryString();

        return Inertia::render('Product/Index', [
            'products' => $products,
            'filters' => $request->all('filter'),
            'message' => session('message'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render(
            'Product/Create'
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'price' => 'required|numeric|min:0.01',
            'category' => 'required|max:100',
        ]);
        Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'category' => $request->category
        ]);

        return redirect()->route('products.index')->with('message', 'Product created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return Inertia::render(
            'Product/View',
            [
                'product' => $product
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return Inertia::render(
            'Product/Edit',
            [
                'product' => $product
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
           'name' => 'required|max:255',
           'price' => 'required|numeric|min:0.01',
           'category' => 'required|max:100',
        ]);
        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'category' => $request->category
        ]);

        return redirect()->route('products.index')->with('message', 'Product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('message', 'Product deleted successfully');
    }
}
