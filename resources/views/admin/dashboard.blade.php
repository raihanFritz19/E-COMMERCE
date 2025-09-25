@extends('layouts.admin') 
@section('content')
<div class="p-6 bg-white rounded-lg shadow">
    <h1 class="text-2xl font-bold text-green-700 mb-4">Dashboard Admin</h1>
    <p class="text-gray-700">Selamat datang, {{ auth()->user()->name ?? 'Admin' }}! 🎉</p>
</div>
@endsection