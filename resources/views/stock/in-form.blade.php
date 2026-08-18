@extends('layouts.stockify') @section('content')<h1 class="text-3xl font-bold text-gray-900 dark:text-white">Barang Masuk</h1><form method="POST" action="{{route('stock.in.store')}}" class="mt-6 max-w-xl rounded-xl bg-white p-6 shadow">@csrf @include('stock.partials.form',['suppliers'=>$suppliers,'isOut'=>false,'isOpname'=>false])</form>@endsection

