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
        Schema::create('pregnant_mothers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade'); // kalau user dihapus, data ibu hamil ikut terhapus
            $table->integer('mother_age');                // Usia Ibu
            $table->integer('pregnancy_number');          // Kehamilan ke
            $table->integer('live_children_count');       // Jumlah Anak Lahir Hidup
            $table->integer('miscarriage_history')->default(0); // Jumlah riwayat keguguran
            $table->text('mother_disease_history')->nullable(); // Riwayat Penyakit Ibu
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pregnant_mothers');
    }
};
