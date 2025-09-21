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
            $table->enum('media_type', ['image', 'video', 'document'])->nullable(); // Jenis media
            $table->string('file_name', 150)->nullable(); // Nama file media
            $table->text('video_url')->nullable(); // URL video (YouTube/Google Drive/dll)
            $table->text('description')->nullable(); // Isi materi edukasi
            $table->foreignId('category_id')
                ->nullable()
                ->constrained('module_categories')
                ->onDelete('set null')
                ->onUpdate('cascade');
            $table->boolean('is_visible')->default(true); // Kontrol tampil/tidak
            $table->boolean('is_flyer')->default(false); // Video khusus flyer
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
