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
        Schema::create('support_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100); // Nama grup
            $table->text('description')->nullable(); // Deskripsi grup
            $table->foreignId('created_by') // ID pembuat grup (FK ke users)
                ->comment('user_id')
                ->constrained('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_groups');
    }
};
