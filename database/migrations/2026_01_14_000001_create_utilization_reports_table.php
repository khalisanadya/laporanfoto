<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('utilization_reports', function (Blueprint $table) {
            $table->id();
            $table->string('judul')->default('UTILIZATION REPORT PER RO');
            $table->date('periode_mulai');
            $table->date('periode_selesai');
            $table->timestamps();
        });

        Schema::create('utilization_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('utilization_report_id')->constrained()->onDelete('cascade');
            $table->string('nama_section'); // e.g., "CYBER", "CITY BATAM", etc.
            $table->string('warna_header')->default('#FFA500'); // Orange color for header
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });

        Schema::create('utilization_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('utilization_section_id')->constrained()->onDelete('cascade');
            $table->string('nama_interface')->nullable(); // e.g., "CYB-IPD1-1006 - TenGigabitEthernet0/1/0.229"
            $table->string('label')->nullable(); // e.g., "IPTR", "PGAS IX", "LOCAL IX"
            
            // Inbound stats
            $table->string('inbound_current')->nullable();
            $table->string('inbound_average')->nullable();
            $table->string('inbound_maximum')->nullable();
            
            // Outbound stats
            $table->string('outbound_current')->nullable();
            $table->string('outbound_average')->nullable();
            $table->string('outbound_maximum')->nullable();
            
            // Summary values (for summary rows)
            $table->string('inbound_value')->nullable(); // e.g., "2500 Mbps"
            $table->string('outbound_value')->nullable(); // e.g., "967 Mbps"
            
            // Graph image
            $table->string('gambar_graph')->nullable();
            
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });

        Schema::create('utilization_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('utilization_section_id')->constrained()->onDelete('cascade');
            $table->string('kategori'); // e.g., "TOTAL PGAS-IX", "Total IP TRANSIT", etc.
            $table->string('inbound_value')->nullable();
            $table->string('outbound_value')->nullable();
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('utilization_summaries');
        Schema::dropIfExists('utilization_items');
        Schema::dropIfExists('utilization_sections');
        Schema::dropIfExists('utilization_reports');
    }
};
