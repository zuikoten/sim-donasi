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
        Schema::table('beneficiaries', function (Blueprint $table) {
            // Tambahkan kolom nomor_telepon
            $table->string('nomor_telepon')->nullable()->after('alamat');

            // Hapus foreign key terlebih dahulu
            $table->dropForeign(['program_id']);

            // Lalu hapus kolom program_id
            $table->dropColumn('program_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('beneficiaries', function (Blueprint $table) {
            // Tambah kembali kolom program_id untuk rollback
            $table->foreignId('program_id')->constrained('programs')->after('keterangan');

            // Hapus kolom nomor_telepon
            $table->dropColumn('nomor_telepon');
        });
    }
};
