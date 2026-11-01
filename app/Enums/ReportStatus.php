<?php

namespace App\Enums;

enum ReportStatus: string
{
    case Baru = 'baru';
    case Diperiksa = 'diperiksa';
    case PerluTindakLanjut = 'perlu_tindak_lanjut';
    case Diteruskan = 'diteruskan';
    case Selesai = 'selesai';

    public function label(): string
    {
        return match ($this) {
            self::Baru => 'Baru',
            self::Diperiksa => 'Diperiksa',
            self::PerluTindakLanjut => 'Perlu Tindak Lanjut',
            self::Diteruskan => 'Diteruskan',
            self::Selesai => 'Selesai',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Baru => 'badge-baru',
            self::Diperiksa => 'badge-diperiksa',
            self::PerluTindakLanjut => 'badge-perlu',
            self::Diteruskan => 'badge-diteruskan',
            self::Selesai => 'badge-selesai',
        };
    }
}
