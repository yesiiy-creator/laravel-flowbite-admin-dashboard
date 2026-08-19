@extends('layouts.stockify')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold">Import Produk</h1>

    <p class="mt-2 text-slate-500">
        Upload file CSV produk.
    </p>

    <form method="POST"
          action="{{ route('products.import') }}"
          enctype="multipart/form-data"
          class="mt-6 rounded-xl bg-white p-6 shadow">

        @csrf

        <label class="block">
            <span class="font-medium">File CSV</span>

            <input
                type="file"
                name="file"
                accept=".csv,.txt"
                required
                class="mt-2 block w-full rounded-lg border p-2"
            >
        </label>

        <div class="mt-6">
            <button
                type="submit"
                class="rounded-lg bg-emerald-600 px-4 py-2 text-white">
                Import Produk
            </button>

            <a
                href="{{ route('products.index') }}"
                class="ml-2 rounded-lg px-4 py-2 text-slate-600">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
