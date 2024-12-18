<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->string('id_pelanggan')->primary(); // Primary key custom
            $table->string('nama_lengkap'); // Kolom untuk nama lengkap
            $table->string('nama');         // Kolom nama
            $table->string('email')->unique();          // Email harus unik
            $table->string('password');                // Password
            $table->string('telepon');     // Nomor telepon
            $table->text('alamat');        // Alamat
            $table->string('role')->default('pelanggan');   // Role default 'user'
            $table->timestamps();                     // Kolom created_at dan updated_at
        });

        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false); // Menambahkan kolom is_admin
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
    
}

