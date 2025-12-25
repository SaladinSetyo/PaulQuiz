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
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        Content::truncate(); // Clear the table first
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $fintechModule = Module::firstOrCreate(
            ['title' => 'Apa itu fintech'],
            ['description' => 'Memahami konsep dasar Financial Technology (Fintech) dan dampaknya.']
        );

        if ($fintechModule) {
            // Article
            Content::create([
                'module_id' => $fintechModule->id,
                'title' => 'Pengantar Fintech',
                'type' => 'article',
                'body' => '<p>Financial Technology, atau yang lebih dikenal dengan Fintech, adalah inovasi dalam sektor jasa keuangan yang memanfaatkan teknologi. Sederhananya, Fintech adalah penggunaan teknologi untuk membuat layanan keuangan menjadi lebih efisien, mudah diakses, dan lebih terjangkau.</p><p>Contoh paling umum dari Fintech yang kita temui sehari-hari adalah dompet digital (seperti GoPay, OVO), pembayaran QR code, transfer uang online, hingga platform pinjaman online (P2P Lending) dan investasi reksa dana online.</p><p>Tujuan utama Fintech adalah untuk mendemokratisasi akses ke layanan keuangan dan menantang model perbankan tradisional yang seringkali dianggap kaku dan lambat.</p>',
                'order' => 1,
            ]);

            // Videos
            $videos = [
                [
                    'title' => 'Apa itu Fintech? [BARU]',
                    'media_url' => 'https://www.youtube.com/watch?v=a81bXkES-gg',
                ],
                [
                    'title' => 'Perkembangan Fintech di Indonesia [BARU]',
                    'media_url' => 'https://www.youtube.com/watch?v=t2DBd2FfHCI',
                ],
                [
                    'title' => 'Jenis-Jenis Fintech [BARU]',
                    'media_url' => 'https://www.youtube.com/watch?v=G9qUhcBcRgY',
                ],
            ];

            foreach ($videos as $index => $video) {
                Content::create([
                    'module_id' => $fintechModule->id,
                    'title' => $video['title'],
                    'type' => 'video',
                    'media_url' => $video['media_url'],
                    'order' => $index + 2,
                    'is_featured' => true,
                ]);
            }

            // Infographics
            $infographics = [
                [
                    'title' => 'Perkembangan Fintech Lending',
                    'media_url' => 'https://img.alinea.id/img/library/library-2020-03/images/bisnis/Perkembangan%20Fintech%20Lending%20Indonesia-01.jpg',
                    'description' => 'Data perkembangan pinjaman online di Indonesia.',
                ],
                [
                    'title' => 'IPO Fintech',
                    'media_url' => 'https://img.alinea.id/img/library/library-2021-03/images/bisnis/IGR_IPO%20Fintech-01.jpg',
                    'description' => 'Infografis mengenai penawaran saham perdana perusahaan Fintech.',
                ],
                [
                    'title' => 'Mengenal Fintech',
                    'media_url' => 'https://cdn.antaranews.com/cache/infografis/1140x2100/2018/10/201810242018-10-24-Fintech.jpg?quality=85',
                    'description' => 'Penjelasan visual mengenai apa itu Fintech.',
                ],
            ];

            foreach ($infographics as $index => $infographic) {
                Content::create([
                    'module_id' => $fintechModule->id,
                    'title' => $infographic['title'],
                    'type' => 'infographic',
                    'media_url' => $infographic['media_url'],
                    'description' => $infographic['description'],
                    'order' => $index + 5,
                    'is_featured' => true,
                ]);
            }
        }
    }
}
