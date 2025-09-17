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
        // Tabel Provinsi
        Schema::create('provinces', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->nullable();   // Kode provinsi (misalnya kode BPS)
            $table->string('name');                         // Nama provinsi
            $table->text('description')->nullable();        // Keterangan tambahan
            $table->timestamps();
        });

        // Tabel Kabupaten/Kota
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->nullable();   // Kode kota/kabupaten
            $table->string('name');                         // Nama kota/kabupaten
            $table->foreignId('province_id')                // Relasi ke provinsi
                ->constrained('provinces')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->timestamps();
        });

        // Tabel Kecamatan
        Schema::create('districts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->nullable();   // Kode kecamatan
            $table->string('name');                         // Nama kecamatan
            $table->foreignId('city_id')                    // Relasi ke kota/kabupaten
                ->constrained('cities')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->timestamps();
        });

        // Tabel Desa/Kelurahan
        Schema::create('villages', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->nullable();   // Kode desa/kelurahan
            $table->string('name');                         // Nama desa/kelurahan
            $table->foreignId('district_id')                // Relasi ke kecamatan
                ->constrained('districts')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('villages');
        Schema::dropIfExists('districts');
        Schema::dropIfExists('cities');
        Schema::dropIfExists('provinces');
    }
};
