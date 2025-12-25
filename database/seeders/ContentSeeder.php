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

        $fintechModule = Module::where('title', 'Apa itu fintech')->first();

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
                    'title' => 'Apa itu Fintech?',
                    'media_url' => 'https://www.youtube.com/watch?v=k1t6tV5V-dE', // Dummy URL
                ],
                [
                    'title' => 'Tips Mengelola Keuangan',
                    'media_url' => 'https://www.youtube.com/watch?v=prYd15J2wM0', // Dummy URL
                ],
                [
                    'title' => 'Investasi untuk Pemula',
                    'media_url' => 'https://www.youtube.com/watch?v=S7wWvF2j9iQ', // Dummy URL
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
                    'title' => 'Cara Kerja P2P Lending',
                    'media_url' => 'https://img.freepik.com/free-vector/infographic-template-with-steps_23-2147851609.jpg', // Placeholder
                    'description' => 'Memahami alur pinjaman online yang aman dan legal.',
                ],
                [
                    'title' => 'Tips Hemat Ala Milenial',
                    'media_url' => 'https://img.freepik.com/free-vector/business-infographic-template-with-four-steps_23-2148729962.jpg', // Placeholder
                    'description' => 'Strategi menabung untuk masa depan yang lebih cerah.',
                ],
                [
                    'title' => 'Instrumen Investasi',
                    'media_url' => 'https://img.freepik.com/free-vector/modern-business-infographic-template_23-2148466185.jpg', // Placeholder
                    'description' => 'Mengenal jenis-jenis investasi risiko rendah hingga tinggi.',
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
