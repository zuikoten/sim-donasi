<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserBulkSeeder extends Seeder
{
    public function run()
    {
        $names = [
            "mba_nina",
            "lailatul_badriah",
            "ari_setyoningrum",
            "ria_astri_kirana",
            "budi_santoso",
            "meta",
            "dr_indra_sp_tht",
            "berry_purnomo",
            "eni",
            "deisy",
            "suseno_wahyu_purnomo",
            "rini_awal_brahma_vhysnu",
            "rini_elvina",
            "muhammad_jalalludin_h",
            "dodi",
            "bunda_fatiha",
            "bunda_naura",
            "bunda_aqila",
            "noor_kartika_sari",
            "ika_puspita",
            "disa_nursanti_karlina",
            "yadi_abdullah",
            "neni_adiningsih",
            "lola_m_datutiku",
            "wiwik_trainer",
            "zahra",
            "nani_andev",
            "mama_berlin",
            "adik_mama_berlin",
            "dewi_infomedia",
            "andrian",
            "mimbar",
            "budi_santoso_1",
            "una",
            "hen_hen",
            "harry",
            "agus_pri",
            "vhysnu",
            "brahma",
            "titi_aisyah",
            "chamid",
            "ummi_faris",
            "jannah",
            "mama_elsa",
            "fathya",
            "dewi_ocha",
            "bu_kuti",
            "asfaroh_noor_aeni",
            "nina",
            "renis",
            "alwita",
            "purwanto",
            "bunda_rafa",
            "jessi_rivaldini",
            "reny_paz",
            "heri",
            "anggun",
            "ana",
            "basuni_irawan",
            "siwi",
            "fitri",
            "mama_danis",
            "eyang_pa_leo",
            "hanggi",
            "bunda_alvito",
            "shita",
            "firman",
            "dimas_sso_tg",
            "yuyun_wahyuni",
            "rini_iswanti",
            "rizal",
            "santo",
            "bakhtiar",
            "susi",
            "kasriyadi",
            "adam",
            "furqon",
            "sri_wahyuni",
            "agustinus",
            "andi",
            "bunda_aqilla",
            "bu_santi",
            "yan_edwin",
            "dedy_mardianto",
            "bunda_mora",
            "anton_ramadhan",
            "aditya",
            "bunda_ardan",
            "bunda_alvito_1",
            "bunda_fatiha_1",
            "bu_mira",
            "bu_dini",
            "bu_andriani",
            "pt_tanjung",
            "bunda_ridho",
            "bunda_alfi",
            "bunda_faranina",
            "ustad_ammar",
            "ine",
        ];

        $usedEmails = [];

        foreach ($names as $name) {

            // 1. Buat slug dari nama (huruf kecil, tanpa spasi/karakter spesial)
            $base = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', str_replace(' ', '', $name)));
            if ($base === '') $base = 'user';

            $email = $base . '@arrahmanic.org';

            // 2. Cek duplikasi, tambahkan angka: ari2@..., ari3@...
            $counter = 2;
            while (in_array($email, $usedEmails)) {
                $email = $base . $counter . '@arrahmanic.org';
                $counter++;
            }
            $usedEmails[] = $email;

            // 3. Insert ke database
            DB::table('users')->insert([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make('password'),
                'role_id' => 3,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

//jalankan dengan 
//php artisan db:seed --class=UserBulkSeeder
