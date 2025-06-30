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
        Schema::create('screening_levels', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('min_score')->nullable();     // Bisa null jika hanya kondisi khusus
            $table->unsignedTinyInteger('max_score')->nullable();     // Bisa null jika hanya kondisi khusus
            $table->string('category', 100)->nullable();              // Interpretasi umum (bisa null jika flag khusus)
            $table->text('recommendation');                           // Penatalaksanaan
            $table->foreignId('question_id')->nullable() // Relasi ke screening_questions
                ->comment('screening_flags')
                ->constrained('screening_questions')
                ->onUpdate('cascade')
                ->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('screening_levels');
    }
};
