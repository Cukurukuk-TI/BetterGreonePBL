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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('label'); // Untuk nama alamat, misal: "Rumah", "Kantor"
            $table->string('recipient_name'); // Nama penerima
            $table->string('phone_number'); // No. HP penerima
            $table->text('full_address'); // Detail alamat lengkap
            $table->string('city'); // Kota/Kabupaten
            $table->string('province'); // Provinsi
            $table->string('postal_code'); // Kode Pos
            $table->boolean('is_default')->default(false); // Penanda alamat utama
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
