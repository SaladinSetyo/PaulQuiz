<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Module;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Module::truncate(); // Clear the table first

        $modules = [
            [
                'title' => 'Apa itu fintech',
                'description' => 'Memahami konsep dasar Financial Technology (Fintech) dan dampaknya.'
            ],
            [
                'title' => 'Jenis-jenis Fintech berikut contohnya',
                'description' => 'Mengenal berbagai jenis layanan Fintech seperti pembayaran digital, pinjaman online, investasi, dan lainnya.'
            ],
            [
                'title' => 'Keamanan digital dan privasi',
                'description' => 'Tips menjaga keamanan data pribadi dan keuangan di era digital.'
            ],
            [
                'title' => 'Regulasi dan perlindungan',
                'description' => 'Memahami regulasi yang melindungi konsumen dalam menggunakan layanan Fintech.'
            ],
        ];

        foreach ($modules as $module) {
            Module::create($module);
        }
    }
}
