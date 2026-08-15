@extends('layouts.dashboard')

@section('content')
<div class="p-4 max-w-lg">
    <h1 class="text-xl font-semibold mb-4">Edit Supplier</h1>
    <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block mb-1 text-sm font-medium">Nama</label>
            <input type="text" name="name" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5" value="{{ old('name', $supplier->name) }}">
        </div>
        <div class="mb-4">
            <label class="block mb-1 text-sm font-medium">Telepon</label>
            <input type="text" name="phone" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5" value="{{ old('phone', $supplier->phone) }}">
        </div>
        <div class="mb-4">
            <label class="block mb-1 text-sm font-medium">Email</label>
            <input type="email" name="email" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5" value="{{ old('email', $supplier->email) }}">
        </div>
        <div class="mb-4">
            <label class="block mb-1 text-sm font-medium">Alamat</label>
            <textarea name="address" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5">{{ old('address', $supplier->address) }}</textarea>
        </div>
        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5">Update</button>
        <a href="{{ route('suppliers.index') }}" class="ml-2 text-sm text-gray-600">Batal</a>
    </form>
</div>
@endsection
