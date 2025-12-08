<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BankAccount;
use Illuminate\Support\Facades\DB;


class BankAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert Kas Tunai sebagai special account
        BankAccount::firstOrCreate(
            [
                'account_type' => 'cash',
                'is_cash' => true
            ],
            [
                'bank_name' => 'Kas Tunai',
                'account_number' => '-',
                'account_holder' => 'Nama Organisasimu',
                'bank_logo' => null,
                'qris_image' => null,
                'is_active' => true,
            ]
        );

        // Optional: Update existing bank accounts jika sudah ada data
        DB::table('bank_accounts')
            ->where('is_cash', false)
            ->whereNull('account_type')
            ->update(['account_type' => 'bank']);
    }
}
