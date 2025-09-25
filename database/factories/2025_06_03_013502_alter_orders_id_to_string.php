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
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
        });
        Schema::table('orders', function (Blueprint $table) {
        $table->string('id', 100)->change();
    });

    Schema::table('order_items', function (Blueprint $table) {
        $table->string('order_id', 100)->change();
        $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
    });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('order_items', function (Blueprint $table) {
        $table->dropForeign(['order_id']);
    });

    Schema::table('order_items', function (Blueprint $table) {
        $table->unsignedBigInteger('order_id')->change();
    });

    Schema::table('orders', function (Blueprint $table) {
        $table->unsignedBigInteger('id')->change();
    });

    Schema::table('order_items', function (Blueprint $table) {
        $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
    });
}
};
