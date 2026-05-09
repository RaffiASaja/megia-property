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
        Schema::create('tanahs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_tanah')->unique();
            $table->string('ns', 24);
            $table->string('name', 100);
            $table->string('luas_tanah', 100);  

            // Sertifikat
            $table->enum('jenis_sertifikat', ['SHM', 'HGB', 'HP', 'HGU']);
            $table->date('tanggal_terbit')->nullable();
            $table->date('masa_berlaku')->nullable();

            // Lokasi
            $table->text('alamat');
            $table->string('provinsi');
            $table->string('kabupaten');
            $table->string('kecamatan');
            $table->string('desa');
            $table->string('kode_pos')->nullable();

            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            // Status
            $table->enum('status_tanah', ['aktif', 'sengketa', 'dijual'])->default('aktif');

            // Link Map
            $table->text('link_map')->nullable();

            $table->longText('polygon')->nullable();

            // Media (disimpan langsung di tabel tanahs)
            $table->json('foto')->nullable();
            $table->json('video')->nullable();
            $table->string('bukti_sertifikat')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tanahs');
    }
};
