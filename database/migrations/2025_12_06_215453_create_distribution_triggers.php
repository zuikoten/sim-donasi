<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Trigger untuk INSERT distribution - mengurangi dana_terkumpul
        DB::unprepared('
            CREATE TRIGGER after_distribution_insert
            AFTER INSERT ON distributions
            FOR EACH ROW
            BEGIN
                UPDATE programs 
                SET dana_terkumpul = dana_terkumpul - NEW.nominal_disalurkan
                WHERE id = NEW.program_id;
            END
        ');

        // Trigger untuk UPDATE distribution
        DB::unprepared('
            CREATE TRIGGER after_distribution_update
            AFTER UPDATE ON distributions
            FOR EACH ROW
            BEGIN
                -- Jika nominal berubah pada program yang sama
                IF NEW.program_id = OLD.program_id AND NEW.nominal_disalurkan != OLD.nominal_disalurkan THEN
                    UPDATE programs 
                    SET dana_terkumpul = dana_terkumpul + OLD.nominal_disalurkan - NEW.nominal_disalurkan
                    WHERE id = NEW.program_id;
                END IF;
                
                -- Jika program berubah
                IF NEW.program_id != OLD.program_id THEN
                    -- Kembalikan dana ke program lama
                    UPDATE programs 
                    SET dana_terkumpul = dana_terkumpul + OLD.nominal_disalurkan
                    WHERE id = OLD.program_id;
                    
                    -- Kurangi dana dari program baru
                    UPDATE programs 
                    SET dana_terkumpul = dana_terkumpul - NEW.nominal_disalurkan
                    WHERE id = NEW.program_id;
                END IF;
            END
        ');

        // Trigger untuk DELETE distribution - mengembalikan dana
        DB::unprepared('
            CREATE TRIGGER after_distribution_delete
            AFTER DELETE ON distributions
            FOR EACH ROW
            BEGIN
                UPDATE programs 
                SET dana_terkumpul = dana_terkumpul + OLD.nominal_disalurkan
                WHERE id = OLD.program_id;
            END
        ');
    }

    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS after_distribution_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS after_distribution_update');
        DB::unprepared('DROP TRIGGER IF EXISTS after_distribution_delete');
    }
};
