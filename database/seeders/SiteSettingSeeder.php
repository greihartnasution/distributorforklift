<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        SiteSetting::truncate();

        SiteSetting::create([
            'site_name'        => null,
            'meta_description' => null,
            'og_image'         => null,
            'favicon'          => null,
            'phone'            => '+62 857 8007 8367',
            'whatsapp'         => '6285780078367',
            'email'            => null,
            'address'          => "Forklift STILL Authorized Dealer\nMugi Griya, Jl. Letjen M.T. Haryono Building, 7th Floor, Suite 703, Kec, Kec. Tebet, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12810",
            'instagram'        => 'distributorforklift',
            'tiktok'           => 'distributorforklift',
            'facebook'         => 'distributorforklift',
            'youtube'          => null,
            'showcase_heading' => 'Distributor Forklift terdepan dalam solusi material handling industri di Indonesia',
        ]);
    }
}
