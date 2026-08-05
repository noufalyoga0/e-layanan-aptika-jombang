<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_code')->unique();
            $table->string('opd_name');
            $table->string('service_id');
            $table->string('service_name');
            $table->text('detail_target');
            $table->enum('status', ['menunggu_verifikasi', 'disposisi', 'diproses', 'selesai', 'ditolak'])->default('menunggu_verifikasi');
            $table->string('priority')->default('Normal');
            $table->text('notes')->nullable();
            $table->string('assigned_to')->default('Belum Didisposisi');
            $table->text('disp_notes')->nullable();
            $table->text('tech_result')->nullable();
            $table->timestamps();
        });

        Schema::create('ticket_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade');
            $table->string('title');
            $table->text('desc');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_logs');
        Schema::dropIfExists('tickets');
    }
};
