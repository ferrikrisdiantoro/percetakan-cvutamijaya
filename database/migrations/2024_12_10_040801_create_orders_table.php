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
        Schema::create('orders', function (Blueprint $table) {
            $table->string('id_pesanan')->primary(); // ID Pesanan (Primary Key)
            $table->string('id_produk');
            $table->string('id_user');
            $table->integer('kuantitas');  // Jumlah produk yang dibeli
            $table->decimal('total_pembayaran', 15, 2); // Total pembayaran
            $table->timestamps(); // Kolom created_at dan updated_at
            $table->foreign('id_produk')->references('id_produk')->on('products')->onDelete('cascade');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
