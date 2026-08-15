@extends('layouts.stockify')

@section('content')

<div class="mb-6">
    <h1 class="text-3xl font-bold">Pengaturan</h1>
    <p class="mt-1 text-slate-500">
        Pengaturan umum aplikasi Stockify.
    </p>
</div>

@if(session('success'))
    <div class="mb-5 rounded-lg bg-emerald-50 p-4 text-emerald-700">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="mb-5 rounded-lg bg-rose-50 p-4 text-rose-700">
        <ul class="list-disc pl-5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="max-w-2xl rounded-xl bg-white p-6 shadow-sm">

    <form action="{{ route('settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-5">
            <label class="mb-2 block font-medium">
                Nama Aplikasi
            </label>

            <input
                type="text"
                name="app_name"
                value="{{ $settings['app_name'] ?? 'Stockify' }}"
                class="w-full rounded-lg border border-slate-300 px-4 py-2.5"
                required
            >
        </div>

        <div class="mb-5">
            <label class="mb-2 block font-medium">
                Nama Gudang
            </label>

            <input
                type="text"
                name="warehouse_name"
                value="{{ $settings['warehouse_name'] ?? '' }}"
                class="w-full rounded-lg border border-slate-300 px-4 py-2.5"
                placeholder="Masukkan nama gudang"
            >
        </div>

        <button
            type="submit"
            class="rounded-lg bg-blue-600 px-5 py-2.5 font-medium text-white hover:bg-blue-700"
        >
            Simpan Pengaturan
        </button>

    </form>

</div>

@endsection