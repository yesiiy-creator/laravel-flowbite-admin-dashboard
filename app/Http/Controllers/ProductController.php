<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'supplier', 'attributes'])
            ->latest()
            ->paginate(10);

        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        return view('products.create', compact('categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_name' => 'required|string|max:255',
            'supplier_name' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:products,sku',
            'description' => 'nullable|string',
            'buy_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $category = \App\Models\Category::firstOrCreate(['name' => $data['category_name']]);
        $data['category_id'] = $category->id;
        unset($data['category_name']);

        if (!empty($data['supplier_name'])) {
            $supplier = \App\Models\Supplier::firstOrCreate(['name' => $data['supplier_name']]);
            $data['supplier_id'] = $supplier->id;
        }
        unset($data['supplier_name']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create($data);

        if ($request->has('attribute_name')) {
            foreach ($request->attribute_name as $index => $name) {
                $value = $request->attribute_value[$index] ?? null;

                if ($name && $value) {
                    $product->attributes()->create([
                        'name' => $name,
                        'value' => $value,
                    ]);
                }
            }
        }

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show($id)
    {
        $product = Product::with([
            'category',
            'supplier',
            'attributes'
        ])->findOrFail($id);

        return view('products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::with('attributes')->findOrFail($id);
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        return view('products.edit', compact(
            'product',
            'categories',
            'suppliers'
        ));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $data = $request->validate([
            'category_name' => 'required|string|max:255',
            'supplier_name' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:products,sku',
            'description' => 'nullable|string',
            'buy_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $category = \App\Models\Category::firstOrCreate(['name' => $data['category_name']]);
        $data['category_id'] = $category->id;
        unset($data['category_name']);

        if (!empty($data['supplier_name'])) {
            $supplier = \App\Models\Supplier::firstOrCreate(['name' => $data['supplier_name']]);
            $data['supplier_id'] = $supplier->id;
        }
        unset($data['supplier_name']);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $data['image'] = $request->file('image')->store(
                'products',
                'public'
            );
        }

        $product->update($data);

        $product->attributes()->delete();

        if ($request->has('attribute_name')) {
            foreach ($request->attribute_name as $index => $name) {
                $value = $request->attribute_value[$index] ?? null;

                if ($name && $value) {
                    $product->attributes()->create([
                        'name' => $name,
                        'value' => $value,
                    ]);
                }
            }
        }

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}



