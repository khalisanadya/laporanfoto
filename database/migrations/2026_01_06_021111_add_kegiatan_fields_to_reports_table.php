<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            // Cek semua column sebelum menambahkan agar tidak duplikat
            if (!Schema::hasColumn('reports', 'nama_kegiatan')) {
                $table->string('nama_kegiatan')->nullable()->after('id');
            }
            if (!Schema::hasColumn('reports', 'waktu_kegiatan')) {
                $table->string('waktu_kegiatan')->nullable()->after('nama_kegiatan');
            }
            if (!Schema::hasColumn('reports', 'jenis_kegiatan')) {
                $table->string('jenis_kegiatan')->nullable()->after('waktu_kegiatan');
            }
            if (!Schema::hasColumn('reports', 'lokasi_kegiatan')) {
                $table->string('lokasi_kegiatan')->nullable()->after('jenis_kegiatan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            if (Schema::hasColumn('reports', 'nama_kegiatan')) {
                $table->dropColumn('nama_kegiatan');
            }
            if (Schema::hasColumn('reports', 'waktu_kegiatan')) {
                $table->dropColumn('waktu_kegiatan');
            }
            if (Schema::hasColumn('reports', 'jenis_kegiatan')) {
                $table->dropColumn('jenis_kegiatan');
            }
            if (Schema::hasColumn('reports', 'lokasi_kegiatan')) {
                $table->dropColumn('lokasi_kegiatan');
            }
        });
    }
};
