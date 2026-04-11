<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            // Hanya ubah jika column sudah ada
            if (Schema::hasColumn('reports', 'photo_path')) {
                $table->string('photo_path')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            if (Schema::hasColumn('reports', 'photo_path')) {
                $table->string('photo_path')->nullable(false)->change();
            }
        });
    }
};
