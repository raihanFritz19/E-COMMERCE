<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Produk::create([
        'nama' => 'Kue Tart Coklat',
        'deskripsi' => 'Kue coklat lembut dan lezat.',
        'harga' => 75000,
        'gambar' => 'produk1.jpg'
    ]);

    Produk::create([
        'nama' => 'Donat Premium',
        'deskripsi' => 'Donat empuk dengan topping manis.',
        'harga' => 35000,
        'gambar' => 'produk2.jpg'
    ]);
}
}
