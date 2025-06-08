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
        Schema::create('screening_choices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id') // Relasi ke screening_questions
                ->constrained('screening_questions')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->string('label', 100);
            $table->unsignedTinyInteger('score'); // Skor dari pilihan (0–3)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('screening_choices');
    }
};
