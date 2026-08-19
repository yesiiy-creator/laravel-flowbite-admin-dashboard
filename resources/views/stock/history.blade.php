@extends('layouts.stockify')

@section('content')
<div class="flex flex-wrap justify-between gap-3">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Stok & Transaksi</h1>
        <p class="text-slate-500">Catatan barang masuk, keluar, dan opname.</p>
    </div>
    <div class="flex gap-2">
        <a class="rounded bg-emerald-600 px-3 py-2 text-white" href="{{route('stock.in.create')}}">+ Masuk</a>
        <a class="rounded bg-amber-500 px-3 py-2 text-white" href="{{route('stock.out.create')}}">+ Keluar</a>
        <a class="rounded bg-slate-800 px-3 py-2 text-white" href="{{route('stock.opname.create')}}">Opname</a>
    </div>
</div>

<div class="mt-6 grid gap-5 lg:grid-cols-3">
    <section class="rounded-xl bg-white p-5 shadow">
        <h2 class="font-bold">Barang Masuk</h2>
        @forelse($ins as $item)
            <div class="mt-3 border-b pb-2">
                <div class="flex items-center justify-between">
                    <b>{{$item->product->name}}</b>
                    <span class="rounded px-2 py-0.5 text-xs {{$item->status==='confirmed'?'bg-emerald-100 text-emerald-700':'bg-amber-100 text-amber-700'}}">
                        {{$item->status==='confirmed'?'Dikonfirmasi':'Menunggu'}}
                    </span>
                </div>
                <p class="text-sm text-slate-500">{{$item->date->format('d/m/Y')}} &middot; {{$item->quantity}}</p>
                @if($item->status==='pending')
                    <form method="POST" action="{{route('stock.in.confirm',$item->id)}}" class="mt-1">
                        @csrf
                        <button class="text-xs text-blue-600 hover:underline">Konfirmasi</button>
                    </form>
                @endif
            </div>
        @empty
            <p class="mt-3 text-slate-500">Belum ada data.</p>
        @endforelse
    </section>

    <section class="rounded-xl bg-white p-5 shadow">
        <h2 class="font-bold">Barang Keluar</h2>
        @forelse($outs as $item)
            <div class="mt-3 border-b pb-2">
                <div class="flex items-center justify-between">
                    <b>{{$item->product->name}}</b>
                    <span class="rounded px-2 py-0.5 text-xs {{$item->status==='confirmed'?'bg-emerald-100 text-emerald-700':'bg-amber-100 text-amber-700'}}">
                        {{$item->status==='confirmed'?'Dikonfirmasi':'Menunggu'}}
                    </span>
                </div>
                <p class="text-sm text-slate-500">{{$item->date->format('d/m/Y')}} &middot; {{$item->quantity}}</p>
                @if($item->status==='pending')
                    <form method="POST" action="{{route('stock.out.confirm',$item->id)}}" class="mt-1">
                        @csrf
                        <button class="text-xs text-blue-600 hover:underline">Konfirmasi</button>
                    </form>
                @endif
            </div>
        @empty
            <p class="mt-3 text-slate-500">Belum ada data.</p>
        @endforelse
    </section>

    <section class="rounded-xl bg-white p-5 shadow">
        <h2 class="font-bold">Stock Opname</h2>
        @forelse($opnames as $item)
            <div class="mt-3 border-b pb-2">
                <b>{{$item->product->name}}</b>
                <p class="text-sm text-slate-500">{{$item->date->format('d/m/Y')}} &middot; {{$item->system_stock}} &rarr; {{$item->physical_stock}}</p>
            </div>
        @empty
            <p class="mt-3 text-slate-500">Belum ada data.</p>
        @endforelse
    </section>
</div>
@endsection