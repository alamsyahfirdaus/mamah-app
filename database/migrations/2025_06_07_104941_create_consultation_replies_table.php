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
        Schema::create('consultation_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consultation_id') // Relasi ke konsultasi
                ->constrained('consultations')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreignId('sender_id') // ID pengirim (bidan atau ibu)
                ->comment('users_id_ibu_atau_bidan')
                ->constrained('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->text('message'); // Isi pesan balasan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultation_replies');
    }
};
