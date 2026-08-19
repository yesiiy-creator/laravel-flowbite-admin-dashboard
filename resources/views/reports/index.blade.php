@extends('layouts.stockify') @section('content')<h1 class="text-3xl font-bold text-gray-900 dark:text-white">Laporan Stok</h1><form class="mt-5 flex gap-3"><input class="rounded border-slate-300" type="date" name="from" value="{{$from}}"><input class="rounded border-slate-300" type="date" name="to" value="{{$to}}"><button class="rounded bg-slate-800 px-4 text-white">Filter</button></form><div class="mt-5 grid gap-4 sm:grid-cols-2"><div class="rounded bg-emerald-50 p-5">Barang masuk<b class="block text-3xl">{{$incoming}}</b></div><div class="rounded bg-amber-50 p-5">Barang keluar<b class="block text-3xl">{{$outgoing}}</b></div></div><div class="mt-6 overflow-hidden rounded-xl bg-white p-5 shadow"><h2 class="font-bold">Stok per produk</h2><table class="mt-3 w-full text-sm">@foreach($products as $p)<tr class="border-b"><td class="py-3">{{$p->name}}</td><td>{{$p->category->name}}</td><td class="text-right">{{$p->stock}}</td></tr>@endforeach</table></div>

<div class="mt-6 overflow-hidden rounded-xl bg-white p-5 shadow">
    <h2 class="font-bold">Laporan Aktivitas Pengguna</h2>
    <table class="mt-3 w-full text-sm">
        <thead>
            <tr class="border-b bg-slate-50 text-left">
                <th class="py-2 px-2">Waktu</th>
                <th class="py-2 px-2">Pengguna</th>
                <th class="py-2 px-2">Aksi</th>
                <th class="py-2 px-2">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($activities as $a)
                <tr class="border-b">
                    <td class="py-3 px-2 text-slate-500">{{ $a->created_at->format('d/m/Y H:i') }}</td>
                    <td class="py-3 px-2">{{ $a->user->name ?? '-' }}</td>
                    <td class="py-3 px-2 font-medium">{{ $a->action }}</td>
                    <td class="py-3 px-2 text-slate-500">{{ $a->description }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="py-6 text-center text-slate-500">Belum ada aktivitas.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

