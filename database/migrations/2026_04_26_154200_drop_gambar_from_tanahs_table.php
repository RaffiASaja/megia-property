<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tanahs', function (Blueprint $table) {
            if (Schema::hasColumn('tanahs', 'gambar')) {
                $table->dropColumn('gambar');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tanahs', function (Blueprint $table) {
            if (!Schema::hasColumn('tanahs', 'gambar')) {
                $table->string('gambar')->nullable();
            }
        });
    }
};
