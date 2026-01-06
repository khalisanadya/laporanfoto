<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->string('nama_kegiatan')->nullable()->after('id');
            $table->string('waktu_kegiatan')->nullable()->after('nama_kegiatan');
            $table->string('jenis_kegiatan')->nullable()->after('waktu_kegiatan');
            $table->string('lokasi_kegiatan')->nullable()->after('jenis_kegiatan');
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn(['nama_kegiatan', 'waktu_kegiatan', 'jenis_kegiatan', 'lokasi_kegiatan']);
        });
    }
};
