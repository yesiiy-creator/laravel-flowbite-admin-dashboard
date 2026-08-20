@extends('layouts.stockify')
@section('content')
<h1 class="text-3xl font-bold text-gray-900 dark:text-white">Supplier</h1>
<div class="mt-6 grid gap-6 lg:grid-cols-3">
    <form method="POST" action="{{route('suppliers.store')}}" class="rounded-xl bg-white p-5 shadow">
        @csrf
        <input class="w-full rounded border-slate-300" name="name" placeholder="Nama supplier" required>
        <input class="mt-3 w-full rounded border-slate-300" name="phone" placeholder="Telepon">
        <input class="mt-3 w-full rounded border-slate-300" type="email" name="email" placeholder="Email">
        <textarea class="mt-3 w-full rounded border-slate-300" name="address" placeholder="Alamat"></textarea>
        <button class="mt-3 rounded bg-emerald-600 px-4 py-2 text-white">Tambah</button>
    </form>
    <div class="lg:col-span-2 rounded-xl bg-white p-5 shadow">
        @foreach($suppliers as $s)
        <div class="flex items-center justify-between border-b py-3">
            <div>
                <b>{{$s->name}}</b>
                <p class="text-sm text-slate-500">{{$s->phone}} · {{$s->email}}</p>
                <p class="text-sm text-slate-400">{{$s->address}}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{route('suppliers.edit', $s->id)}}" class="text-blue-600 hover:underline">Edit</a>
                <form method="POST" action="{{route('suppliers.destroy', $s->id)}}" onsubmit="return confirm('Yakin hapus supplier ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
