<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [
            [
                'title'        => 'Tren Otomasi Gudang 2026: Peran Forklift Elektrik',
                'slug'         => 'tren-otomasi-gudang-2026-peran-forklift-elektrik',
                'category'     => 'Industri',
                'excerpt'      => 'Perkembangan teknologi forklift elektrik semakin mendominasi industri logistik modern di Indonesia.',
                'content'      => '<p>Industri logistik Indonesia tengah mengalami transformasi besar. Forklift elektrik kini menjadi pilihan utama bagi perusahaan yang ingin meningkatkan efisiensi sekaligus mengurangi emisi karbon di area operasional mereka.</p>',
                'image'        => 'news/Ytj8Wpc6OUy7u57J1gd9zDxgmkeANqTx120niC8K.jpg',
                'is_published' => true,
                'published_at' => now()->subDays(5),
            ],
            [
                'title'        => 'Peluncuran Seri Forklift Reach Truck Terbaru 2026',
                'slug'         => 'peluncuran-seri-forklift-reach-truck-terbaru-2026',
                'category'     => 'Produk',
                'excerpt'      => 'Kami memperkenalkan seri reach truck generasi terbaru dengan kapasitas angkat hingga 2,5 ton dan efisiensi energi superior.',
                'content'      => '<p>Reach truck generasi terbaru hadir dengan berbagai peningkatan signifikan, mulai dari sistem baterai lithium-ion hingga fitur keselamatan otomatis yang canggih.</p>',
                'image'        => 'news/uVgrZgH8UmgUHy7DTY40HDvhGY5zJJBKH8QghZmF.jpg',
                'is_published' => true,
                'published_at' => now()->subDays(10),
            ],
            [
                'title'        => 'Panduan Lengkap Perawatan Forklift agar Awet',
                'slug'         => 'panduan-lengkap-perawatan-forklift-agar-awet',
                'category'     => 'Tips & Panduan',
                'excerpt'      => 'Perawatan rutin adalah kunci umur panjang forklift Anda. Berikut panduan lengkap yang wajib dilakukan setiap bulan.',
                'content'      => '<p>Forklift yang dirawat dengan baik dapat beroperasi lebih dari 10 tahun. Kunci utamanya adalah perawatan berkala yang konsisten dan penanganan cepat ketika ada masalah.</p>',
                'image'        => 'news/QtQfbkHYvekpduy2AuwOuPny8ghzvpkKPDbjDtiY.jpg',
                'is_published' => true,
                'published_at' => now()->subDays(15),
            ],
        ];

        foreach ($articles as $data) {
            News::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
