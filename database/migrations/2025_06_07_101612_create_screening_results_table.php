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
        Schema::create('screening_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id') // ID pengguna dengan peran 'ibu'
                ->comment('role_ibu')
                ->constrained('users')
                ->onDelete('cascade')
                ->onUpdate('cascade'); // FK ke tabel users
            $table->unsignedTinyInteger('score'); // Total skor EPDS (0–30)
            $table->string('category', 50); // Kategori: rendah, sedang, tinggi
            $table->text('recommendation')->nullable(); // Rekomendasi dari sistem
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('screening_results');
    }
};
