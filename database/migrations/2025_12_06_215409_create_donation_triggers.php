<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Trigger untuk UPDATE donation
        DB::unprepared('
            CREATE TRIGGER after_donation_update
            AFTER UPDATE ON donations
            FOR EACH ROW
            BEGIN
                -- Jika status berubah menjadi terverifikasi
                IF NEW.status = "terverifikasi" AND OLD.status != "terverifikasi" THEN
                    UPDATE programs 
                    SET dana_terkumpul = dana_terkumpul + NEW.nominal
                    WHERE id = NEW.program_id;
                END IF;
                
                -- Jika status berubah dari terverifikasi ke status lain
                IF OLD.status = "terverifikasi" AND NEW.status != "terverifikasi" THEN
                    UPDATE programs 
                    SET dana_terkumpul = dana_terkumpul - OLD.nominal
                    WHERE id = OLD.program_id;
                END IF;
                
                -- Jika status tetap terverifikasi tapi nominal berubah
                IF NEW.status = "terverifikasi" AND OLD.status = "terverifikasi" AND NEW.nominal != OLD.nominal THEN
                    UPDATE programs 
                    SET dana_terkumpul = dana_terkumpul - OLD.nominal + NEW.nominal
                    WHERE id = NEW.program_id;
                END IF;
                
                -- Jika program berubah dan status terverifikasi
                IF NEW.status = "terverifikasi" AND OLD.status = "terverifikasi" AND NEW.program_id != OLD.program_id THEN
                    -- Kurangi dari program lama
                    UPDATE programs 
                    SET dana_terkumpul = dana_terkumpul - OLD.nominal
                    WHERE id = OLD.program_id;
                    
                    -- Tambah ke program baru
                    UPDATE programs 
                    SET dana_terkumpul = dana_terkumpul + NEW.nominal
                    WHERE id = NEW.program_id;
                END IF;
            END
        ');

        // Trigger untuk DELETE donation
        DB::unprepared('
            CREATE TRIGGER after_donation_delete
            AFTER DELETE ON donations
            FOR EACH ROW
            BEGIN
                IF OLD.status = "terverifikasi" THEN
                    UPDATE programs 
                    SET dana_terkumpul = dana_terkumpul - OLD.nominal
                    WHERE id = OLD.program_id;
                END IF;
            END
        ');
    }

    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS after_donation_update');
        DB::unprepared('DROP TRIGGER IF EXISTS after_donation_delete');
    }
};
