@extends('layouts.app')

@section('content')
<div class="px-4 py-10"> 
    <h2 class="text-center text-2xl font-bold text-green-700 mb-8">Testimoni Pelanggan</h2>
    
    @if ($testimonis->count()) 
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 max-w-7xl mx-auto"> 
            @foreach ($testimonis as $testimoni) 
                <div class="bg-white shadow-md rounded-xl p-4 flex flex-col h-full"> 
                    <p class="italic text-gray-600 mb-2">“{{ $testimoni->isi }}”</p> 
                    <p class="mt-auto text-sm font-semibold text-gray-700"> 
                        – {{ $testimoni->user->name }} tentang <span class="italic">{{ $testimoni->produk->nama }}</span> 
                    </p> 
                    <div class="flex mt-2"> 
                        @for ($i = 1; $i <= 5; $i++) 
                            <span class="{{ $i <= $testimoni->rating ? 'text-yellow-400' : 'text-gray-300' }}">&#9733;</span> 
                        @endfor 
                    </div> 
                </div> 
            @endforeach 
        </div> 
    @else 
        <p class="text-center text-gray-500">Belum ada testimoni.</p> 
    @endif 
</div> 
@endsection