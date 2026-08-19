@extends('layouts.stockify') @section('content')<h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{$product->exists?'Edit Produk':'Produk Baru'}}</h1><form method="POST" action="{{$product->exists?route('products.update',$product->id):route('products.store')}}" class="mt-6 max-w-3xl rounded-xl bg-white p-6 shadow">@csrf @if($product->exists)@method('PUT')@endif <div class="grid gap-4 md:grid-cols-2"><label>Nama<input class="mt-1 w-full rounded border-slate-300" name="name" value="{{old('name',$product->name)}}" required></label><label>SKU<input class="mt-1 w-full rounded border-slate-300" name="sku" value="{{old('sku',$product->sku)}}" required></label><label>Kategori<input class="mt-1 w-full rounded border-slate-300" name="category_name" value="{{old('category_name',$product->category->name ?? '')}}" placeholder="mis. Pakaian, Elektronik, Tas" required></label><label>Supplier<input class="mt-1 w-full rounded border-slate-300" name="supplier_name" value="{{old('supplier_name',$product->supplier->name ?? '')}}" placeholder="mis. Toko Sumber Jaya"></label><label>Harga beli<input class="mt-1 w-full rounded border-slate-300" type="number" name="buy_price" value="{{old('buy_price',$product->buy_price??0)}}"></label><label>Harga jual<input class="mt-1 w-full rounded border-slate-300" type="number" name="sell_price" value="{{old('sell_price',$product->sell_price??0)}}"></label><label>Stok awal<input class="mt-1 w-full rounded border-slate-300" type="number" name="stock" value="{{old('stock',$product->stock??0)}}"></label><label>Stok minimum<input class="mt-1 w-full rounded border-slate-300" type="number" name="min_stock" value="{{old('min_stock',$product->min_stock??0)}}"></label></div><label class="mt-4 block">Deskripsi<textarea class="mt-1 w-full rounded border-slate-300" name="description">{{old('description',$product->description)}}</textarea></label><div class="mt-6">
    <div class="flex items-center justify-between">
        <label class="font-medium">Atribut Tambahan</label>
        <button type="button" onclick="addAttributeRow()" class="text-sm text-blue-600 hover:underline">+ Tambah Atribut</button>
    </div>
    <div id="attribute-rows" class="mt-2 space-y-2">
        @if(isset($product) && $product->exists)
            @foreach($product->attributes as $attr)
                <div class="flex gap-2">
                    <input class="w-1/2 rounded border-slate-300" name="attribute_name[]" value="{{ $attr->name }}" placeholder="Nama atribut (mis. Ukuran)">
                    <input class="w-1/2 rounded border-slate-300" name="attribute_value[]" value="{{ $attr->value }}" placeholder="Nilai (mis. XL)">
                </div>
            @endforeach
        @endif
    </div>
</div>

<script>
function addAttributeRow() {
    const container = document.getElementById('attribute-rows');
    const div = document.createElement('div');
    div.className = 'flex gap-2';
    div.innerHTML = `<input class="w-1/2 rounded border-slate-300" name="attribute_name[]" placeholder="Nama atribut (mis. Ukuran)"><input class="w-1/2 rounded border-slate-300" name="attribute_value[]" placeholder="Nilai (mis. XL)">`;
    container.appendChild(div);
}
</script>

<button class="mt-5 rounded bg-emerald-600 px-4 py-2 text-white">Simpan</button></form>@endsection


