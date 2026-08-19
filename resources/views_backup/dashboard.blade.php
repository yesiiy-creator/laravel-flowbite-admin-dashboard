@extends('layouts.stockify')

@section('content')

<h1 class="text-3xl font-bold text-gray-900 dark:text-white">
    Dashboard Stockify
</h1>

<p class="mt-1 text-slate-500">
    @if($role === 'admin')
        Ringkasan sistem dan aktivitas pengguna.
    @elseif($role === 'manajer_gudang')
        Ringkasan kondisi stok dan aktivitas gudang.
    @else
        Daftar tugas gudang yang perlu dikerjakan.
    @endif
</p>

{{-- ================= STAFF GUDANG ================= --}}
@if($role === 'staff_gudang')

<div class="mt-7 grid gap-6 lg:grid-cols-2">

    <section class="rounded-xl bg-black p-6 shadow-sm border border-gray-800">
        <h2 class="font-bold text-white">
            📥 Barang Masuk Hari Ini
        </h2>

        <p class="mt-1 text-sm text-slate-400">
            Barang yang perlu diperiksa dan diterima.
        </p>

        @forelse($incomingToday as $item)
            <div class="mt-4 border-b border-gray-800 pb-3 text-gray-200">
                {{ $item->product->name }}

                <b class="float-right text-emerald-400">
                    +{{ $item->quantity }}
                </b>
            </div>
        @empty
            <p class="mt-4 text-slate-500">
                Tidak ada tugas barang masuk hari ini.
            </p>
        @endforelse
    </section>

    <section class="rounded-xl bg-black p-6 shadow-sm border border-gray-800">
        <h2 class="font-bold text-white">
            📤 Barang Keluar Hari Ini
        </h2>

        <p class="mt-1 text-sm text-slate-400">
            Barang yang perlu disiapkan dan dikeluarkan.
        </p>

        @forelse($outgoingToday as $item)
            <div class="mt-4 border-b border-gray-800 pb-3 text-gray-200">
                {{ $item->product->name }}

                <b class="float-right text-rose-400">
                    -{{ $item->quantity }}
                </b>
            </div>
        @empty
            <p class="mt-4 text-slate-500">
                Tidak ada tugas barang keluar hari ini.
            </p>
        @endforelse
    </section>

</div>

{{-- ================= ADMIN ================= --}}
@elseif($role === 'admin')

<div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">

    <div class="rounded-xl bg-blue-50 p-5">
        <p class="text-slate-600">Total Produk</p>
        <b class="text-3xl">{{ $totalProducts }}</b>
    </div>

    <div class="rounded-xl bg-rose-50 p-5">
        <p class="text-slate-600">Stok Menipis</p>
        <b class="text-3xl">{{ $lowStock }}</b>
    </div>

    <div class="rounded-xl bg-emerald-50 p-5">
        <p class="text-slate-600">Masuk Hari Ini</p>
        <b class="text-3xl">{{ $incomingTodayCount }}</b>
    </div>

    <div class="rounded-xl bg-amber-50 p-5">
        <p class="text-slate-600">Keluar Hari Ini</p>
        <b class="text-3xl">{{ $outgoingTodayCount }}</b>
    </div>

</div>

<div class="mt-7 rounded-xl bg-black p-6 shadow-sm border border-gray-800">
    <h2 class="font-bold text-white">
        Grafik Stok Masuk & Keluar
    </h2>

    <p class="mt-1 text-sm text-slate-400">
        Aktivitas stok 7 hari terakhir.
    </p>

    <canvas id="stockChart" class="mt-4" height="90"></canvas>
</div>

<div class="mt-7 grid gap-6 lg:grid-cols-2">

    <section class="rounded-xl bg-black p-6 shadow-sm border border-gray-800">

        <h2 class="font-bold text-white">
            ⚠️ Stok Menipis
        </h2>

        @forelse($lowProducts as $p)

            <div class="mt-4 border-b border-gray-800 pb-3 text-gray-200">

                {{ $p->name }}

                <b class="float-right text-rose-400">
                    {{ $p->stock }} / min {{ $p->min_stock }}
                </b>

            </div>

        @empty

            <p class="mt-4 text-slate-500">
                Semua stok aman.
            </p>

        @endforelse

    </section>

    <section class="rounded-xl bg-black p-6 shadow-sm border border-gray-800">

        <h2 class="font-bold text-white">
            👤 Aktivitas Pengguna
        </h2>

        @forelse($activities as $a)

            <div class="mt-4 border-b border-gray-800 pb-3 text-gray-200">

                <b>{{ $a->action }}</b>

                <p class="text-sm text-slate-500">
                    {{ $a->description }}
                </p>

            </div>

        @empty

            <p class="mt-4 text-slate-500">
                Belum ada aktivitas pengguna.
            </p>

        @endforelse

    </section>

</div>

@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>

<script>

new Chart(document.getElementById('stockChart'), {

    type: 'line',

    data: {

        labels: @json($chartLabels),

        datasets: [

            {
                label: 'Barang Masuk',
                data: @json($chartIncoming),
                borderColor: '#10b981',
                backgroundColor: '#10b98120',
                tension: 0.3
            },

            {
                label: 'Barang Keluar',
                data: @json($chartOutgoing),
                borderColor: '#f43f5e',
                backgroundColor: '#f43f5e20',
                tension: 0.3
            }

        ]

    },

    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }

});

</script>

@endpush

{{-- ================= MANAJER GUDANG ================= --}}
@elseif($role === 'manajer_gudang')

<div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">

    <div class="rounded-xl bg-rose-50 p-5">
        <p class="text-slate-600">
            Stok Menipis
        </p>

        <b class="text-3xl">
            {{ $lowStock }}
        </b>
    </div>

    <div class="rounded-xl bg-emerald-50 p-5">
        <p class="text-slate-600">
            Barang Masuk Hari Ini
        </p>

        <b class="text-3xl">
            {{ $incomingTodayCount }}
        </b>
    </div>

    <div class="rounded-xl bg-amber-50 p-5">
        <p class="text-slate-600">
            Barang Keluar Hari Ini
        </p>

        <b class="text-3xl">
            {{ $outgoingTodayCount }}
        </b>
    </div>

</div>

<div class="mt-7 rounded-xl bg-black p-6 shadow-sm border border-gray-800">

    <h2 class="font-bold text-white">
        ⚠️ Produk dengan Stok Menipis
    </h2>

    @forelse($lowProducts as $p)

        <div class="mt-4 border-b border-gray-800 pb-3 text-gray-200">

            {{ $p->name }}

            <b class="float-right text-rose-400">
                {{ $p->stock }} / min {{ $p->min_stock }}
            </b>

        </div>

    @empty

        <p class="mt-4 text-slate-500">
            Tidak ada produk dengan stok menipis.
        </p>

    @endforelse

</div>

@endif

@endsection
