<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Module;
use App\Models\Content;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Content::truncate(); // Clear the table first

        $fintechModule = Module::where('title', 'Apa itu fintech')->first();

        if ($fintechModule) {
            Content::create([
                'module_id' => $fintechModule->id,
                'title' => 'Pengantar Fintech',
                'type' => 'article',
                'body' => '<p>Financial Technology, atau yang lebih dikenal dengan Fintech, adalah inovasi dalam sektor jasa keuangan yang memanfaatkan teknologi. Sederhananya, Fintech adalah penggunaan teknologi untuk membuat layanan keuangan menjadi lebih efisien, mudah diakses, dan lebih terjangkau.</p><p>Contoh paling umum dari Fintech yang kita temui sehari-hari adalah dompet digital (seperti GoPay, OVO), pembayaran QR code, transfer uang online, hingga platform pinjaman online (P2P Lending) dan investasi reksa dana online.</p><p>Tujuan utama Fintech adalah untuk mendemokratisasi akses ke layanan keuangan dan menantang model perbankan tradisional yang seringkali dianggap kaku dan lambat.</p>',
                'order' => 1,
            ]);
        }
    }
}
