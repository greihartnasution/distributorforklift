<?php

namespace Database\Seeders;

use App\Models\HomepageTestimonial;
use Illuminate\Database\Seeder;

class HomepageTestimonialSeeder extends Seeder
{
    public function run(): void
    {
        HomepageTestimonial::truncate();

        $testimonials = [
            [
                'name'       => 'Budi Santoso',
                'position'   => 'General Manager',
                'company'    => 'PT. Logistik Nusantara',
                'quote'      => 'Layanan dari Distributor Forklift sangat profesional. Forklift yang kami gunakan bekerja optimal di gudang kami selama lebih dari 3 tahun tanpa masalah berarti. Tim purna jual mereka juga sangat responsif.',
                'image'      => null,
                'sort_order' => 1,
                'is_active'  => true,
            ],
            [
                'name'       => 'Sari Dewi',
                'position'   => 'Kepala Operasional',
                'company'    => 'CV. Maju Bersama',
                'quote'      => 'Kami sudah mempercayakan kebutuhan material handling kami kepada Distributor Forklift sejak 2020. Kualitas unit dan dukungan teknisnya jauh melampaui ekspektasi kami.',
                'image'      => null,
                'sort_order' => 2,
                'is_active'  => true,
            ],
            [
                'name'       => 'Ahmad Fauzi',
                'position'   => 'Direktur',
                'company'    => 'PT. Industri Prima',
                'quote'      => 'Proses pengadaan forklift sangat mudah dan transparan. Tim mereka membantu kami memilih unit yang paling sesuai dengan kebutuhan produksi kami. Sangat direkomendasikan.',
                'image'      => null,
                'sort_order' => 3,
                'is_active'  => true,
            ],
        ];

        foreach ($testimonials as $data) {
            HomepageTestimonial::create($data);
        }
    }
}
