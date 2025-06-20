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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_number')->nullable()->after('email');
            $table->date('date_of_birth')->nullable(); // BENAR
            $table->text('bio')->nullable();
            $table->string('profile_photo_path', 4096)->nullable();
        });
    }

    // Pastikan method down() juga diisi untuk bisa rollback
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone_number',
                'date_of_birth',
                'bio',
                'profile_photo_path',
            ]);
        });
    }

};
