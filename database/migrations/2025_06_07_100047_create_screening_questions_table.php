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
        Schema::create('screening_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('question_no')->unique(); // Nomor urut pertanyaan (1-10)
            $table->text('question_text'); // Isi pertanyaan
            $table->boolean('is_special')->default(false); // Apakah ini pertanyaan khusus?
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('screening_questions');
    }
};
