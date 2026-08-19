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

    public function export()
    {
        $products = Product::with(['category', 'supplier'])->get();

        $filename = 'products-' . date('Y-m-d-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return response()->stream(function () use ($products) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'SKU',
                'Nama',
                'Kategori',
                'Supplier',
                'Harga Beli',
                'Harga Jual',
                'Stok',
                'Stok Minimum'
            ]);

            foreach ($products as $product) {
                fputcsv($handle, [
                    $product->sku,
                    $product->name,
                    $product->category->name ?? '',
                    $product->supplier->name ?? '',
                    $product->buy_price,
                    $product->sell_price,
                    $product->stock,
                    $product->min_stock,
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    public function importForm()
    {
        return view('products.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getRealPath(), 'r');

        fgetcsv($handle);

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 8) {
                continue;
            }

            $category = Category::firstOrCreate([
                'name' => trim($row[2])
            ]);

            $supplier = null;

            if (!empty(trim($row[3]))) {
                $supplier = Supplier::firstOrCreate([
                    'name' => trim($row[3])
                ]);
            }

            Product::updateOrCreate(
                ['sku' => trim($row[0])],
                [
                    'name' => trim($row[1]),
                    'category_id' => $category->id,
                    'supplier_id' => $supplier?->id,
                    'buy_price' => $row[4],
                    'sell_price' => $row[5],
                    'stock' => $row[6],
                    'min_stock' => $row[7],
                ]
            );
        }

        fclose($handle);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil diimport.');
    }
}
