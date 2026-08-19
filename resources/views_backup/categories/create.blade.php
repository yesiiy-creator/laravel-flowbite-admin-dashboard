@extends('layouts.dashboard')

@section('content')
<div class="p-4">
    <h1 class="text-2xl font-bold mb-4">Tambah Kategori</h1>

    <form action="{{ route('categories.store') }}" method="POST" class="max-w-lg">
        @csrf

        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-gray-900">Nama Kategori</label>
            <input type="text" name="name" value="{{ old('name') }}"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                required>
            @error('name')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-gray-900">Deskripsi</label>
            <textarea name="description"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">{{ old('description') }}</textarea>
        </div>

        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5">
            Simpan
        </button>
        <a href="{{ route('categories.index') }}" class="ml-2 text-gray-600 hover:underline">Batal</a>
    </form>
</div>
@endsection