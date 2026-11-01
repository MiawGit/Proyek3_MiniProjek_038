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

    /**
     * Status tujuan yang boleh dipilih dari status tertentu oleh peran tertentu
     * (BR-02: status akhir tidak bisa diubah lagi; BR-03: transisi tidak boleh
     * melompat atau mundur).
     *
     * @return array<string, list<self>> dipetakan dari nilai status saat ini
     */
    public static function nextFor(Role $role): array
    {
        return match ($role) {
            Role::Relawan => [
                self::Baru->value => [self::Diperiksa],
                self::Diperiksa->value => [self::PerluTindakLanjut, self::Selesai],
            ],
            Role::Koordinator => [
                self::PerluTindakLanjut->value => [self::Diteruskan],
                self::Diteruskan->value => [self::Selesai],
            ],
            Role::Pelapor => [],
        };
    }
}
