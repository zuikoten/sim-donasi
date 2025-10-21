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
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_program');
            $table->text('deskripsi');
            $table->enum('kategori', ['Zakat', 'Infaq', 'Sedekah', 'Wakaf']);
            $table->decimal('target_dana', 15, 2);
            $table->decimal('dana_terkumpul', 15, 2)->default(0);
            $table->enum('status', ['aktif', 'selesai', 'ditutup'])->default('aktif');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
