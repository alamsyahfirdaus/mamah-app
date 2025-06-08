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
        Schema::create('educational_modules', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150); // Judul modul
            $table->text('content'); // Isi materi edukasi
            $table->string('video_url', 255)->nullable(); // Link video (opsional)
            $table->string('image_url', 255)->nullable(); // Gambar ilustrasi (opsional)
            $table->foreignId('category_id')->nullable()->constrained('module_categories')->onDelete('set null')->onUpdate('cascade');
            $table->boolean('is_visible')->default(true); // Kontrol tampil/tidak
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('educational_modules');
    }
};
