@extends('layouts.stockify')

@section('content')
<h1 class="text-3xl font-bold text-gray-900 dark:text-white">Dashboard Stockify</h1>
<p class="mt-1 text-slate-500">Ringkasan stok dan aktivitas gudang.</p>

@if($role === 'staff_gudang')
    <div class="mt-7 grid gap-6 lg:grid-cols-2">
        <section class="rounded-xl bg-black p-6 shadow-sm border border-gray-800">
            <h2 class="font-bold text-white">Barang masuk hari ini (perlu diperiksa)</h2>
            @forelse($incomingToday as $item)
                <p class="mt-3 border-b border-gray-800 pb-2 text-gray-200">
                    {{ $item->product->name }}
                    <b class="float-right text-emerald-400">+{{ $item->quantity }}</b>
                </p>
            @empty
                <p class="mt-3 text-slate-500">Tidak ada barang masuk hari ini.</p>
            @endforelse
        </section>
        <section class="rounded-xl bg-black p-6 shadow-sm border border-gray-800">
            <h2 class="font-bold text-white">Barang keluar hari ini (perlu disiapkan)</h2>
            @forelse($outgoingToday as $item)
                <p class="mt-3 border-b border-gray-800 pb-2 text-gray-200">
                    {{ $item->product->name }}
                    <b class="float-right text-rose-400">-{{ $item->quantity }}</b>
                </p>
            @empty
                <p class="mt-3 text-slate-500">Tidak ada barang keluar hari ini.</p>
            @endforelse
        </section>
    </div>
@else
    <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach([['Produk',$totalProducts,'blue'],['Stok Menipis',$lowStock,'rose'],['Masuk Hari Ini',$incomingTodayCount,'emerald'],['Keluar Hari Ini',$outgoingTodayCount,'amber']] as [$a,$b,$c])
            <div class="rounded-xl bg-{{$c}}-50 p-5">
                <p>{{$a}}</p>
                <b class="text-3xl">{{$b}}</b>
            </div>
        @endforeach
    </div>

    <div class="mt-7 rounded-xl bg-black p-6 shadow-sm border border-gray-800">
        <h2 class="font-bold text-white">Grafik Stok Masuk & Keluar (7 Hari Terakhir)</h2>
        <canvas id="stockChart" class="mt-4" height="90"></canvas>
    </div>

    <div class="mt-7 grid gap-6 lg:grid-cols-2">
        <section class="rounded-xl bg-black p-6 shadow-sm border border-gray-800">
            <h2 class="font-bold text-white">Stok menipis</h2>
            @forelse($lowProducts as $p)
                <p class="mt-3 border-b border-gray-800 pb-2 text-gray-200">{{$p->name}} <b class="float-right text-rose-400">{{$p->stock}} / min {{$p->min_stock}}</b></p>
            @empty
                <p class="mt-3 text-slate-500">Aman.</p>
            @endforelse
        </section>

        @if($role === 'admin')
        <section class="rounded-xl bg-black p-6 shadow-sm border border-gray-800">
            <h2 class="font-bold text-white">Aktivitas terbaru</h2>
            @forelse($activities as $a)
                <p class="mt-3 border-b border-gray-800 pb-2 text-gray-200"><b>{{$a->action}}</b><br><span class="text-sm text-slate-500">{{$a->description}}</span></p>
            @empty
                <p class="mt-3 text-slate-500">Belum ada aktivitas.</p>
            @endforelse
        </section>
        @endif
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
    <script>
    new Chart(document.getElementById('stockChart'), {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [
                { label: 'Barang Masuk', data: @json($chartIncoming), borderColor: '#10b981', backgroundColor: '#10b98120', tension: 0.3 },
                { label: 'Barang Keluar', data: @json($chartOutgoing), borderColor: '#f43f5e', backgroundColor: '#f43f5e20', tension: 0.3 },
            ],
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } },
    });
    </script>
    @endpush
@endif
@endsection
