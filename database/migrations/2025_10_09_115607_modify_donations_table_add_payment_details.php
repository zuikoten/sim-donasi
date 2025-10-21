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
        Schema::table('donations', function (Blueprint $table) {
            // Ubah kolom metode_pembayaran menjadi ENUM
            $table->enum('metode_pembayaran', ['Uang Tunai', 'Transfer Bank', 'QRIS', 'E-Wallet'])->change();

            // Tambahkan kolom keterangan_tambahan
            $table->text('keterangan_tambahan')->nullable()->after('metode_pembayaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            // Kembalikan kolom metode_pembayaran ke string
            $table->string('metode_pembayaran')->change();

            // Hapus kolom keterangan_tambahan
            $table->dropColumn('keterangan_tambahan');
        });
    }
};
