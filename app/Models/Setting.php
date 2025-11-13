<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
    ];

    /**
     * Get setting value by key
     */
    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set or update setting
     */
    public static function set($key, $value, $type = 'text')
    {
        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => $type]
        );
    }

    /**
     * Get all settings as key-value array
     */
    public static function getAllSettings()
    {
        return self::pluck('value', 'key')->toArray();
    }

    /**
     * Mutator: Simpan nomor telepon hanya angka (tanpa +, spasi, tanda strip)
     */
    public function setOfficePhoneAttribute($value)
    {
        if ($this->attributes['key'] === 'office_phone') {
            // Hapus semua karakter non-digit
            $cleaned = preg_replace('/\D/', '', $value);

            // Pastikan awalan 62 (ganti 0 di awal)
            if (str_starts_with($cleaned, '0')) {
                $cleaned = '62' . substr($cleaned, 1);
            }

            $this->attributes['value'] = $cleaned;
        } else {
            $this->attributes['value'] = $value;
        }
    }

    /**
     * Accessor: Format nomor telepon untuk tampilan
     */
    public function getFormattedOfficePhoneAttribute()
    {
        if ($this->key !== 'office_phone') {
            return $this->value;
        }

        $phone = $this->value ?? '';

        if (!preg_match('/^62\d+$/', $phone)) {
            return $phone; // biarkan tampil apa adanya kalau format salah
        }

        $formatted = '+62 ';
        $numbers = substr($phone, 2); // buang awalan 62

        // Format dinamis, bukan kaku 3-4-4
        if (strlen($numbers) > 3) {
            $formatted .= substr($numbers, 0, 3);
        }
        if (strlen($numbers) > 3) {
            $formatted .= '-' . substr($numbers, 3, 4);
        }
        if (strlen($numbers) > 7) {
            $formatted .= '-' . substr($numbers, 7);
        }

        return $formatted;
    }
}
