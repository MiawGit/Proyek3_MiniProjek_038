<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Area;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Budi (Koordinator)',
            'email' => 'koordinator@karhutla.test',
            'password' => Hash::make('password'),
            'role' => Role::Koordinator,
        ]);

        User::create([
            'name' => 'Sari (Relawan)',
            'email' => 'relawan@karhutla.test',
            'password' => Hash::make('password'),
            'role' => Role::Relawan,
        ]);

        User::create([
            'name' => 'Andi (Pelapor)',
            'email' => 'pelapor@karhutla.test',
            'password' => Hash::make('password'),
            'role' => Role::Pelapor,
        ]);

        $areas = [
            ['name' => 'Kawasan Hutan Pinus', 'description' => 'Kawasan hutan pinus di perbatasan kecamatan.'],
            ['name' => 'Perkebunan Sawit Timur', 'description' => 'Area perkebunan kelapa sawit.'],
            ['name' => 'Lahan Gambut Selatan', 'description' => 'Lahan gambut yang rawan kebakaran saat kemarau.'],
            ['name' => 'Cagar Alam Utara', 'description' => 'Cagar alam dengan vegetasi lebat.'],
        ];

        foreach ($areas as $area) {
            Area::create($area);
        }
    }
}
