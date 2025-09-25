<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengirimen', function (Blueprint $table) {   
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('alamat');
            $table->string('kurir');
            $table->enum('status', ['belum_dikirim', 'dikirim', 'diterima'])->default('belum_dikirim');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengirimen');
    }
};
